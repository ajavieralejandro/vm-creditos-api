@php
    /** @var \App\Models\CreditPack $pack */
@endphp

@component('layouts.admin', ['title' => $title ?? 'Editar pack de créditos'])
    <h2 class="text-sm font-semibold text-slate-800 mb-4">Editar pack de créditos</h2>

    @include('admin.credit-packs._form', [
        'pack' => $pack,
        'action' => route('admin.credit-packs.update', $pack),
        'method' => 'PUT',
        'submitLabel' => 'Guardar cambios',
    ])
@endcomponent
