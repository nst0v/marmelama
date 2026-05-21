<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'old_id',
    'title',
    'slug',
    'h1',
    'content',
    'meta_title',
    'meta_description',
    'meta_keywords',
    'is_system',
    'is_visible',
    'sort_order',
])]
class ContentPage extends Model
{
    protected function casts(): array
    {
        return [
            'is_system' => 'boolean',
            'is_visible' => 'boolean',
        ];
    }
}
