<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'old_id',
    'name',
    'slug',
    'sex',
    'is_active',
    'title',
    'color',
    'birthday',
    'father_name',
    'mother_name',
    'genetic_tests',
    'breeder',
    'owner',
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
class BreedingCat extends Model
{
    public function getCoverImageAttribute(): ?string
    {
        return is_array($this->images) ? ($this->images[0] ?? null) : null;
    }

    protected function casts(): array
    {
        return [
            'birthday' => 'date',
            'images' => 'array',
            'is_active' => 'boolean',
            'is_visible' => 'boolean',
        ];
    }

    public function fatherLitters(): HasMany
    {
        return $this->hasMany(Litter::class, 'father_id');
    }

    public function motherLitters(): HasMany
    {
        return $this->hasMany(Litter::class, 'mother_id');
    }
}
