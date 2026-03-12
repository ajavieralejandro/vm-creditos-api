<?php

use App\Http\Controllers\Api\CreditConfigController;
use App\Http\Controllers\Api\CreditPackController;
use Illuminate\Support\Facades\Route;

Route::apiResource('credit-packs', CreditPackController::class);
Route::apiResource('credit-configs', CreditConfigController::class);
