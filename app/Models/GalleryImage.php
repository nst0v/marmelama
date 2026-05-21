<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'old_id',
    'category',
    'title',
    'alt',
    'image_path',
    'sort_order',
    'is_visible',
])]
class GalleryImage extends Model
{
    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
        ];
    }
}
