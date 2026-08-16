<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kitten_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name', 80);
            $table->string('phone', 40);
            $table->string('email', 160)->nullable();
            $table->text('message');
            $table->string('status', 32)->default('new')->index();
            $table->text('internal_notes')->nullable();
            $table->string('mail_status', 32)->default('pending')->index();
            $table->timestamp('mail_sent_at')->nullable();
            $table->text('mail_error')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_requests');
    }
};
