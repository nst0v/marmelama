<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('site_settings')) {
            return;
        }

        DB::table('site_settings')->insertOrIgnore([
            'group' => 'social',
            'key' => 'max_url',
            'value' => '',
            'type' => 'url',
            'label' => 'Ссылка MAX',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        if (Schema::hasTable('site_settings')) {
            DB::table('site_settings')->where('key', 'max_url')->delete();
        }
    }
};
