<?php

namespace App\Enums;

enum CreditPurchaseOrderStatus: string
{
    case Pending = 'pending';
    case PreferenceCreated = 'preference_created';
    case PaymentPending = 'payment_pending';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case Cancelled = 'cancelled';
    case Refunded = 'refunded';
    case Accredited = 'accredited';
    case Failed = 'failed';
}
