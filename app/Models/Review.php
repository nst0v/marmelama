<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'old_id',
    'author_name',
    'phone',
    'email',
    'body',
    'response',
    'image',
    'reviewed_at',
    'is_visible',
    'sort_order',
])]
class Review extends Model
{
    protected function casts(): array
    {
        return [
            'reviewed_at' => 'date',
            'is_visible' => 'boolean',
        ];
    }
}
