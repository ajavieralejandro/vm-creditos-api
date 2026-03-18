<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCreditPackRequest;
use App\Http\Requests\UpdateCreditPackRequest;
use App\Http\Resources\CreditPackResource;
use App\Models\CreditPack;
use Illuminate\Http\JsonResponse;

class CreditPackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $packs = CreditPack::where('is_active', true)
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        return CreditPackResource::collection($packs)->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCreditPackRequest $request): JsonResponse
    {
        $pack = CreditPack::create($request->validated());

        return (new CreditPackResource($pack))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(CreditPack $creditPack): JsonResponse
    {
        return (new CreditPackResource($creditPack))->response();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCreditPackRequest $request, CreditPack $creditPack): JsonResponse
    {
        $creditPack->update($request->validated());

        return (new CreditPackResource($creditPack))->response();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CreditPack $creditPack): JsonResponse
    {
        $creditPack->delete();

        return response()->json([
            'message' => 'Deleted successfully',
        ]);
    }
}
