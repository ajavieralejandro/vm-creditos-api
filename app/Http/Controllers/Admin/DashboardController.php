<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CreditPurchaseOrderStatus;
use App\Enums\WalletTransactionType;
use App\Enums\WebhookStatus;
use App\Http\Controllers\Controller;
use App\Models\CreditPack;
use App\Models\CreditPurchaseOrder;
use App\Models\PaymentWebhook;
use App\Models\UserWallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $activePacks = CreditPack::where('is_active', true)->count();

        $pendingStatuses = [
            CreditPurchaseOrderStatus::Pending,
            CreditPurchaseOrderStatus::PreferenceCreated,
            CreditPurchaseOrderStatus::PaymentPending,
        ];

        $ordersPending = CreditPurchaseOrder::whereIn('status', $pendingStatuses)->count();
        $ordersApproved = CreditPurchaseOrder::where('status', CreditPurchaseOrderStatus::Approved)->count();
        $ordersAccredited = CreditPurchaseOrder::where('status', CreditPurchaseOrderStatus::Accredited)->count();
        $ordersRejected = CreditPurchaseOrder::where('status', CreditPurchaseOrderStatus::Rejected)->count();
        $ordersFailed = CreditPurchaseOrder::where('status', CreditPurchaseOrderStatus::Failed)->count();

        $webhooksFailed = PaymentWebhook::where('status', WebhookStatus::Failed)->count();
        $webhooksPending = PaymentWebhook::whereIn('status', [WebhookStatus::Received, WebhookStatus::Processing])->count();

        $totalCreditsAccredited = WalletTransaction::where('type', WalletTransactionType::Credit)->sum('amount');
        $totalWalletBalance = UserWallet::sum('balance');

        $recentOrders = CreditPurchaseOrder::with(['user', 'creditPack'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $recentWebhooks = PaymentWebhook::orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('admin.dashboard', [
            'title' => 'Dashboard',
            'subtitle' => 'Resumen operativo de créditos y pagos',
            'metrics' => [
                'active_packs' => $activePacks,
                'orders_pending' => $ordersPending,
                'orders_approved' => $ordersApproved,
                'orders_accredited' => $ordersAccredited,
                'orders_rejected' => $ordersRejected,
                'orders_failed' => $ordersFailed,
                'webhooks_failed' => $webhooksFailed,
                'webhooks_pending' => $webhooksPending,
                'total_credits_accredited' => $totalCreditsAccredited,
                'total_wallet_balance' => $totalWalletBalance,
            ],
            'recentOrders' => $recentOrders,
            'recentWebhooks' => $recentWebhooks,
        ]);
    }
}
