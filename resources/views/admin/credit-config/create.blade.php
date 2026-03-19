@component('layouts.admin', ['title' => $title ?? 'Nueva configuración de créditos'])
    <h2 class="text-sm font-semibold text-slate-800 mb-4">Crear configuración global de créditos</h2>

    @include('admin.credit-config._form', [
        'config' => null,
        'action' => route('admin.credit-config.store'),
        'method' => 'POST',
        'submitLabel' => 'Crear configuración',
    ])
@endcomponent
