<?php

namespace App\Http\Controllers\Api;

use App\Enums\CreditPurchaseOrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCreditPurchaseRequest;
use App\Http\Resources\CreditPurchaseOrderResource;
use App\Models\CreditPack;
use App\Models\CreditPurchaseOrder;
use App\Services\CreditPurchases\CreditPurchaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CreditPurchaseController extends Controller
{
    public function store(StoreCreditPurchaseRequest $request, CreditPurchaseService $service): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $data = $request->validated();

        $query = CreditPack::query()->where('is_active', true);

        if (isset($data['pack_id'])) {
            $pack = $query->whereKey($data['pack_id'])->first();
        } else {
            $pack = $query->where('code', $data['pack_code'])->first();
        }

        if (! $pack) {
            return response()->json([
                'message' => 'Selected credit pack is not available',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        [$order, $preference] = $service->createOrderAndPreference($user, $pack);

        return (new CreditPurchaseOrderResource($order))
            ->additional([
                'mercadopago' => [
                    'preference_id' => $order->mp_preference_id,
                    'init_point' => $order->mp_init_point,
                ],
            ])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Request $request, CreditPurchaseOrder $creditPurchaseOrder): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        if ($creditPurchaseOrder->user_id !== $user->id) {
            return response()->json([
                'message' => 'Not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return (new CreditPurchaseOrderResource($creditPurchaseOrder))->response();
    }

    public function indexMy(Request $request): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $orders = CreditPurchaseOrder::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(100)
            ->get();

        return CreditPurchaseOrderResource::collection($orders)->response();
    }
}
