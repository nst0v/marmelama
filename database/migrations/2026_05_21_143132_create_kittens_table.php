<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kittens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('old_id')->nullable()->index();
            $table->foreignId('litter_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('sex', ['male', 'female', 'unknown'])->default('unknown')->index();
            $table->string('color')->nullable();
            $table->date('born_on')->nullable();
            $table->enum('status', ['available', 'reserved', 'sold'])->default('available')->index();
            $table->decimal('price', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->json('images')->nullable();
            $table->string('image_alt')->nullable();
            $table->string('image_title')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kittens');
    }
};
