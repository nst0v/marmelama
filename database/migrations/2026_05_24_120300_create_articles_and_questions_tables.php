<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('old_id')->nullable()->unique();
            $table->foreignId('parent_id')->nullable()->constrained('article_categories')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description')->nullable();
            $table->enum('description_position', ['top', 'bottom'])->default('top');
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('seo_title')->nullable();
            $table->string('seo_h1')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('old_id')->nullable()->unique();
            $table->foreignId('article_category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('h1')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->dateTime('published_at')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('image')->nullable();
            $table->boolean('allow_comments')->default(false);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();

            $table->index(['is_visible', 'published_at', 'sort_order', 'id'], 'articles_public_idx');
        });

        Schema::create('article_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('old_id')->nullable()->unique();
            $table->foreignId('article_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('article_comments')->nullOnDelete();
            $table->string('author_name')->nullable();
            $table->text('body');
            $table->dateTime('commented_at')->nullable();
            $table->boolean('is_visible')->default(false);
            $table->timestamps();
        });

        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('old_id')->nullable()->unique();
            $table->string('author_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('title');
            $table->longText('body')->nullable();
            $table->longText('response')->nullable();
            $table->date('asked_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
        Schema::dropIfExists('article_comments');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('article_categories');
    }
};
