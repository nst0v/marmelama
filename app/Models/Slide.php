<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'old_id',
    'title',
    'placement',
    'url',
    'caption',
    'alt',
    'image',
    'sort_order',
    'is_visible',
])]
class Slide extends Model
{
    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::updated(function (Slide $slide): void {
            if (! $slide->wasChanged('image')) {
                return;
            }

            self::deleteImageIfUnused($slide->getOriginal('image'));
        });

        static::deleted(function (Slide $slide): void {
            self::deleteImageIfUnused($slide->image);
        });
    }

    private static function deleteImageIfUnused(?string $path): void
    {
        $path = trim((string) $path);

        if ($path === '') {
            return;
        }

        $normalized = ltrim(str_replace('\\', '/', $path), '/');

        if (str_starts_with($normalized, 'storage/')) {
            $normalized = substr($normalized, strlen('storage/'));
        }

        if (str_starts_with($normalized, 'app/public/')) {
            $normalized = substr($normalized, strlen('app/public/'));
        }

        $isUsedByAnotherSlide = self::query()
            ->whereIn('image', array_unique([
                $path,
                $normalized,
                'storage/'.$normalized,
                'app/public/'.$normalized,
            ]))
            ->exists();

        if (! $isUsedByAnotherSlide) {
            Storage::disk('public')->delete($normalized);
        }
    }
}
