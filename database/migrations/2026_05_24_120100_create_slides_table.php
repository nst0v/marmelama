<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('slides', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('old_id')->nullable()->unique();
            $table->string('title')->nullable();
            $table->string('placement')->default('home')->index();
            $table->string('url')->nullable();
            $table->text('caption')->nullable();
            $table->string('alt')->nullable();
            $table->string('image');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();

            $table->index(['is_visible', 'placement', 'sort_order', 'id'], 'slides_public_idx');
        });

        $now = now();

        DB::table('gallery_images')
            ->where('category', 'slider')
            ->orderByDesc('sort_order')
            ->orderBy('id')
            ->get()
            ->each(function (object $image) use ($now): void {
                $oldId = $image->old_id !== null ? (int) $image->old_id : null;

                if ($oldId !== null && $oldId >= 100000) {
                    $oldId -= 100000;
                }

                DB::table('slides')->updateOrInsert(
                    ['old_id' => $oldId],
                    [
                        'title' => $image->title,
                        'placement' => 'home',
                        'url' => null,
                        'caption' => null,
                        'alt' => $image->alt,
                        'image' => $image->image_path,
                        'sort_order' => (int) $image->sort_order,
                        'is_visible' => (bool) $image->is_visible,
                        'created_at' => $image->created_at ?? $now,
                        'updated_at' => $now,
                    ]
                );
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('slides');
    }
};
