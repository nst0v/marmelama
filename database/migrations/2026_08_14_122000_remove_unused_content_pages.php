<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const SLUGS = [
        'home',
        'contacts',
        'footer-categories',
        'footer-left',
        'footer-center',
        'home-intro',
    ];

    public function up(): void
    {
        if (Schema::hasTable('content_pages')) {
            DB::table('content_pages')->whereIn('slug', self::SLUGS)->delete();
        }
    }

    public function down(): void
    {
        // Removed legacy content comes from the source dump. A database backup
        // is required to restore its exact text on an existing installation.
    }
};
