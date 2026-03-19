@php
    /** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator $webhooks */
@endphp

@component('layouts.admin', ['title' => $title ?? 'Webhooks de pagos'])
    <form method="GET" action="{{ route('admin.webhooks.index') }}" class="flex flex-wrap gap-2 items-end text-sm mb-4">
        <div>
            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Estado</label>
            <select name="status" class="border rounded px-2 py-1">
                <option value="">Todos</option>
                <option value="received" @selected(($filters['status'] ?? '') === 'received')>Recibidos</option>
                <option value="processing" @selected(($filters['status'] ?? '') === 'processing')>Procesando</option>
                <option value="processed" @selected(($filters['status'] ?? '') === 'processed')>Procesados</option>
                <option value="failed" @selected(($filters['status'] ?? '') === 'failed')>Fallidos</option>
            </select>
        </div>
        <div>
            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Proveedor</label>
            <input type="text" name="provider" value="{{ $filters['provider'] ?? '' }}" class="border rounded px-2 py-1 min-w-[120px]">
        </div>
        <div>
            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Topic</label>
            <input type="text" name="topic" value="{{ $filters['topic'] ?? '' }}" class="border rounded px-2 py-1 min-w-[150px]">
        </div>
        <div>
            <label class="block text-[11px] font-semibold text-slate-600 mb-1">External ID</label>
            <input type="text" name="external_id" value="{{ $filters['external_id'] ?? '' }}" class="border rounded px-2 py-1 min-w-[150px]">
        </div>
        <div>
            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Desde</label>
            <input type="date" name="from" value="{{ $filters['from'] ?? '' }}" class="border rounded px-2 py-1">
        </div>
        <div>
            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Hasta</label>
            <input type="date" name="to" value="{{ $filters['to'] ?? '' }}" class="border rounded px-2 py-1">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded bg-slate-900 text-white text-xs font-medium hover:bg-slate-800">Filtrar</button>
            <a href="{{ route('admin.webhooks.index') }}" class="text-xs text-slate-600 hover:underline">Limpiar</a>
        </div>
    </form>

    <div class="bg-white rounded border border-slate-200 overflow-hidden text-sm">
        <table class="min-w-full">
            <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase text-slate-500">
                <tr>
                    <th class="px-3 py-2 text-left">ID</th>
                    <th class="px-3 py-2 text-left">Proveedor</th>
                    <th class="px-3 py-2 text-left">Estado</th>
                    <th class="px-3 py-2 text-left">Topic</th>
                    <th class="px-3 py-2 text-left">External ID</th>
                    <th class="px-3 py-2 text-left">Intentos</th>
                    <th class="px-3 py-2 text-left">Fecha</th>
                    <th class="px-3 py-2 text-left">Orden asociada</th>
                    <th class="px-3 py-2 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($webhooks as $webhook)
                    <tr class="border-b border-slate-100 hover:bg-slate-50">
                        <td class="px-3 py-2 align-top text-xs">#{{ $webhook->id }}</td>
                        <td class="px-3 py-2 align-top text-xs">{{ $webhook->provider }}</td>
                        <td class="px-3 py-2 align-top">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] {{ $webhook->status?->value === 'failed' ? 'bg-red-50 text-red-700' : ($webhook->status?->value === 'processed' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600') }}">
                                {{ $webhook->status?->value ?? '-' }}
                            </span>
                        </td>
                        <td class="px-3 py-2 align-top text-xs">{{ $webhook->topic }}</td>
                        <td class="px-3 py-2 align-top text-xs">{{ $webhook->external_id }}</td>
                        <td class="px-3 py-2 align-top text-xs">{{ $webhook->attempt_count }}</td>
                        <td class="px-3 py-2 align-top text-xs">{{ $webhook->created_at?->format('Y-m-d H:i') }}</td>
                        <td class="px-3 py-2 align-top text-xs">
                            @php
                                $paymentId = data_get($webhook->payload, 'data.id');
                                $order = $paymentId ? ($ordersByPaymentId[$paymentId] ?? null) : null;
                            @endphp
                            @if($order)
                                <a href="{{ route('admin.credit-purchases.show', $order) }}" class="text-slate-700 hover:underline">Orden #{{ $order->id }}</a>
                            @else
                                <span class="text-[11px] text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 align-top text-right">
                            <a href="{{ route('admin.webhooks.show', $webhook) }}" class="text-xs text-slate-700 hover:underline">Ver</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-3 py-6 text-center text-slate-500 text-sm">No hay webhooks registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $webhooks->links() }}
    </div>
@endcomponent
