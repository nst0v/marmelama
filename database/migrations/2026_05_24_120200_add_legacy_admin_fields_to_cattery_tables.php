<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('breeding_cats', function (Blueprint $table) {
            $table->string('image_alt')->nullable()->after('images');
            $table->string('image_title')->nullable()->after('image_alt');
        });

        Schema::table('litters', function (Blueprint $table) {
            $table->text('father_description')->nullable()->after('father_name');
            $table->string('father_image')->nullable()->after('father_description');
            $table->text('mother_description')->nullable()->after('mother_name');
            $table->string('mother_image')->nullable()->after('mother_description');
        });
    }

    public function down(): void
    {
        Schema::table('litters', function (Blueprint $table) {
            $table->dropColumn([
                'father_description',
                'father_image',
                'mother_description',
                'mother_image',
            ]);
        });

        Schema::table('breeding_cats', function (Blueprint $table) {
            $table->dropColumn(['image_alt', 'image_title']);
        });
    }
};
