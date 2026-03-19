<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCreditPackRequest;
use App\Http\Requests\UpdateCreditPackRequest;
use App\Models\CreditPack;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CreditPackController extends Controller
{
    public function index(Request $request)
    {
        $query = CreditPack::query();

        if ($status = $request->string('status')->toString()) {
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($search = $request->string('search')->toString()) {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $packs = $query
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.credit-packs.index', [
            'title' => 'Packs de créditos',
            'packs' => $packs,
            'filters' => [
                'status' => $status,
                'search' => $search,
            ],
        ]);
    }

    public function create()
    {
        return view('admin.credit-packs.create', [
            'title' => 'Nuevo pack de créditos',
        ]);
    }

    public function store(StoreCreditPackRequest $request): RedirectResponse
    {
        CreditPack::create($request->validated());

        return redirect()
            ->route('admin.credit-packs.index')
            ->with('status', 'Pack creado correctamente.');
    }

    public function edit(CreditPack $credit_pack)
    {
        return view('admin.credit-packs.edit', [
            'title' => 'Editar pack de créditos',
            'pack' => $credit_pack,
        ]);
    }

    public function update(UpdateCreditPackRequest $request, CreditPack $credit_pack): RedirectResponse
    {
        $credit_pack->update($request->validated());

        return redirect()
            ->route('admin.credit-packs.index')
            ->with('status', 'Pack actualizado correctamente.');
    }

    public function destroy(CreditPack $credit_pack): RedirectResponse
    {
        $credit_pack->delete();

        return redirect()
            ->route('admin.credit-packs.index')
            ->with('status', 'Pack eliminado correctamente.');
    }

    public function toggle(CreditPack $credit_pack): RedirectResponse
    {
        $credit_pack->is_active = ! $credit_pack->is_active;
        $credit_pack->save();

        return redirect()
            ->route('admin.credit-packs.index')
            ->with('status', 'Estado de pack actualizado.');
    }
}
