<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserWalletResource;
use App\Http\Resources\WalletTransactionResource;
use App\Services\Wallet\WalletService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function show(Request $request, WalletService $walletService): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $wallet = $walletService->getOrCreateWalletForUser($user);

        return (new UserWalletResource($wallet))->response();
    }

    public function transactions(Request $request, WalletService $walletService): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $wallet = $walletService->getOrCreateWalletForUser($user);

        $transactions = $wallet->transactions()
            ->orderByDesc('created_at')
            ->limit(200)
            ->get();

        return WalletTransactionResource::collection($transactions)->response();
    }
}
