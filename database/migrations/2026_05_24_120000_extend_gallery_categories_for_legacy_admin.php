<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gallery_categories', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->after('old_id')->constrained('gallery_categories')->nullOnDelete();
            $table->string('h1')->nullable()->after('slug');
            $table->enum('description_position', ['top', 'bottom'])->default('top')->after('description');
            $table->string('image')->nullable()->after('description_position');
        });
    }

    public function down(): void
    {
        Schema::table('gallery_categories', function (Blueprint $table) {
            $table->dropConstrainedForeignId('parent_id');
            $table->dropColumn(['h1', 'description_position', 'image']);
        });
    }
};
