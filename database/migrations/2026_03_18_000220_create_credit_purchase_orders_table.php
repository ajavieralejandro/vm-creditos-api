<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('credit_purchase_orders', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('wallet_id')->constrained('user_wallets')->cascadeOnDelete();
            $table->foreignId('credit_pack_id')->constrained('credit_packs');
            $table->string('status', 50)->index();
            $table->string('external_reference', 191)->unique();

            $table->unsignedBigInteger('credits_amount');
            $table->unsignedBigInteger('price_amount');
            $table->string('currency', 10);

            $table->json('pack_snapshot');

            $table->string('payment_provider', 50)->default('mercadopago');
            $table->string('mp_preference_id', 191)->nullable();
            $table->string('mp_init_point', 500)->nullable();
            $table->string('mp_payment_id', 191)->nullable()->index();
            $table->string('mp_merchant_order_id', 191)->nullable()->index();
            $table->string('payment_status', 50)->nullable();
            $table->string('payment_status_detail', 100)->nullable();

            $table->json('payment_payload')->nullable();

            $table->timestamp('payment_created_at')->nullable();
            $table->timestamp('payment_updated_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('accredited_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('refunded_at')->nullable();

            $table->text('last_error')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credit_purchase_orders');
    }
};
