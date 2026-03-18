<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('credit_packs', function (Blueprint $table): void {
            $table->unsignedBigInteger('credits_amount')->default(0)->after('name');
            $table->unsignedBigInteger('price_amount')->default(0)->after('credits_amount');
            $table->string('currency', 10)->default('ARS')->after('price_amount');
            $table->json('metadata')->nullable()->after('sort_order');
        });

        Schema::table('credit_packs', function (Blueprint $table): void {
            if (Schema::hasColumn('credit_packs', 'credits')) {
                $table->dropColumn('credits');
            }

            if (Schema::hasColumn('credit_packs', 'price_ars')) {
                $table->dropColumn('price_ars');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('credit_packs', function (Blueprint $table): void {
            $table->dropColumn(['credits_amount', 'price_amount', 'currency', 'metadata']);

            $table->integer('credits')->default(0);
            $table->integer('price_ars')->default(0);
        });
    }
};
