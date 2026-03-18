<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_webhooks', function (Blueprint $table): void {
            $table->id();
            $table->string('provider', 50);
            $table->string('topic', 100)->nullable();
            $table->string('external_id', 191)->nullable();
            $table->string('status', 50)->index();
            $table->unsignedInteger('attempt_count')->default(0);
            $table->json('payload');
            $table->json('headers')->nullable();
            $table->text('last_error')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index(['provider', 'topic', 'external_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_webhooks');
    }
};
