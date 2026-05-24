<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'old_id',
    'title',
    'slug',
    'letter',
    'born_on',
    'father_id',
    'mother_id',
    'father_name',
    'father_description',
    'father_image',
    'mother_name',
    'mother_description',
    'mother_image',
    'status',
    'description',
    'content',
    'images',
    'meta_title',
    'meta_description',
    'meta_keywords',
    'sort_order',
    'is_visible',
])]
class Litter extends Model
{
    public function getCoverImageAttribute(): ?string
    {
        return is_array($this->images) ? ($this->images[0] ?? null) : null;
    }

    protected function casts(): array
    {
        return [
            'born_on' => 'date',
            'images' => 'array',
            'is_visible' => 'boolean',
        ];
    }

    public function father(): BelongsTo
    {
        return $this->belongsTo(BreedingCat::class, 'father_id');
    }

    public function mother(): BelongsTo
    {
        return $this->belongsTo(BreedingCat::class, 'mother_id');
    }

    public function kittens(): HasMany
    {
        return $this->hasMany(Kitten::class);
    }
}
