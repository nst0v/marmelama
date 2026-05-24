<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable([
    'old_id',
    'parent_id',
    'name',
    'slug',
    'h1',
    'description',
    'description_position',
    'image',
    'meta_title',
    'meta_description',
    'meta_keywords',
    'sort_order',
    'is_visible',
])]
class GalleryCategory extends Model
{
    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
        ];
    }

    public function images(): HasMany
    {
        return $this->hasMany(GalleryImage::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public static function findOrCreateByName(string $name): self
    {
        $name = trim($name);

        if ($name === '') {
            throw new \InvalidArgumentException('Gallery category name cannot be empty.');
        }

        $existing = static::query()->where('name', $name)->first();

        if ($existing !== null) {
            return $existing;
        }

        $baseSlug = Str::slug($name);

        if ($baseSlug === '') {
            $baseSlug = 'gallery-category';
        }

        return static::query()->create([
            'name' => $name,
            'slug' => static::nextAvailableSlug($baseSlug),
        ]);
    }

    public static function nextAvailableSlug(string $baseSlug, ?int $ignoreId = null): string
    {
        $slug = $baseSlug;
        $suffix = 2;

        while (static::query()
            ->when($ignoreId !== null, fn ($query) => $query->whereKeyNot($ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = "{$baseSlug}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
