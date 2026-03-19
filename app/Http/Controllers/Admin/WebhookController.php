<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CreditPurchaseOrder;
use App\Models\PaymentWebhook;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class WebhookController extends Controller
{
    public function index(Request $request)
    {
        $query = PaymentWebhook::query()->orderByDesc('created_at');

        $status = $request->string('status')->toString();
        if ($status) {
            $query->where('status', $status);
        }

        if ($provider = $request->string('provider')->toString()) {
            $query->where('provider', $provider);
        }

        if ($topic = $request->string('topic')->toString()) {
            $query->where('topic', 'like', "%{$topic}%");
        }

        if ($externalId = $request->string('external_id')->toString()) {
            $query->where('external_id', 'like', "%{$externalId}%");
        }

        if ($from = $request->string('from')->toString()) {
            $query->whereDate('created_at', '>=', Carbon::parse($from));
        }

        if ($to = $request->string('to')->toString()) {
            $query->whereDate('created_at', '<=', Carbon::parse($to));
        }

        $webhooks = $query->paginate(25)->withQueryString();

        // Intentar mapear payment_id -> orden
        $paymentIds = $webhooks->pluck('payload.data.id')->filter()->unique()->all();

        $ordersByPaymentId = [];
        if (! empty($paymentIds)) {
            $ordersByPaymentId = CreditPurchaseOrder::whereIn('mp_payment_id', $paymentIds)
                ->get()
                ->keyBy('mp_payment_id');
        }

        return view('admin.webhooks.index', [
            'title' => 'Webhooks de pagos',
            'webhooks' => $webhooks,
            'ordersByPaymentId' => $ordersByPaymentId,
            'filters' => [
                'status' => $status,
                'provider' => $provider ?? null,
                'topic' => $topic ?? null,
                'external_id' => $externalId ?? null,
                'from' => $from ?? null,
                'to' => $to ?? null,
            ],
        ]);
    }

    public function show(PaymentWebhook $payment_webhook)
    {
        $payment_webhook->refresh();

        $paymentId = data_get($payment_webhook->payload, 'data.id');
        $relatedOrder = null;
        if ($paymentId) {
            $relatedOrder = CreditPurchaseOrder::where('mp_payment_id', $paymentId)->first();
        }

        return view('admin.webhooks.show', [
            'title' => 'Detalle webhook #'.$payment_webhook->id,
            'webhook' => $payment_webhook,
            'relatedOrder' => $relatedOrder,
        ]);
    }
}
