@php
    /** @var \App\Models\CreditPack|null $pack */
@endphp

<form method="POST" action="{{ $action }}" class="space-y-4 max-w-xl">
    @csrf
    @if(in_array($method, ['PUT', 'PATCH', 'DELETE']))
        @method($method)
    @endif

    @if ($errors->any())
        <div class="rounded border border-red-300 bg-red-50 text-red-800 text-sm px-4 py-2">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Código</label>
            <input type="text" name="code" value="{{ old('code', $pack->code ?? '') }}" class="w-full border rounded px-3 py-2 text-sm" required maxlength="100">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Nombre</label>
            <input type="text" name="name" value="{{ old('name', $pack->name ?? '') }}" class="w-full border rounded px-3 py-2 text-sm" required maxlength="150">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Créditos</label>
            <input type="number" min="1" name="credits" value="{{ old('credits', $pack->credits ?? 1) }}" class="w-full border rounded px-3 py-2 text-sm" required>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Precio ARS</label>
            <input type="number" min="0" name="price_ars" value="{{ old('price_ars', $pack->price_ars ?? 0) }}" class="w-full border rounded px-3 py-2 text-sm" required>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Orden</label>
            <input type="number" min="0" name="sort_order" value="{{ old('sort_order', $pack->sort_order ?? 0) }}" class="w-full border rounded px-3 py-2 text-sm">
        </div>
    </div>

    <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1">Descripción</label>
        <textarea name="description" rows="3" class="w-full border rounded px-3 py-2 text-sm">{{ old('description', $pack->description ?? '') }}</textarea>
    </div>

    <div class="flex items-center gap-2">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" id="is_active" name="is_active" value="1" class="rounded border-slate-300" {{ old('is_active', $pack->is_active ?? true) ? 'checked' : '' }}>
        <label for="is_active" class="text-sm text-slate-700">Activo</label>
    </div>

    <div class="flex items-center gap-3 pt-2">
        <button type="submit" class="inline-flex items-center px-4 py-2 rounded bg-slate-900 text-white text-sm font-medium hover:bg-slate-800">
            {{ $submitLabel }}
        </button>
        <a href="{{ route('admin.credit-packs.index') }}" class="text-sm text-slate-600 hover:underline">Cancelar</a>
    </div>
</form>
