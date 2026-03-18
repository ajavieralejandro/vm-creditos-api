<?php

namespace App\Services\CreditPurchases;

use App\Enums\CreditPurchaseOrderStatus;
use App\Enums\PaymentProvider;
use App\Models\CreditPack;
use App\Models\CreditPurchaseOrder;
use App\Models\User;
use App\Services\Payments\MercadoPagoClient;
use App\Services\Wallet\WalletService;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Str;

class CreditPurchaseService
{
    public function __construct(
        private readonly DatabaseManager $db,
        private readonly WalletService $walletService,
        private readonly MercadoPagoClient $mercadoPagoClient,
    ) {
    }

    public function createOrderAndPreference(User $user, CreditPack $pack): array
    {
        $wallet = $this->walletService->getOrCreateWalletForUser($user);

        $order = $this->db->transaction(function () use ($user, $wallet, $pack): CreditPurchaseOrder {
            $externalReference = 'CPO-'.$user->id.'-'.Str::uuid()->toString();

            return CreditPurchaseOrder::create([
                'user_id' => $user->id,
                'wallet_id' => $wallet->id,
                'credit_pack_id' => $pack->id,
                'status' => CreditPurchaseOrderStatus::Pending,
                'external_reference' => $externalReference,
                'credits_amount' => $pack->credits_amount,
                'price_amount' => $pack->price_amount,
                'currency' => $pack->currency,
                'pack_snapshot' => [
                    'code' => $pack->code,
                    'name' => $pack->name,
                    'description' => $pack->description,
                    'credits_amount' => $pack->credits_amount,
                    'price_amount' => $pack->price_amount,
                    'currency' => $pack->currency,
                    'metadata' => $pack->metadata,
                ],
                'payment_provider' => PaymentProvider::MercadoPago,
            ]);
        });

        $preferencePayload = [
            'external_reference' => $order->external_reference,
            'items' => [
                [
                    'id' => (string) $pack->id,
                    'title' => $pack->name,
                    'description' => $pack->description,
                    'quantity' => 1,
                    'currency_id' => $pack->currency,
                    'unit_price' => $pack->price_amount / 100,
                ],
            ],
            'payer' => [
                'id' => (string) $user->id,
                'email' => $user->email,
                'name' => $user->name,
            ],
            'back_urls' => [
                'success' => config('mercadopago.success_url'),
                'failure' => config('mercadopago.failure_url'),
                'pending' => config('mercadopago.pending_url'),
            ],
            'auto_return' => 'approved',
        ];

        $preference = $this->mercadoPagoClient->createPreference($preferencePayload);

        $order->mp_preference_id = $preference['id'] ?? null;
        $order->mp_init_point = $preference['init_point'] ?? ($preference['sandbox_init_point'] ?? null);
        $order->status = CreditPurchaseOrderStatus::PreferenceCreated;
        $order->save();

        return [$order, $preference];
    }
}
