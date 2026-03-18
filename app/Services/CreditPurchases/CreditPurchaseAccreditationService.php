<?php

namespace App\Services\CreditPurchases;

use App\Enums\CreditPurchaseOrderStatus;
use App\Enums\PaymentProvider;
use App\Models\CreditPurchaseOrder;
use App\Services\Wallet\WalletService;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Arr;

class CreditPurchaseAccreditationService
{
    public function __construct(
        private readonly DatabaseManager $db,
        private readonly WalletService $walletService,
    ) {
    }

    public function accreditFromApprovedPayment(CreditPurchaseOrder $order, array $paymentData): CreditPurchaseOrder
    {
        return $this->db->transaction(function () use ($order, $paymentData): CreditPurchaseOrder {
            /** @var CreditPurchaseOrder $order */
            $order->refresh();

            if ($order->status === CreditPurchaseOrderStatus::Accredited || $order->accredited_at !== null) {
                return $order;
            }

            if ($order->payment_provider !== PaymentProvider::MercadoPago) {
                return $order;
            }

            $status = $paymentData['status'] ?? null;
            if ($status !== 'approved') {
                return $order;
            }

            $paidAmount = (int) round((float) ($paymentData['transaction_amount'] ?? 0) * 100);
            if ($paidAmount < $order->price_amount) {
                $order->last_error = 'Paid amount less than expected';
                $order->status = CreditPurchaseOrderStatus::Failed;
                $order->save();

                return $order;
            }

            $order->mp_payment_id = (string) ($paymentData['id'] ?? $order->mp_payment_id);
            $order->mp_merchant_order_id = (string) Arr::get($paymentData, 'order.id', $order->mp_merchant_order_id);
            $order->payment_status = (string) ($paymentData['status'] ?? 'approved');
            $order->payment_status_detail = $paymentData['status_detail'] ?? null;
            $order->payment_payload = $paymentData;
            $order->approved_at = now();

            $this->walletService->creditFromOrder($order);

            $order->status = CreditPurchaseOrderStatus::Accredited;
            $order->accredited_at = now();
            $order->save();

            return $order;
        });
    }
}
