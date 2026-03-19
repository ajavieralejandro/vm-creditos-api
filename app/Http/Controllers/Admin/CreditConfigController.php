<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCreditConfigRequest;
use App\Http\Requests\UpdateCreditConfigRequest;
use App\Models\CreditConfig;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CreditConfigController extends Controller
{
    public function index()
    {
        $configs = CreditConfig::orderByDesc('created_at')->paginate(10);
        $current = $configs->first();

        return view('admin.credit-config.index', [
            'title' => 'Configuración de créditos',
            'subtitle' => 'Parámetros globales de expiración y penalidades',
            'configs' => $configs,
            'current' => $current,
        ]);
    }

    public function create(): RedirectResponse|\Illuminate\View\View
    {
        if (CreditConfig::exists()) {
            return redirect()
                ->route('admin.credit-config.index')
                ->with('status', 'Ya existe una configuración, edítala en lugar de crear otra.');
        }

        return view('admin.credit-config.create', [
            'title' => 'Nueva configuración de créditos',
        ]);
    }

    public function store(StoreCreditConfigRequest $request): RedirectResponse
    {
        CreditConfig::create($request->validated());

        return redirect()
            ->route('admin.credit-config.index')
            ->with('status', 'Configuración creada correctamente.');
    }

    public function edit(CreditConfig $credit_config)
    {
        return view('admin.credit-config.edit', [
            'title' => 'Editar configuración de créditos',
            'config' => $credit_config,
        ]);
    }

    public function update(UpdateCreditConfigRequest $request, CreditConfig $credit_config): RedirectResponse
    {
        $credit_config->update($request->validated());

        return redirect()
            ->route('admin.credit-config.index')
            ->with('status', 'Configuración actualizada correctamente.');
    }
}
