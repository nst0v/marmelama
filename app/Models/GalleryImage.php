<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'old_id',
    'gallery_category_id',
    'category',
    'title',
    'alt',
    'image_path',
    'sort_order',
    'is_visible',
])]
class GalleryImage extends Model
{
    protected static function booted(): void
    {
        static::saving(function (self $image): void {
            $categoryName = trim((string) $image->category);

            if (
                $categoryName !== ''
                && (
                    $image->gallery_category_id === null
                    || ($image->isDirty('category') && ! $image->isDirty('gallery_category_id'))
                )
            ) {
                $category = GalleryCategory::findOrCreateByName($categoryName);

                $image->gallery_category_id = $category->id;
                $image->category = $category->name;

                return;
            }

            if ($image->gallery_category_id !== null) {
                $category = $image->galleryCategory()->first();

                if ($category !== null) {
                    $image->category = $category->name;
                }
            }
        });
    }

    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
        ];
    }

    public function galleryCategory(): BelongsTo
    {
        return $this->belongsTo(GalleryCategory::class);
    }
}
