<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CreditPurchaseOrderStatus;
use App\Http\Controllers\Controller;
use App\Models\CreditPurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CreditPurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = CreditPurchaseOrder::with(['user', 'creditPack'])
            ->orderByDesc('created_at');

        $status = $request->string('status')->toString();
        if ($status) {
            if ($status === 'pending') {
                $pendingStatuses = [
                    CreditPurchaseOrderStatus::Pending,
                    CreditPurchaseOrderStatus::PreferenceCreated,
                    CreditPurchaseOrderStatus::PaymentPending,
                ];
                $query->whereIn('status', $pendingStatuses);
            } else {
                $query->where('status', $status);
            }
        }

        if ($externalReference = $request->string('external_reference')->toString()) {
            $query->where('external_reference', 'like', "%{$externalReference}%");
        }

        if ($user = $request->string('user')->toString()) {
            $query->where(function ($q) use ($user): void {
                if (is_numeric($user)) {
                    $q->where('user_id', (int) $user);
                }

                $q->orWhereHas('user', function ($uq) use ($user): void {
                    $uq->where('email', 'like', "%{$user}%")
                        ->orWhere('name', 'like', "%{$user}%");
                });
            });
        }

        if ($from = $request->string('from')->toString()) {
            $query->whereDate('created_at', '>=', Carbon::parse($from));
        }

        if ($to = $request->string('to')->toString()) {
            $query->whereDate('created_at', '<=', Carbon::parse($to));
        }

        $orders = $query->paginate(25)->withQueryString();

        return view('admin.credit-purchases.index', [
            'title' => 'Órdenes de compra',
            'orders' => $orders,
            'filters' => [
                'status' => $status,
                'external_reference' => $externalReference ?? null,
                'user' => $user ?? null,
                'from' => $from ?? null,
                'to' => $to ?? null,
            ],
        ]);
    }

    public function show(CreditPurchaseOrder $credit_purchase_order)
    {
        $credit_purchase_order->load(['user', 'creditPack', 'wallet']);

        return view('admin.credit-purchases.show', [
            'title' => 'Detalle de orden #'.$credit_purchase_order->id,
            'order' => $credit_purchase_order,
        ]);
    }
}
