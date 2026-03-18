<?php

use App\Http\Controllers\Api\CreditConfigController;
use App\Http\Controllers\Api\CreditPackController;
use App\Http\Controllers\Api\CreditPurchaseController;
use App\Http\Controllers\Api\MercadoPagoWebhookController;
use App\Http\Controllers\Api\WalletController;
use Illuminate\Support\Facades\Route;

Route::apiResource('credit-packs', CreditPackController::class)->only(['index', 'show']);
Route::apiResource('credit-configs', CreditConfigController::class);

Route::middleware('auth')->group(function (): void {
	Route::post('credit-purchases', [CreditPurchaseController::class, 'store']);
	Route::get('credit-purchases/{creditPurchaseOrder}', [CreditPurchaseController::class, 'show']);
	Route::get('my/credit-purchases', [CreditPurchaseController::class, 'indexMy']);

	Route::get('my/wallet', [WalletController::class, 'show']);
	Route::get('my/wallet/transactions', [WalletController::class, 'transactions']);
});

Route::post('webhooks/mercadopago', [MercadoPagoWebhookController::class, 'handle']);
