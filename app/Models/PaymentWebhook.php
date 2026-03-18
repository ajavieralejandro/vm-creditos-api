<?php

namespace App\Models;

use App\Enums\WebhookStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentWebhook extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider',
        'topic',
        'external_id',
        'status',
        'attempt_count',
        'payload',
        'headers',
        'last_error',
        'processed_at',
    ];

    protected $casts = [
        'status' => WebhookStatus::class,
        'payload' => 'array',
        'headers' => 'array',
        'processed_at' => 'datetime',
    ];
}
