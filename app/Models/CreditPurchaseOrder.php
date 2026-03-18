<?php

namespace App\Models;

use App\Enums\CreditPurchaseOrderStatus;
use App\Enums\PaymentProvider;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class CreditPurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'wallet_id',
        'credit_pack_id',
        'status',
        'external_reference',
        'credits_amount',
        'price_amount',
        'currency',
        'pack_snapshot',
        'payment_provider',
        'mp_preference_id',
        'mp_init_point',
        'mp_payment_id',
        'mp_merchant_order_id',
        'payment_status',
        'payment_status_detail',
        'payment_payload',
        'payment_created_at',
        'payment_updated_at',
        'paid_at',
        'approved_at',
        'accredited_at',
        'failed_at',
        'cancelled_at',
        'refunded_at',
        'last_error',
    ];

    protected $casts = [
        'status' => CreditPurchaseOrderStatus::class,
        'payment_provider' => PaymentProvider::class,
        'credits_amount' => 'integer',
        'price_amount' => 'integer',
        'pack_snapshot' => 'array',
        'payment_payload' => 'array',
        'payment_created_at' => 'datetime',
        'payment_updated_at' => 'datetime',
        'paid_at' => 'datetime',
        'approved_at' => 'datetime',
        'accredited_at' => 'datetime',
        'failed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(UserWallet::class, 'wallet_id');
    }

    public function creditPack(): BelongsTo
    {
        return $this->belongsTo(CreditPack::class, 'credit_pack_id');
    }

    public function walletTransactions(): MorphMany
    {
        return $this->morphMany(WalletTransaction::class, 'source');
    }
}
