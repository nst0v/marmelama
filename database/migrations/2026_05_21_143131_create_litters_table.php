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
        Schema::create('litters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('old_id')->nullable()->index();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('letter')->nullable()->index();
            $table->date('born_on')->nullable();
            $table->foreignId('father_id')->nullable()->constrained('breeding_cats')->nullOnDelete();
            $table->foreignId('mother_id')->nullable()->constrained('breeding_cats')->nullOnDelete();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->enum('status', ['planned', 'available', 'reserved', 'archive'])->default('available')->index();
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
        Schema::dropIfExists('litters');
    }
};
