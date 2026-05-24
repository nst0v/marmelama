<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'old_id',
    'article_category_id',
    'slug',
    'title',
    'h1',
    'meta_description',
    'meta_keywords',
    'excerpt',
    'content',
    'published_at',
    'sort_order',
    'image',
    'allow_comments',
    'is_visible',
])]
class Article extends Model
{
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'allow_comments' => 'boolean',
            'is_visible' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ArticleCategory::class, 'article_category_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(ArticleComment::class);
    }
}
