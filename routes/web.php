<?php

use App\Http\Controllers\Admin\CreditConfigController as AdminCreditConfigController;
use App\Http\Controllers\Admin\CreditPackController as AdminCreditPackController;
use App\Http\Controllers\Admin\CreditPurchaseController as AdminCreditPurchaseController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\WalletController as AdminWalletController;
use App\Http\Controllers\Admin\WebhookController as AdminWebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->as('admin.')->group(function (): void {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('credit-packs', [AdminCreditPackController::class, 'index'])->name('credit-packs.index');
    Route::get('credit-packs/create', [AdminCreditPackController::class, 'create'])->name('credit-packs.create');
    Route::post('credit-packs', [AdminCreditPackController::class, 'store'])->name('credit-packs.store');
    Route::get('credit-packs/{credit_pack}/edit', [AdminCreditPackController::class, 'edit'])->name('credit-packs.edit');
    Route::put('credit-packs/{credit_pack}', [AdminCreditPackController::class, 'update'])->name('credit-packs.update');
    Route::delete('credit-packs/{credit_pack}', [AdminCreditPackController::class, 'destroy'])->name('credit-packs.destroy');
    Route::patch('credit-packs/{credit_pack}/toggle', [AdminCreditPackController::class, 'toggle'])->name('credit-packs.toggle');

    Route::get('credit-purchases', [AdminCreditPurchaseController::class, 'index'])->name('credit-purchases.index');
    Route::get('credit-purchases/{credit_purchase_order}', [AdminCreditPurchaseController::class, 'show'])->name('credit-purchases.show');

    Route::get('wallets', [AdminWalletController::class, 'index'])->name('wallets.index');
    Route::get('wallets/{wallet}', [AdminWalletController::class, 'show'])->name('wallets.show');

    Route::get('webhooks', [AdminWebhookController::class, 'index'])->name('webhooks.index');
    Route::get('webhooks/{payment_webhook}', [AdminWebhookController::class, 'show'])->name('webhooks.show');

    // Credit configuration (global)
    Route::get('credit-config', [AdminCreditConfigController::class, 'index'])->name('credit-config.index');
    Route::get('credit-config/create', [AdminCreditConfigController::class, 'create'])->name('credit-config.create');
    Route::post('credit-config', [AdminCreditConfigController::class, 'store'])->name('credit-config.store');
    Route::get('credit-config/{credit_config}/edit', [AdminCreditConfigController::class, 'edit'])->name('credit-config.edit');
    Route::put('credit-config/{credit_config}', [AdminCreditConfigController::class, 'update'])->name('credit-config.update');
});
