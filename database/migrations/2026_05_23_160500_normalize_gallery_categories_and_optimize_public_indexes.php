<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gallery_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('old_id')->nullable()->unique();
            $table->string('name')->index();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();

            $table->index(['is_visible', 'sort_order', 'id'], 'gallery_categories_public_idx');
        });

        Schema::table('gallery_images', function (Blueprint $table) {
            $table->foreignId('gallery_category_id')->nullable()->constrained('gallery_categories')->nullOnDelete();
        });

        $this->backfillGalleryCategories();

        Schema::table('breeding_cats', function (Blueprint $table) {
            $table->index(['is_visible', 'is_active', 'sort_order', 'id'], 'breeding_cats_public_idx');
            $table->index(['is_visible', 'sex', 'is_active', 'sort_order', 'id'], 'breeding_cats_sex_public_idx');
        });

        Schema::table('litters', function (Blueprint $table) {
            $table->index(['is_visible', 'born_on', 'sort_order', 'id'], 'litters_public_idx');
            $table->index(['father_id', 'is_visible', 'born_on', 'id'], 'litters_father_public_idx');
            $table->index(['mother_id', 'is_visible', 'born_on', 'id'], 'litters_mother_public_idx');
        });

        Schema::table('kittens', function (Blueprint $table) {
            $table->index(['is_visible', 'status', 'sort_order', 'id'], 'kittens_status_public_idx');
            $table->index(['is_visible', 'sex', 'sort_order', 'id'], 'kittens_sex_public_idx');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->index(['is_visible', 'reviewed_at', 'id'], 'reviews_public_idx');
        });

        Schema::table('news_posts', function (Blueprint $table) {
            $table->index(['is_visible', 'published_at', 'id'], 'news_posts_public_idx');
        });

        Schema::table('gallery_images', function (Blueprint $table) {
            $table->index(['is_visible', 'category', 'sort_order', 'id'], 'gallery_images_public_category_name_idx');
            $table->index(['is_visible', 'gallery_category_id', 'sort_order', 'id'], 'gallery_images_public_category_idx');
        });
    }

    public function down(): void
    {
        Schema::table('gallery_images', function (Blueprint $table) {
            $table->dropIndex('gallery_images_public_category_idx');
            $table->dropIndex('gallery_images_public_category_name_idx');
        });

        Schema::table('news_posts', function (Blueprint $table) {
            $table->dropIndex('news_posts_public_idx');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex('reviews_public_idx');
        });

        Schema::table('kittens', function (Blueprint $table) {
            $table->dropIndex('kittens_status_public_idx');
            $table->dropIndex('kittens_sex_public_idx');
        });

        Schema::table('litters', function (Blueprint $table) {
            $table->dropIndex('litters_public_idx');
            $table->dropIndex('litters_father_public_idx');
            $table->dropIndex('litters_mother_public_idx');
        });

        Schema::table('breeding_cats', function (Blueprint $table) {
            $table->dropIndex('breeding_cats_public_idx');
            $table->dropIndex('breeding_cats_sex_public_idx');
        });

        Schema::table('gallery_images', function (Blueprint $table) {
            $table->dropConstrainedForeignId('gallery_category_id');
        });

        Schema::dropIfExists('gallery_categories');
    }

    private function backfillGalleryCategories(): void
    {
        $categoryNames = DB::table('gallery_images')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        foreach ($categoryNames as $name) {
            $this->ensureGalleryCategory((string) $name);
        }

        $categoriesByName = DB::table('gallery_categories')->pluck('id', 'name');

        DB::table('gallery_images')
            ->whereNull('gallery_category_id')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->orderBy('id')
            ->chunkById(100, function ($images) use ($categoriesByName): void {
                foreach ($images as $image) {
                    $categoryId = $categoriesByName[$image->category] ?? null;

                    if ($categoryId === null) {
                        continue;
                    }

                    DB::table('gallery_images')
                        ->where('id', $image->id)
                        ->update(['gallery_category_id' => $categoryId]);
                }
            });
    }

    private function ensureGalleryCategory(string $name): int
    {
        $name = trim($name);

        $existingId = DB::table('gallery_categories')
            ->where('name', $name)
            ->value('id');

        if ($existingId !== null) {
            return (int) $existingId;
        }

        $baseSlug = Str::slug($name);

        if ($baseSlug === '') {
            $baseSlug = 'gallery-category';
        }

        $slug = $this->nextAvailableGalleryCategorySlug($baseSlug);
        $now = now();

        return (int) DB::table('gallery_categories')->insertGetId([
            'name' => $name,
            'slug' => $slug,
            'sort_order' => 0,
            'is_visible' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    private function nextAvailableGalleryCategorySlug(string $baseSlug): string
    {
        $slug = $baseSlug;
        $suffix = 2;

        while (DB::table('gallery_categories')->where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
};
