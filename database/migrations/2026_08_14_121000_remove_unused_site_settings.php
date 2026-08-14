<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const SETTINGS = [
        'address' => ['Адрес', 'text'],
        'message' => ['Сообщение', 'textarea'],
        'zvonok' => ['Заказ звонка включён', 'boolean'],
        'nagrada' => ['Показывать награды', 'boolean'],
    ];

    public function up(): void
    {
        if (Schema::hasTable('site_settings')) {
            DB::table('site_settings')->whereIn('key', array_keys(self::SETTINGS))->delete();
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('site_settings')) {
            return;
        }

        $now = now();

        foreach (self::SETTINGS as $key => [$label, $type]) {
            DB::table('site_settings')->insertOrIgnore([
                'group' => 'main',
                'key' => $key,
                'value' => '',
                'type' => $type,
                'label' => $label,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
};
