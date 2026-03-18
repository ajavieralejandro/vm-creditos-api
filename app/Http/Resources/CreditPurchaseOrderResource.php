<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CreditPurchaseOrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'wallet_id' => $this->wallet_id,
            'credit_pack_id' => $this->credit_pack_id,
            'status' => $this->status?->value,
            'external_reference' => $this->external_reference,
            'credits_amount' => $this->credits_amount,
            'price_amount' => $this->price_amount,
            'currency' => $this->currency,
            'pack_snapshot' => $this->pack_snapshot,
            'payment_provider' => $this->payment_provider?->value,
            'mp_preference_id' => $this->mp_preference_id,
            'mp_init_point' => $this->mp_init_point,
            'mp_payment_id' => $this->mp_payment_id,
            'mp_merchant_order_id' => $this->mp_merchant_order_id,
            'payment_status' => $this->payment_status,
            'payment_status_detail' => $this->payment_status_detail,
            'payment_created_at' => $this->payment_created_at,
            'payment_updated_at' => $this->payment_updated_at,
            'paid_at' => $this->paid_at,
            'approved_at' => $this->approved_at,
            'accredited_at' => $this->accredited_at,
            'failed_at' => $this->failed_at,
            'cancelled_at' => $this->cancelled_at,
            'refunded_at' => $this->refunded_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
