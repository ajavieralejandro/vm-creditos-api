<?php

namespace Tests\Feature;

use App\Enums\CreditPurchaseOrderStatus;
use App\Enums\WalletTransactionType;
use App\Models\CreditPack;
use App\Models\CreditPurchaseOrder;
use App\Models\User;
use App\Models\UserWallet;
use App\Models\WalletTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CreditPurchaseFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_active_packs(): void
    {
        CreditPack::factory()->create([
            'is_active' => true,
            'credits_amount' => 10,
            'price_amount' => 1000_00,
            'currency' => 'ARS',
        ]);

        CreditPack::factory()->create([
            'is_active' => false,
            'credits_amount' => 99,
            'price_amount' => 999_00,
            'currency' => 'ARS',
        ]);

        $response = $this->getJson('/api/credit-packs');

        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
    }

    public function test_create_purchase_order(): void
    {
        Http::fake([
            '*/checkout/preferences' => Http::response([
                'id' => 'pref_123',
                'init_point' => 'https://mp/init/123',
            ], 201),
        ]);

        $user = User::factory()->create();

        $pack = CreditPack::factory()->create([
            'is_active' => true,
            'credits_amount' => 10,
            'price_amount' => 1000_00,
            'currency' => 'ARS',
        ]);

        $response = $this->actingAs($user)->postJson('/api/credit-purchases', [
            'pack_id' => $pack->id,
        ]);

        $response->assertCreated();
        $response->assertJsonPath('data.credit_pack_id', $pack->id);
        $response->assertJsonPath('mercadopago.preference_id', 'pref_123');
    }

    public function test_cannot_purchase_inactive_pack(): void
    {
        Http::fake();

        $user = User::factory()->create();

        $pack = CreditPack::factory()->create([
            'is_active' => false,
            'credits_amount' => 10,
            'price_amount' => 1000_00,
            'currency' => 'ARS',
        ]);

        $response = $this->actingAs($user)->postJson('/api/credit-purchases', [
            'pack_id' => $pack->id,
        ]);

        $response->assertStatus(422);
    }

    public function test_approved_webhook_accredits_wallet_once_even_if_duplicated(): void
    {
        Http::fake([
            '*/v1/payments/*' => Http::response([
                'id' => 'pay_123',
                'status' => 'approved',
                'status_detail' => 'accredited',
                'external_reference' => 'ext_123',
                'transaction_amount' => 1000.00,
            ], 200),
        ]);

        $user = User::factory()->create();

        $pack = CreditPack::factory()->create([
            'is_active' => true,
            'credits_amount' => 10,
            'price_amount' => 1000_00,
            'currency' => 'ARS',
        ]);

        $wallet = UserWallet::create([
            'user_id' => $user->id,
            'balance' => 0,
        ]);

        $order = CreditPurchaseOrder::create([
            'user_id' => $user->id,
            'wallet_id' => $wallet->id,
            'credit_pack_id' => $pack->id,
            'status' => CreditPurchaseOrderStatus::Pending,
            'external_reference' => 'ext_123',
            'credits_amount' => $pack->credits_amount,
            'price_amount' => $pack->price_amount,
            'currency' => $pack->currency,
            'pack_snapshot' => [],
            'payment_provider' => 'mercadopago',
        ]);

        $payload = [
            'type' => 'payment',
            'data' => [
                'id' => 'pay_123',
            ],
        ];

        $first = $this->postJson('/api/webhooks/mercadopago', $payload);
        $first->assertStatus(200);

        $second = $this->postJson('/api/webhooks/mercadopago', $payload);
        $second->assertStatus(200);

        $wallet->refresh();

        $this->assertSame(10, $wallet->balance);
        $this->assertEquals(1, WalletTransaction::where('wallet_id', $wallet->id)->count());
    }

    public function test_rejected_payment_does_not_accredit(): void
    {
        Http::fake([
            '*/v1/payments/*' => Http::response([
                'id' => 'pay_124',
                'status' => 'rejected',
                'status_detail' => 'cc_rejected',
                'external_reference' => 'ext_124',
                'transaction_amount' => 1000.00,
            ], 200),
        ]);

        $user = User::factory()->create();

        $pack = CreditPack::factory()->create([
            'is_active' => true,
            'credits_amount' => 10,
            'price_amount' => 1000_00,
            'currency' => 'ARS',
        ]);

        $wallet = UserWallet::create([
            'user_id' => $user->id,
            'balance' => 0,
        ]);

        $order = CreditPurchaseOrder::create([
            'user_id' => $user->id,
            'wallet_id' => $wallet->id,
            'credit_pack_id' => $pack->id,
            'status' => CreditPurchaseOrderStatus::Pending,
            'external_reference' => 'ext_124',
            'credits_amount' => $pack->credits_amount,
            'price_amount' => $pack->price_amount,
            'currency' => $pack->currency,
            'pack_snapshot' => [],
            'payment_provider' => 'mercadopago',
        ]);

        $payload = [
            'type' => 'payment',
            'data' => [
                'id' => 'pay_124',
            ],
        ];

        $response = $this->postJson('/api/webhooks/mercadopago', $payload);
        $response->assertStatus(200);

        $wallet->refresh();

        $this->assertSame(0, $wallet->balance);
        $this->assertEquals(0, WalletTransaction::where('wallet_id', $wallet->id)->count());
    }

    public function test_user_can_view_wallet_and_transactions(): void
    {
        $user = User::factory()->create();
        $wallet = UserWallet::create([
            'user_id' => $user->id,
            'balance' => 50,
        ]);

        WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'type' => WalletTransactionType::Credit,
            'amount' => 50,
            'balance_before' => 0,
            'balance_after' => 50,
            'description' => 'Initial credits',
            'source_type' => User::class,
            'source_id' => $user->id,
        ]);

        $walletResponse = $this->actingAs($user)->getJson('/api/my/wallet');
        $walletResponse->assertOk();
        $walletResponse->assertJsonPath('data.balance', 50);

        $txResponse = $this->actingAs($user)->getJson('/api/my/wallet/transactions');
        $txResponse->assertOk();
        $this->assertCount(1, $txResponse->json('data'));
    }
}
