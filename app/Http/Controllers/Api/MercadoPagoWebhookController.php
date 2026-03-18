<?php

namespace App\Http\Controllers\Api;

use App\Enums\WebhookStatus;
use App\Http\Controllers\Controller;
use App\Models\PaymentWebhook;
use App\Services\Payments\MercadoPagoWebhookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class MercadoPagoWebhookController extends Controller
{
    public function handle(Request $request, MercadoPagoWebhookService $service): JsonResponse
    {
        $configuredSecret = config('mercadopago.webhook_secret');
        if ($configuredSecret !== null) {
            $providedSecret = $request->query('secret');
            if (! hash_equals((string) $configuredSecret, (string) $providedSecret)) {
                return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
            }
        }

        $payload = $request->all();
        $topic = $request->input('type') ?? $request->input('topic') ?? $request->input('action');
        $externalId = data_get($payload, 'data.id') ?? data_get($payload, 'id');

        $webhook = PaymentWebhook::create([
            'provider' => 'mercadopago',
            'topic' => $topic,
            'external_id' => $externalId,
            'status' => WebhookStatus::Received,
            'attempt_count' => 0,
            'payload' => $payload,
            'headers' => $request->headers->all(),
        ]);

        try {
            $service->handlePaymentWebhook($webhook);
        } catch (\Throwable $e) {
            Log::error('Error processing Mercado Pago webhook', [
                'webhook_id' => $webhook->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json(['status' => 'error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['status' => 'ok']);
    }
}
