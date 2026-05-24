<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\BreedingCat;
use App\Models\GalleryImage;
use App\Models\Kitten;
use App\Models\Litter;
use App\Models\NewsPost;
use App\Models\Slide;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

#[Signature('media:sync-storage')]
#[Description('Move imported media files into storage/app/public/media and rewrite database paths.')]
class SyncMediaStorage extends Command
{
    private array $movedTargets = [];

    public function handle(): int
    {
        $stats = [
            'breeding_cats' => 0,
            'litters' => 0,
            'kittens' => 0,
            'gallery_images' => 0,
            'news_posts' => 0,
            'slides' => 0,
            'articles' => 0,
            'files_moved' => 0,
            'missing_files' => 0,
        ];

        $stats['breeding_cats'] = $this->normalizeImageArrays(
            BreedingCat::all(),
            'media/parents',
            ['public/images/parents', 'public/images'],
            $stats,
        );

        $stats['litters'] = $this->normalizeImageArrays(
            Litter::all(),
            'media/litters',
            ['public/images/litters', 'public/images'],
            $stats,
        );

        $stats['kittens'] = $this->normalizeImageArrays(
            Kitten::all(),
            'media/kittens',
            ['public/images/kittens', 'public/images'],
            $stats,
        );

        $stats['gallery_images'] = $this->normalizeSingleImageField(
            GalleryImage::all(),
            'image_path',
            'media/gallery',
            ['public/images/gallery', 'public/images'],
            $stats,
        );

        $stats['news_posts'] = $this->normalizeSingleImageField(
            NewsPost::all(),
            'image',
            'media/news',
            ['public/images/news', 'public/images'],
            $stats,
        );

        $stats['slides'] = $this->normalizeSingleImageField(
            Slide::all(),
            'image',
            'media/slides',
            ['public/images/gallery', 'public/images'],
            $stats,
        );

        $stats['articles'] = $this->normalizeSingleImageField(
            Article::all(),
            'image',
            'media/articles',
            ['public/images/articles', 'public/images'],
            $stats,
        );

        $this->moveLooseFiles('public/images/parents', 'media/parents', $stats);
        $this->moveLooseFiles('public/images/kittens', 'media/kittens', $stats);
        $this->moveLooseFiles('public/images/gallery', 'media/gallery', $stats);
        $this->moveLooseFiles('public/images/news', 'media/news', $stats);
        $this->moveLooseFiles('public/images/litters', 'media/litters', $stats);

        $this->table(
            ['Metric', 'Value'],
            [
                ['Breeding cats updated', $stats['breeding_cats']],
                ['Litters updated', $stats['litters']],
                ['Kittens updated', $stats['kittens']],
                ['Gallery images updated', $stats['gallery_images']],
                ['News posts updated', $stats['news_posts']],
                ['Slides updated', $stats['slides']],
                ['Articles updated', $stats['articles']],
                ['Files moved', $stats['files_moved']],
                ['Missing files', $stats['missing_files']],
            ]
        );

        $this->info('Media storage synchronization completed.');

        return self::SUCCESS;
    }

    private function normalizeImageArrays(iterable $records, string $targetDirectory, array $sourceDirectories, array &$stats): int
    {
        $updated = 0;

        foreach ($records as $record) {
            $images = is_array($record->images) ? $record->images : [];
            $normalized = [];
            $changed = false;

            foreach ($images as $image) {
                $normalizedPath = $this->normalizePath((string) $image, $targetDirectory, $sourceDirectories, $stats);
                $normalized[] = $normalizedPath;
                $changed = $changed || $normalizedPath !== $image;
            }

            if (! $changed) {
                continue;
            }

            $record->images = $normalized;
            $record->save();
            $updated++;
        }

        return $updated;
    }

    private function normalizeSingleImageField(iterable $records, string $field, string $targetDirectory, array $sourceDirectories, array &$stats): int
    {
        $updated = 0;

        foreach ($records as $record) {
            $value = (string) ($record->{$field} ?? '');
            $normalized = $this->normalizePath($value, $targetDirectory, $sourceDirectories, $stats);

            if ($normalized === $value) {
                continue;
            }

            $record->{$field} = $normalized;
            $record->save();
            $updated++;
        }

        return $updated;
    }

    private function normalizePath(string $path, string $targetDirectory, array $sourceDirectories, array &$stats): string
    {
        $path = trim($path);

        if ($path === '') {
            return $path;
        }

        $normalized = ltrim(str_replace('\\', '/', $path), '/');

        if (str_starts_with($normalized, trim($targetDirectory, '/').'/')) {
            return $normalized;
        }

        $basename = basename($normalized);
        $target = trim($targetDirectory, '/').'/'.$basename;

        if (Storage::disk('public')->exists($target)) {
            return $target;
        }

        $source = $this->findSource($normalized, $basename, $sourceDirectories);

        if ($source === null) {
            $stats['missing_files']++;

            return $path;
        }

        Storage::disk('public')->put($target, file_get_contents($source));

        if (! in_array($target, $this->movedTargets, true)) {
            $stats['files_moved']++;
            $this->movedTargets[] = $target;
        }

        if (str_starts_with($source, public_path('images/'))) {
            @unlink($source);
        }

        return $target;
    }

    private function findSource(string $originalPath, string $basename, array $sourceDirectories): ?string
    {
        $candidates = [];

        foreach ($sourceDirectories as $directory) {
            $directory = trim($directory, '/');

            if ($directory === '') {
                continue;
            }

            $candidates[] = base_path($directory.'/'.$originalPath);
            $candidates[] = base_path($directory.'/'.$basename);
        }

        foreach ($candidates as $candidate) {
            if (is_file($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    private function moveLooseFiles(string $sourceDirectory, string $targetDirectory, array &$stats): void
    {
        $absoluteSourceDirectory = base_path(trim($sourceDirectory, '/'));

        if (! is_dir($absoluteSourceDirectory)) {
            return;
        }

        foreach (scandir($absoluteSourceDirectory) ?: [] as $entry) {
            if (in_array($entry, ['.', '..'], true)) {
                continue;
            }

            $source = $absoluteSourceDirectory.'/'.$entry;

            if (! is_file($source)) {
                continue;
            }

            $target = trim($targetDirectory, '/').'/'.$entry;

            if (! Storage::disk('public')->exists($target)) {
                Storage::disk('public')->put($target, file_get_contents($source));
                $stats['files_moved']++;
            }

            @unlink($source);
        }
    }
}
