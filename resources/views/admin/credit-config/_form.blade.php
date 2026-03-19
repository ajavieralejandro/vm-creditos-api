@php
    /** @var \App\Models\CreditConfig|null $config */
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

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Meses de expiración</label>
            <input type="number" min="1" max="24" name="expiration_months" value="{{ old('expiration_months', $config->expiration_months ?? 12) }}" class="w-full border rounded px-3 py-2 text-sm" required>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Minutos de gracia cancelación</label>
            <input type="number" min="0" name="cancel_grace_minutes" value="{{ old('cancel_grace_minutes', $config->cancel_grace_minutes ?? 0) }}" class="w-full border rounded px-3 py-2 text-sm" required>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Modo penalidad</label>
            <select name="penalty_mode" class="w-full border rounded px-3 py-2 text-sm" required>
                @php
                    $modes = ['none' => 'Sin penalidad', 'flat' => 'Monto fijo', 'percent' => 'Porcentaje'];
                    $selectedMode = old('penalty_mode', $config->penalty_mode ?? 'none');
                @endphp
                @foreach ($modes as $value => $label)
                    <option value="{{ $value }}" @selected($selectedMode === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Valor penalidad</label>
            <input type="number" min="0" name="penalty_value" value="{{ old('penalty_value', $config->penalty_value ?? 0) }}" class="w-full border rounded px-3 py-2 text-sm" required>
        </div>
        <div class="flex items-center gap-2 mt-6">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" id="is_active" name="is_active" value="1" class="rounded border-slate-300" {{ old('is_active', $config->is_active ?? true) ? 'checked' : '' }}>
            <label for="is_active" class="text-sm text-slate-700">Configuración activa</label>
        </div>
    </div>

    <div class="flex items-center gap-3 pt-2">
        <button type="submit" class="inline-flex items-center px-4 py-2 rounded bg-slate-900 text-white text-sm font-medium hover:bg-slate-800">
            {{ $submitLabel }}
        </button>
        <a href="{{ route('admin.credit-config.index') }}" class="text-sm text-slate-600 hover:underline">Cancelar</a>
    </div>
</form>
