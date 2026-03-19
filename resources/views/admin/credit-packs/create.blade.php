@component('layouts.admin', ['title' => $title ?? 'Nuevo pack de créditos'])
    <h2 class="text-sm font-semibold text-slate-800 mb-4">Crear nuevo pack de créditos</h2>

    @include('admin.credit-packs._form', [
        'pack' => null,
        'action' => route('admin.credit-packs.store'),
        'method' => 'POST',
        'submitLabel' => 'Crear pack',
    ])
@endcomponent
