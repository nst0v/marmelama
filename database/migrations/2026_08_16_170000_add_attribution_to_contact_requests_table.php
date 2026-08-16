<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_requests', function (Blueprint $table) {
            $table->timestamp('privacy_consented_at')->nullable()->after('message');
            $table->string('utm_source', 100)->nullable()->index()->after('privacy_consented_at');
            $table->string('utm_medium', 100)->nullable()->after('utm_source');
            $table->string('utm_campaign')->nullable()->after('utm_medium');
            $table->string('utm_content')->nullable()->after('utm_campaign');
            $table->string('utm_term')->nullable()->after('utm_content');
            $table->string('yclid')->nullable()->after('utm_term');
            $table->string('landing_url', 2048)->nullable()->after('yclid');
            $table->string('referrer_url', 2048)->nullable()->after('landing_url');
        });
    }

    public function down(): void
    {
        Schema::table('contact_requests', function (Blueprint $table) {
            $table->dropIndex(['utm_source']);
            $table->dropColumn([
                'privacy_consented_at',
                'utm_source',
                'utm_medium',
                'utm_campaign',
                'utm_content',
                'utm_term',
                'yclid',
                'landing_url',
                'referrer_url',
            ]);
        });
    }
};
