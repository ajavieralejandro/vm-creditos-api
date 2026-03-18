<?php

namespace App\Services\Wallet;

use App\Enums\WalletTransactionType;
use App\Models\CreditPurchaseOrder;
use App\Models\User;
use App\Models\UserWallet;
use App\Models\WalletTransaction;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\DB;

class WalletService
{
    public function __construct(private readonly DatabaseManager $db)
    {
    }

    public function getOrCreateWalletForUser(User $user): UserWallet
    {
        return $this->db->transaction(function () use ($user): UserWallet {
            $wallet = UserWallet::where('user_id', $user->id)->lockForUpdate()->first();

            if (! $wallet) {
                $wallet = UserWallet::create([
                    'user_id' => $user->id,
                    'external_user_id' => null,
                    'balance' => 0,
                ]);
            }

            return $wallet;
        });
    }

    public function creditFromOrder(CreditPurchaseOrder $order): UserWallet
    {
        return $this->db->transaction(function () use ($order): UserWallet {
            $wallet = UserWallet::where('id', $order->wallet_id)->lockForUpdate()->firstOrFail();

            $existing = WalletTransaction::where('wallet_id', $wallet->id)
                ->where('type', WalletTransactionType::Credit)
                ->where('source_type', CreditPurchaseOrder::class)
                ->where('source_id', $order->id)
                ->first();

            if ($existing) {
                return $wallet;
            }

            $amount = $order->credits_amount;
            $balanceBefore = $wallet->balance;
            $balanceAfter = $balanceBefore + $amount;

            WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'type' => WalletTransactionType::Credit,
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'description' => 'Credit pack purchase #'.$order->id,
                'source_type' => CreditPurchaseOrder::class,
                'source_id' => $order->id,
                'metadata' => [
                    'external_reference' => $order->external_reference,
                ],
            ]);

            $wallet->balance = $balanceAfter;
            $wallet->save();

            return $wallet;
        });
    }
}
