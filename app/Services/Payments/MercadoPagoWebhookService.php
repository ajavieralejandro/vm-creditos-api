<?php

namespace App\Services\Payments;

use App\Enums\CreditPurchaseOrderStatus;
use App\Enums\WebhookStatus;
use App\Models\CreditPurchaseOrder;
use App\Models\PaymentWebhook;
use App\Services\CreditPurchases\CreditPurchaseAccreditationService;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Arr;

class MercadoPagoWebhookService
{
    public function __construct(
        private readonly DatabaseManager $db,
        private readonly MercadoPagoClient $client,
        private readonly CreditPurchaseAccreditationService $accreditationService,
    ) {
    }

    public function handlePaymentWebhook(PaymentWebhook $webhook): void
    {
        $payload = $webhook->payload ?? [];
        $topic = $webhook->topic;

        $webhook->status = WebhookStatus::Processing;
        $webhook->attempt_count = ($webhook->attempt_count ?? 0) + 1;
        $webhook->save();

        try {
            $paymentId = Arr::get($payload, 'data.id') ?? Arr::get($payload, 'resource.id') ?? Arr::get($payload, 'id');

            if (! $paymentId) {
                $webhook->status = WebhookStatus::Ignored;
                $webhook->last_error = 'Missing payment id in webhook payload';
                $webhook->processed_at = now();
                $webhook->save();

                return;
            }

            $payment = $this->client->getPayment((string) $paymentId);

            $externalReference = $payment['external_reference'] ?? null;
            if (! $externalReference) {
                $webhook->status = WebhookStatus::Ignored;
                $webhook->last_error = 'Missing external_reference in payment data';
                $webhook->processed_at = now();
                $webhook->save();

                return;
            }

            $order = CreditPurchaseOrder::where('external_reference', $externalReference)->first();

            if (! $order) {
                $webhook->status = WebhookStatus::Ignored;
                $webhook->last_error = 'No local order for external_reference '.$externalReference;
                $webhook->processed_at = now();
                $webhook->save();

                return;
            }

            $order->payment_status = (string) ($payment['status'] ?? $order->payment_status);
            $order->payment_status_detail = $payment['status_detail'] ?? $order->payment_status_detail;
            $order->payment_payload = $payment;

            if (! empty($payment['date_created'])) {
                $order->payment_created_at = \Illuminate\Support\Carbon::parse($payment['date_created']);
            }

            if (! empty($payment['date_last_updated'])) {
                $order->payment_updated_at = \Illuminate\Support\Carbon::parse($payment['date_last_updated']);
            }

            if (($payment['status'] ?? null) === 'approved') {
                $order->status = CreditPurchaseOrderStatus::Approved;
                $order->save();

                $this->accreditationService->accreditFromApprovedPayment($order, $payment);
            } elseif (($payment['status'] ?? null) === 'rejected') {
                $order->status = CreditPurchaseOrderStatus::Rejected;
                $order->failed_at = now();
                $order->save();
            }

            $webhook->status = WebhookStatus::Processed;
            $webhook->processed_at = now();
            $webhook->save();
        } catch (\Throwable $e) {
            $webhook->status = WebhookStatus::Failed;
            $webhook->last_error = $e->getMessage();
            $webhook->processed_at = now();
            $webhook->save();

            throw $e;
        }
    }
}
