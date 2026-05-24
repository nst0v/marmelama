<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

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
}
