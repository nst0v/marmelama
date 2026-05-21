<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'old_id',
    'title',
    'slug',
    'excerpt',
    'content',
    'image',
    'published_at',
    'is_visible',
    'sort_order',
])]
class NewsPost extends Model
{
    protected function casts(): array
    {
        return [
            'published_at' => 'date',
            'is_visible' => 'boolean',
        ];
    }
}
