<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

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
