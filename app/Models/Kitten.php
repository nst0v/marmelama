<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[Fillable([
    'old_id',
    'litter_id',
    'source_litter_letter',
    'name',
    'slug',
    'sex',
    'color',
    'born_on',
    'status',
    'price',
    'description',
    'content',
    'images',
    'image_alt',
    'image_title',
    'meta_title',
    'meta_description',
    'meta_keywords',
    'sort_order',
    'is_visible',
])]
class Kitten extends Model
{
    protected function displayName(): Attribute
    {
        return Attribute::get(function (): string {
            $name = trim((string) preg_replace('/\s+/u', ' ', $this->name));
            $withoutSexPrefix = trim((string) preg_replace('/^(?:мальчик|девочка)\s+/iu', '', $name));
            $displayName = $withoutSexPrefix !== '' ? $withoutSexPrefix : $name;

            return mb_strtolower($displayName) === 'котенок'
                ? 'Котёнок'
                : Str::ucfirst($displayName);
        });
    }

    public function getCoverImageAttribute(): ?string
    {
        return is_array($this->images) ? ($this->images[0] ?? null) : null;
    }

    protected function casts(): array
    {
        return [
            'born_on' => 'date',
            'price' => 'decimal:2',
            'images' => 'array',
            'is_visible' => 'boolean',
        ];
    }

    public function litter(): BelongsTo
    {
        return $this->belongsTo(Litter::class);
    }
}
