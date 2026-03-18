<?php

namespace App\Services\Payments;

use App\Exceptions\PaymentProviderException;
use Illuminate\Support\Facades\Http;

class MercadoPagoClient
{
    public function createPreference(array $payload): array
    {
        $response = Http::baseUrl(config('mercadopago.base_url'))
            ->withToken((string) config('mercadopago.access_token'))
            ->acceptJson()
            ->post('/checkout/preferences', $payload);

        if (! $response->successful()) {
            throw new PaymentProviderException('Failed to create Mercado Pago preference', $response->status(), $response->json());
        }

        return $response->json();
    }

    public function getPayment(string $paymentId): array
    {
        $response = Http::baseUrl(config('mercadopago.base_url'))
            ->withToken((string) config('mercadopago.access_token'))
            ->acceptJson()
            ->get("/v1/payments/{$paymentId}");

        if (! $response->successful()) {
            throw new PaymentProviderException('Failed to retrieve Mercado Pago payment', $response->status(), $response->json());
        }

        return $response->json();
    }

    public function getMerchantOrder(string $merchantOrderId): array
    {
        $response = Http::baseUrl(config('mercadopago.base_url'))
            ->withToken((string) config('mercadopago.access_token'))
            ->acceptJson()
            ->get("/merchant_orders/{$merchantOrderId}");

        if (! $response->successful()) {
            throw new PaymentProviderException('Failed to retrieve Mercado Pago merchant order', $response->status(), $response->json());
        }

        return $response->json();
    }
}
