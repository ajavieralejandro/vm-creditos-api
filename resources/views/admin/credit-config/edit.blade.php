@php
    /** @var \App\Models\CreditConfig $config */
@endphp

@component('layouts.admin', ['title' => $title ?? 'Editar configuración de créditos'])
    <h2 class="text-sm font-semibold text-slate-800 mb-4">Editar configuración global de créditos</h2>

    @include('admin.credit-config._form', [
        'config' => $config,
        'action' => route('admin.credit-config.update', $config),
        'method' => 'PUT',
        'submitLabel' => 'Guardar cambios',
    ])
@endcomponent
