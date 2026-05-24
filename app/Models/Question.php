<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'old_id',
    'author_name',
    'phone',
    'title',
    'body',
    'response',
    'asked_at',
])]
class Question extends Model
{
    protected function casts(): array
    {
        return [
            'asked_at' => 'date',
        ];
    }
}
