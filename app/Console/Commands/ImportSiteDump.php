<?php

namespace App\Console\Commands;

use App\Models\BreedingCat;
use App\Models\ContentPage;
use App\Models\GalleryCategory;
use App\Models\GalleryImage;
use App\Models\Kitten;
use App\Models\Litter;
use App\Models\NewsPost;
use App\Models\Review;
use App\Models\SiteSetting;
use App\Models\Slide;
use Carbon\CarbonImmutable;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Signature('site:import-dump
    {dump : Path to the source marmelama_rf.sql dump}
    {--fresh : Clear imported content tables before import}
    {--dry-run : Parse and report counts without writing}
    {--image-source=old_site/file : Directory with source uploaded images}')]
#[Description('Import public content from the Marmelama SQL dump.')]
class ImportSiteDump extends Command
{
    private const TABLES = [
        'cats',
        'content',
        'gallery',
        'gallery_cat',
        'kittens',
        'news',
        'pomet',
        'reviews',
        'settings',
        'slider',
    ];

    private array $sourceData = [];

    public function handle(): int
    {
        $dumpPath = (string) $this->argument('dump');

        if (! is_file($dumpPath)) {
            $this->error("Dump file not found: {$dumpPath}");

            return self::FAILURE;
        }

        $this->sourceData = $this->parseDump($dumpPath);

        $this->table([
            'Source table',
            'Rows',
        ], collect(self::TABLES)->map(fn (string $table) => [
            $table,
            count($this->sourceData[$table] ?? []),
        ])->all());

        if ($this->option('dry-run')) {
            $this->info('Dry run finished. Nothing was written.');

            return self::SUCCESS;
        }

        DB::transaction(function (): void {
            if ($this->option('fresh')) {
                $this->clearImportedTables();
            }

            $this->importBreedingCats();
            $this->importLitters();
            $this->importKittens();
            $this->importReviews();
            $this->importContentPages();
            $this->importGallery();
            $this->importSlides();
            $this->importNews();
            $this->importSettings();
        });

        $this->info('Data import completed.');

        return self::SUCCESS;
    }

    private function parseDump(string $dumpPath): array
    {
        $sql = file_get_contents($dumpPath);

        if ($sql === false) {
            throw new \RuntimeException("Unable to read dump: {$dumpPath}");
        }

        $result = array_fill_keys(self::TABLES, []);

        foreach (self::TABLES as $table) {
            foreach ($this->extractInsertStatements($sql, $table) as $statement) {
                [$columns, $values] = $this->splitInsertStatement($statement, $table);

                foreach ($this->parseValues($values) as $row) {
                    $result[$table][] = array_combine($columns, $row);
                }
            }
        }

        return $result;
    }

    private function extractInsertStatements(string $sql, string $table): array
    {
        $statements = [];
        $needle = "INSERT INTO `{$table}`";
        $offset = 0;

        while (($start = strpos($sql, $needle, $offset)) !== false) {
            $end = $this->findStatementEnd($sql, $start);
            $statements[] = substr($sql, $start, $end - $start + 1);
            $offset = $end + 1;
        }

        return $statements;
    }

    private function findStatementEnd(string $sql, int $start): int
    {
        $inString = false;
        $escaped = false;
        $length = strlen($sql);

        for ($i = $start; $i < $length; $i++) {
            $char = $sql[$i];

            if ($inString) {
                if ($escaped) {
                    $escaped = false;

                    continue;
                }

                if ($char === '\\') {
                    $escaped = true;

                    continue;
                }

                if ($char === "'") {
                    $inString = false;
                }

                continue;
            }

            if ($char === "'") {
                $inString = true;

                continue;
            }

            if ($char === ';') {
                return $i;
            }
        }

        throw new \RuntimeException('Unterminated INSERT statement in source dump.');
    }

    private function splitInsertStatement(string $statement, string $table): array
    {
        $pattern = '/^INSERT INTO `'.preg_quote($table, '/').'` \((.*?)\) VALUES\s*(.*);$/s';

        if (! preg_match($pattern, trim($statement), $matches)) {
            throw new \RuntimeException("Unable to parse INSERT statement for {$table}.");
        }

        $columns = array_map(
            fn (string $column): string => trim($column, " `\t\n\r\0\x0B"),
            explode(',', $matches[1])
        );

        return [$columns, trim($matches[2])];
    }

    private function parseValues(string $values): array
    {
        $rows = [];
        $row = [];
        $field = '';
        $fieldQuoted = false;
        $inRow = false;
        $inString = false;
        $escaped = false;
        $length = strlen($values);

        for ($i = 0; $i < $length; $i++) {
            $char = $values[$i];

            if ($inString) {
                if ($escaped) {
                    $field .= $this->decodeEscapedCharacter($char);
                    $escaped = false;

                    continue;
                }

                if ($char === '\\') {
                    $escaped = true;

                    continue;
                }

                if ($char === "'") {
                    $inString = false;

                    continue;
                }

                $field .= $char;

                continue;
            }

            if (! $inRow) {
                if ($char === '(') {
                    $inRow = true;
                    $row = [];
                    $field = '';
                    $fieldQuoted = false;
                }

                continue;
            }

            if ($char === "'") {
                $inString = true;
                $fieldQuoted = true;

                continue;
            }

            if ($char === ',') {
                $row[] = $this->normalizeField($field, $fieldQuoted);
                $field = '';
                $fieldQuoted = false;

                continue;
            }

            if ($char === ')') {
                $row[] = $this->normalizeField($field, $fieldQuoted);
                $rows[] = $row;
                $inRow = false;

                continue;
            }

            $field .= $char;
        }

        return $rows;
    }

    private function normalizeField(string $field, bool $quoted): mixed
    {
        if ($quoted) {
            return $field;
        }

        $field = trim($field);

        if (strcasecmp($field, 'NULL') === 0) {
            return null;
        }

        if (is_numeric($field)) {
            return str_contains($field, '.') ? (float) $field : (int) $field;
        }

        return $field;
    }

    private function decodeEscapedCharacter(string $char): string
    {
        return match ($char) {
            '0' => "\0",
            'n' => "\n",
            'r' => "\r",
            't' => "\t",
            'b' => "\b",
            'Z' => "\x1a",
            default => $char,
        };
    }

    private function clearImportedTables(): void
    {
        Schema::withoutForeignKeyConstraints(function (): void {
            foreach ([
                'site_settings',
                'slides',
                'gallery_images',
                'gallery_categories',
                'news_posts',
                'reviews',
                'kittens',
                'litters',
                'breeding_cats',
                'content_pages',
            ] as $table) {
                DB::table($table)->delete();
            }
        });
    }

    private function importBreedingCats(): void
    {
        foreach ($this->sourceData['cats'] ?? [] as $row) {
            BreedingCat::updateOrCreate(
                ['old_id' => $this->int($row, 'id')],
                [
                    'name' => $this->string($row, 'title') ?: 'Без имени',
                    'slug' => $this->slug($row, 'url', 'cat-'.$this->int($row, 'id')),
                    'sex' => ((int) ($row['pol'] ?? 1)) === 1 ? 'male' : 'female',
                    'is_active' => (bool) ($row['active'] ?? true),
                    'title' => $this->nullableString($row, 'prizes'),
                    'color' => $this->nullableString($row, 'color'),
                    'birthday' => $this->date($row['birthday'] ?? null),
                    'father_name' => $this->nullableString($row, 'father'),
                    'mother_name' => $this->nullableString($row, 'mother'),
                    'genetic_tests' => $this->extractGeneticTests($this->string($row, 'description')),
                    'breeder' => $this->nullableString($row, 'breeder'),
                    'owner' => $this->nullableString($row, 'owner'),
                    'description' => $this->nullableString($row, 'description'),
                    'content' => $this->nullableString($row, 'full_description'),
                    'images' => $this->images($row, 'media/parents', ['public/images/parents', 'public/images']),
                    'image_alt' => $this->nullableString($row, 'img_alt'),
                    'image_title' => $this->nullableString($row, 'img_title'),
                    'meta_title' => $this->nullableString($row, 'meta_title'),
                    'meta_description' => $this->nullableString($row, 'meta_desc'),
                    'meta_keywords' => $this->nullableString($row, 'meta_keywords'),
                    'sort_order' => (int) ($row['prior'] ?? 0),
                    'is_visible' => (bool) ($row['visible'] ?? true),
                ]
            );
        }

        $this->info('Imported breeding cats: '.count($this->sourceData['cats'] ?? []));
    }

    private function importLitters(): void
    {
        foreach ($this->sourceData['pomet'] ?? [] as $row) {
            $father = BreedingCat::where('old_id', $this->int($row, 'father_id'))->first();
            $mother = BreedingCat::where('old_id', $this->int($row, 'mother_id'))->first();

            Litter::updateOrCreate(
                ['old_id' => $this->int($row, 'id')],
                [
                    'title' => $this->string($row, 'title') ?: 'Помет '.$this->string($row, 'litera'),
                    'slug' => $this->slug($row, 'url', 'litter-'.$this->int($row, 'id')),
                    'letter' => $this->nullableString($row, 'litera'),
                    'born_on' => $this->extractDateFromText($this->string($row, 'title')),
                    'father_id' => $father?->id,
                    'mother_id' => $mother?->id,
                    'father_name' => $this->nullableString($row, 'father_name') ?: $father?->name,
                    'father_description' => $this->nullableString($row, 'father_desc'),
                    'father_image' => $this->image(
                        $this->string($row, 'father_img'),
                        'media/litters/parents',
                        ['public/images/litters', 'public/images']
                    ),
                    'mother_name' => $this->nullableString($row, 'mother_name') ?: $mother?->name,
                    'mother_description' => $this->nullableString($row, 'mother_desc'),
                    'mother_image' => $this->image(
                        $this->string($row, 'mother_img'),
                        'media/litters/parents',
                        ['public/images/litters', 'public/images']
                    ),
                    'status' => ((int) ($row['status'] ?? 1)) === 0 ? 'archive' : 'available',
                    'description' => $this->nullableString($row, 'description'),
                    'content' => $this->nullableString($row, 'full_description'),
                    'images' => $this->images($row, 'media/litters', ['public/images/litters', 'public/images']),
                    'meta_title' => $this->nullableString($row, 'meta_title'),
                    'meta_description' => $this->nullableString($row, 'meta_desc'),
                    'meta_keywords' => $this->nullableString($row, 'meta_keywords'),
                    'sort_order' => (int) ($row['prior'] ?? 0),
                    'is_visible' => (bool) ($row['visible'] ?? true),
                ]
            );
        }

        $this->info('Imported litters: '.count($this->sourceData['pomet'] ?? []));
    }

    private function importKittens(): void
    {
        foreach ($this->sourceData['kittens'] ?? [] as $row) {
            $litter = Litter::where('letter', $this->string($row, 'pomet'))->first();

            Kitten::updateOrCreate(
                ['old_id' => $this->int($row, 'id')],
                [
                    'litter_id' => $litter?->id,
                    'source_litter_letter' => $this->nullableString($row, 'pomet'),
                    'name' => $this->string($row, 'title') ?: 'Котенок',
                    'slug' => $this->slug($row, 'url', 'kitten-'.$this->int($row, 'id')),
                    'sex' => $this->guessSex($this->string($row, 'title').' '.$this->string($row, 'description')),
                    'color' => $this->guessColor($this->string($row, 'description').' '.$this->string($row, 'full_description')),
                    'born_on' => $litter?->born_on,
                    'status' => $this->kittenStatus((int) ($row['status'] ?? 0)),
                    'price' => ((int) ($row['price'] ?? 0)) > 0 ? (int) $row['price'] : null,
                    'description' => $this->nullableString($row, 'description'),
                    'content' => $this->nullableString($row, 'full_description'),
                    'images' => $this->images($row, 'media/kittens', ['public/images/kittens', 'public/images']),
                    'image_alt' => $this->nullableString($row, 'img_alt'),
                    'image_title' => $this->nullableString($row, 'img_title'),
                    'meta_title' => $this->nullableString($row, 'meta_title'),
                    'meta_description' => $this->nullableString($row, 'meta_desc'),
                    'meta_keywords' => $this->nullableString($row, 'meta_keywords'),
                    'sort_order' => (int) ($row['prior'] ?? 0),
                    'is_visible' => (bool) ($row['visible'] ?? true),
                ]
            );
        }

        $this->info('Imported kittens: '.count($this->sourceData['kittens'] ?? []));
    }

    private function importReviews(): void
    {
        foreach ($this->sourceData['reviews'] ?? [] as $row) {
            Review::updateOrCreate(
                ['old_id' => $this->int($row, 'id')],
                [
                    'author_name' => $this->string($row, 'author') ?: 'Гость',
                    'phone' => $this->nullableString($row, 'telefon'),
                    'body' => $this->string($row, 'review'),
                    'response' => $this->nullableString($row, 'otvet'),
                    'reviewed_at' => $this->date($row['data'] ?? null),
                    'is_visible' => (bool) ($row['visible'] ?? true),
                ]
            );
        }

        $this->info('Imported reviews: '.count($this->sourceData['reviews'] ?? []));
    }

    private function importContentPages(): void
    {
        $imported = 0;

        foreach ($this->sourceData['content'] ?? [] as $row) {
            $slug = $this->contentSlug($row);

            if (! in_array($slug, ['about', 'dostavka', 'video'], true)) {
                continue;
            }

            ContentPage::updateOrCreate(
                ['old_id' => $this->int($row, 'id')],
                [
                    'title' => $this->string($row, 'title') ?: 'Страница',
                    'slug' => $slug,
                    'h1' => null,
                    'content' => $this->nullableString($row, 'tekst'),
                    'meta_title' => $this->nullableString($row, 'title'),
                    'meta_description' => $this->nullableString($row, 'meta_desc'),
                    'meta_keywords' => $this->nullableString($row, 'meta_keywords'),
                    'is_system' => (bool) ($row['notpage'] ?? false),
                    'is_visible' => (bool) ($row['visible'] ?? true),
                ]
            );

            $imported++;
        }

        $this->info("Imported content pages: {$imported}");
    }

    private function importGallery(): void
    {
        $categories = collect($this->sourceData['gallery_cat'] ?? [])->keyBy('id');

        foreach ($this->sourceData['gallery'] ?? [] as $row) {
            $category = $this->upsertGalleryCategory($categories->get($row['cat'] ?? null));

            GalleryImage::updateOrCreate(
                ['old_id' => $this->int($row, 'id')],
                [
                    'gallery_category_id' => $category?->id,
                    'category' => $category?->name,
                    'title' => $this->nullableString($row, 'title'),
                    'alt' => $this->nullableString($row, 'alt'),
                    'image_path' => $this->image(
                        $this->string($row, 'img'),
                        'media/gallery',
                        ['public/images/gallery', 'public/images']
                    ) ?: $this->string($row, 'img'),
                    'sort_order' => (int) ($row['prior'] ?? 0),
                    'is_visible' => true,
                ]
            );
        }

        $this->info('Imported gallery images: '.count($this->sourceData['gallery'] ?? []));
    }

    private function importSlides(): void
    {
        foreach ($this->sourceData['slider'] ?? [] as $row) {
            Slide::updateOrCreate(
                ['old_id' => $this->int($row, 'id')],
                [
                    'title' => $this->nullableString($row, 'title'),
                    'placement' => ((int) ($row['cat'] ?? 0)) === 0 ? 'home' : 'page-'.$this->int($row, 'cat'),
                    'url' => $this->nullableString($row, 'url'),
                    'caption' => $this->nullableString($row, 'caption'),
                    'alt' => $this->nullableString($row, 'alt'),
                    'image' => $this->image(
                        $this->string($row, 'img'),
                        'media/slides',
                        ['public/images/gallery', 'public/images']
                    ) ?: $this->string($row, 'img'),
                    'sort_order' => (int) ($row['prior'] ?? 0),
                    'is_visible' => (bool) ($row['visible'] ?? true),
                ]
            );
        }

        $this->info('Imported slides: '.count($this->sourceData['slider'] ?? []));
    }

    private function importNews(): void
    {
        foreach ($this->sourceData['news'] ?? [] as $row) {
            NewsPost::updateOrCreate(
                ['old_id' => $this->int($row, 'id')],
                [
                    'title' => $this->string($row, 'title') ?: 'Новость',
                    'slug' => 'news-'.$this->int($row, 'id'),
                    'excerpt' => $this->nullableString($row, 'kratk'),
                    'content' => $this->nullableString($row, 'novost'),
                    'image' => $this->image(
                        $this->string($row, 'img'),
                        'media/news',
                        ['public/images/news', 'public/images']
                    ),
                    'published_at' => $this->date($row['data'] ?? null),
                    'is_visible' => true,
                    'sort_order' => (int) ($row['prior'] ?? 0),
                ]
            );
        }

        $this->info('Imported news posts: '.count($this->sourceData['news'] ?? []));
    }

    private function importSettings(): void
    {
        foreach ($this->sourceData['settings'] ?? [] as $row) {
            foreach ([
                'admin_email' => ['Контактный email', 'email'],
                'phone' => ['Телефон', 'text'],
            ] as $key => [$label, $type]) {
                SiteSetting::updateOrCreate(
                    ['key' => $key],
                    [
                        'group' => 'main',
                        'value' => (string) ($row[$key] ?? ''),
                        'type' => $type,
                        'label' => $label,
                    ]
                );
            }
        }

        $this->info('Imported settings groups: '.count($this->sourceData['settings'] ?? []));
    }

    private function upsertGalleryCategory(?array $row, ?string $fallbackName = null): ?GalleryCategory
    {
        $name = $row !== null
            ? $this->nullableString($row, 'title')
            : ($fallbackName !== null ? trim($fallbackName) : null);

        if ($name === null || $name === '') {
            return null;
        }

        $baseSlug = $row !== null
            ? $this->slug($row, 'url', Str::slug($name) ?: 'gallery-category')
            : (Str::slug($name) ?: 'gallery-category');

        if ($row !== null && $this->int($row, 'id') > 0) {
            $parent = $this->galleryCategoryParent($row);

            $existing = GalleryCategory::query()
                ->where('old_id', $this->int($row, 'id'))
                ->first();

            $slug = $existing !== null
                ? GalleryCategory::nextAvailableSlug($baseSlug, $existing->id)
                : GalleryCategory::nextAvailableSlug($baseSlug);

            return GalleryCategory::query()->updateOrCreate(
                ['old_id' => $this->int($row, 'id')],
                [
                    'parent_id' => $parent?->id,
                    'name' => $name,
                    'slug' => $slug,
                    'h1' => $this->nullableString($row, 'h1'),
                    'description' => $this->nullableString($row, 'descr'),
                    'description_position' => ((int) ($row['descr_position'] ?? 0)) === 1 ? 'bottom' : 'top',
                    'image' => $this->image(
                        $this->string($row, 'img'),
                        'media/gallery-categories',
                        ['public/images/gallery', 'public/images']
                    ),
                    'meta_title' => $this->nullableString($row, 'meta_title'),
                    'meta_description' => $this->nullableString($row, 'meta_descr'),
                    'meta_keywords' => $this->nullableString($row, 'meta_keywords'),
                    'sort_order' => (int) ($row['prior'] ?? 0),
                    'is_visible' => ((int) ($row['hide'] ?? 0)) === 0 && ((int) ($row['vis'] ?? 1)) === 1,
                ]
            );
        }

        return GalleryCategory::findOrCreateByName($name);
    }

    private function galleryCategoryParent(array $row): ?GalleryCategory
    {
        $parentOldId = $this->int($row, 'parent_cat');

        if ($parentOldId <= 0 || $parentOldId === $this->int($row, 'id')) {
            return null;
        }

        $parentRow = collect($this->sourceData['gallery_cat'] ?? [])
            ->first(fn (array $category): bool => $this->int($category, 'id') === $parentOldId);

        if (is_array($parentRow)) {
            return $this->upsertGalleryCategory($parentRow);
        }

        return GalleryCategory::query()
            ->where('old_id', $parentOldId)
            ->first();
    }

    private function string(array $row, string $key): string
    {
        return trim((string) ($row[$key] ?? ''));
    }

    private function nullableString(array $row, string $key): ?string
    {
        $value = $this->string($row, $key);

        return $value === '' ? null : $value;
    }

    private function int(array $row, string $key): int
    {
        return (int) ($row[$key] ?? 0);
    }

    private function date(mixed $value): ?string
    {
        $value = trim((string) $value);

        if ($value === '' || $value === '0000-00-00') {
            return null;
        }

        foreach (['d.m.Y', 'Y-m-d'] as $format) {
            try {
                $date = CarbonImmutable::createFromFormat($format, $value);
            } catch (\Throwable) {
                $date = false;
            }

            if ($date !== false) {
                return $date->toDateString();
            }
        }

        return null;
    }

    private function extractDateFromText(string $text): ?string
    {
        if (preg_match('/(\d{2})\.(\d{2})\.(\d{4})/', $text, $matches)) {
            return "{$matches[3]}-{$matches[2]}-{$matches[1]}";
        }

        return null;
    }

    private function slug(array $row, string $key, string $fallback): string
    {
        $value = $this->string($row, $key);

        return $value !== '' ? $value : $fallback;
    }

    private function contentSlug(array $row): string
    {
        $url = $this->string($row, 'url');

        if ($url !== '') {
            return $url;
        }

        return match ($this->int($row, 'id')) {
            1 => 'home',
            10 => 'footer-categories',
            11 => 'footer-left',
            12 => 'footer-center',
            13 => 'home-intro',
            default => 'content-'.$this->int($row, 'id'),
        };
    }

    private function images(array $row, string $targetDirectory, array $sourceDirectories = []): array
    {
        $images = [];

        for ($i = 1; $i <= 20; $i++) {
            $image = trim((string) ($row['img'.$i] ?? ''));

            if ($image !== '') {
                $images[] = $this->image($image, $targetDirectory, $sourceDirectories) ?: $image;
            }
        }

        return $images;
    }

    private function image(string $filename, string $targetDirectory = 'media/misc', array $sourceDirectories = []): ?string
    {
        if ($filename === '') {
            return null;
        }

        $normalized = ltrim(str_replace('\\', '/', $filename), '/');

        if (str_starts_with($normalized, trim($targetDirectory, '/').'/') && Storage::disk('public')->exists($normalized)) {
            return $normalized;
        }

        $sourcePath = $this->findImageSourcePath($normalized, $sourceDirectories);

        if ($sourcePath === null) {
            return $filename;
        }

        $target = trim($targetDirectory, '/').'/'.basename($normalized);
        Storage::disk('public')->put($target, file_get_contents($sourcePath));

        return $target;
    }

    private function findImageSourcePath(string $filename, array $sourceDirectories = []): ?string
    {
        $sourceDir = trim((string) $this->option('image-source'));
        $candidates = [];

        if ($sourceDir !== '') {
            $candidates[] = base_path(trim($sourceDir, '/').'/'.$filename);
        }

        foreach ($sourceDirectories as $directory) {
            $directory = trim($directory, '/');

            if ($directory === '') {
                continue;
            }

            $candidates[] = base_path($directory.'/'.$filename);
            $candidates[] = base_path($directory.'/'.basename($filename));
        }

        foreach ($candidates as $candidate) {
            if (is_file($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    private function extractGeneticTests(string $html): ?string
    {
        if (! Str::contains(Str::lower($html), 'генетичес')) {
            return null;
        }

        return trim(strip_tags($html)) ?: null;
    }

    private function guessSex(string $text): string
    {
        $text = Str::lower($text);

        if (Str::contains($text, ['девочка', 'кошечка'])) {
            return 'female';
        }

        if (Str::contains($text, ['мальчик', 'котик', 'котенок мальчик'])) {
            return 'male';
        }

        return 'unknown';
    }

    private function guessColor(string $text): ?string
    {
        $text = Str::lower(strip_tags($text));

        foreach (['шоколадного', 'лилового', 'соболиного'] as $color) {
            if (Str::contains($text, $color)) {
                return str_replace('ого', 'ый', $color);
            }
        }

        return null;
    }

    private function kittenStatus(int $status): string
    {
        return match ($status) {
            1 => 'sold',
            2 => 'reserved',
            default => 'available',
        };
    }
}
