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
        Schema::create('breeding_cats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('old_id')->nullable()->index();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('sex', ['male', 'female']);
            $table->boolean('is_active')->default(true);
            $table->string('title')->nullable();
            $table->string('color')->nullable();
            $table->date('birthday')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->text('genetic_tests')->nullable();
            $table->string('breeder')->nullable();
            $table->string('owner')->nullable();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->json('images')->nullable();
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
        Schema::dropIfExists('breeding_cats');
    }
};
