<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCreditConfigRequest;
use App\Http\Requests\UpdateCreditConfigRequest;
use App\Http\Resources\CreditConfigResource;
use App\Models\CreditConfig;
use Illuminate\Http\JsonResponse;

class CreditConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $configs = CreditConfig::latest()->get();

        return CreditConfigResource::collection($configs)->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCreditConfigRequest $request): JsonResponse
    {
        $config = CreditConfig::create($request->validated());

        return (new CreditConfigResource($config))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(CreditConfig $creditConfig): JsonResponse
    {
        return (new CreditConfigResource($creditConfig))->response();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCreditConfigRequest $request, CreditConfig $creditConfig): JsonResponse
    {
        $creditConfig->update($request->validated());

        return (new CreditConfigResource($creditConfig))->response();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CreditConfig $creditConfig): JsonResponse
    {
        $creditConfig->delete();

        return response()->json([
            'message' => 'Deleted successfully',
        ]);
    }
}
