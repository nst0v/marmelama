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

        $now = now();

        foreach ([
            ['group' => 'contacts', 'key' => 'phone', 'value' => '+7 (913) 645-31-18', 'type' => 'text', 'label' => 'Телефон'],
            ['group' => 'contacts', 'key' => 'admin_email', 'value' => 'balovatskaya@mail.ru', 'type' => 'email', 'label' => 'Электронная почта'],
            ['group' => 'social', 'key' => 'max_url', 'value' => '', 'type' => 'url', 'label' => 'Ссылка MAX'],
        ] as $setting) {
            DB::table('site_settings')->insertOrIgnore([
                ...$setting,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        // These keys may contain owner changes by the time a rollback occurs,
        // so rolling back must not delete them.
    }
};
