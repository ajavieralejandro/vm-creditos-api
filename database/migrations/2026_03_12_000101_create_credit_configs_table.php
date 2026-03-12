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
        Schema::create('credit_configs', function (Blueprint $table): void {
            $table->id();
            $table->integer('expiration_months');
            $table->integer('cancel_grace_minutes');
            $table->string('penalty_mode', 20);
            $table->integer('penalty_value');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_configs');
    }
};
