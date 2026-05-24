<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'old_id',
    'article_id',
    'parent_id',
    'author_name',
    'body',
    'commented_at',
    'is_visible',
])]
class ArticleComment extends Model
{
    protected function casts(): array
    {
        return [
            'commented_at' => 'datetime',
            'is_visible' => 'boolean',
        ];
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }
}
