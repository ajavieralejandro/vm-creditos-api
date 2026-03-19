@php
    /** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator $orders */
@endphp

@component('layouts.admin', ['title' => $title ?? 'Órdenes de compra'])
    <form method="GET" action="{{ route('admin.credit-purchases.index') }}" class="flex flex-wrap gap-2 items-end text-sm mb-4">
        <div>
            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Estado</label>
            <select name="status" class="border rounded px-2 py-1">
                <option value="">Todos</option>
                <option value="pending" @selected(($filters['status'] ?? '') === 'pending')>Pendientes</option>
                <option value="approved" @selected(($filters['status'] ?? '') === 'approved')>Aprobadas</option>
                <option value="accredited" @selected(($filters['status'] ?? '') === 'accredited')>Acreditadas</option>
                <option value="rejected" @selected(($filters['status'] ?? '') === 'rejected')>Rechazadas</option>
                <option value="failed" @selected(($filters['status'] ?? '') === 'failed')>Fallidas</option>
            </select>
        </div>
        <div>
            <label class="block text-[11px] font-semibold text-slate-600 mb-1">External ref</label>
            <input type="text" name="external_reference" value="{{ $filters['external_reference'] ?? '' }}" class="border rounded px-2 py-1 min-w-[160px]">
        </div>
        <div>
            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Usuario (ID, email, nombre)</label>
            <input type="text" name="user" value="{{ $filters['user'] ?? '' }}" class="border rounded px-2 py-1 min-w-[200px]">
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
            <a href="{{ route('admin.credit-purchases.index') }}" class="text-xs text-slate-600 hover:underline">Limpiar</a>
        </div>
    </form>

    <div class="bg-white rounded border border-slate-200 overflow-hidden text-sm">
        <table class="min-w-full">
            <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase text-slate-500">
                <tr>
                    <th class="px-3 py-2 text-left">ID</th>
                    <th class="px-3 py-2 text-left">Usuario</th>
                    <th class="px-3 py-2 text-left">Pack</th>
                    <th class="px-3 py-2 text-left">Estado</th>
                    <th class="px-3 py-2 text-left">Payment</th>
                    <th class="px-3 py-2 text-right">Monto</th>
                    <th class="px-3 py-2 text-left">Creada</th>
                    <th class="px-3 py-2 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr class="border-b border-slate-100 hover:bg-slate-50">
                        <td class="px-3 py-2 align-top">
                            <a href="{{ route('admin.credit-purchases.show', $order) }}" class="text-slate-800 hover:underline">#{{ $order->id }}</a>
                            <div class="text-[11px] text-slate-500">{{ $order->external_reference }}</div>
                        </td>
                        <td class="px-3 py-2 align-top">
                            @if($order->user)
                                <span class="block">{{ $order->user->name }}</span>
                                <span class="block text-[11px] text-slate-500">{{ $order->user->email }}</span>
                            @else
                                <span class="text-[11px] text-slate-400">Sin usuario</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 align-top">
                            {{ $order->creditPack->name ?? '-' }}
                        </td>
                        <td class="px-3 py-2 align-top">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] {{ $order->status?->value === 'accredited' ? 'bg-emerald-50 text-emerald-700' : ($order->status?->value === 'approved' ? 'bg-sky-50 text-sky-700' : ($order->status?->value === 'failed' || $order->status?->value === 'rejected' ? 'bg-red-50 text-red-700' : 'bg-slate-100 text-slate-600')) }}">
                                {{ $order->status?->value ?? '-' }}
                            </span>
                        </td>
                        <td class="px-3 py-2 align-top text-xs">
                            <div>Provider: {{ $order->payment_provider?->value ?? '-' }}</div>
                            <div class="text-[11px] text-slate-500">Status: {{ $order->payment_status ?? '-' }}</div>
                        </td>
                        <td class="px-3 py-2 align-top text-right">
                            @if(!is_null($order->price_amount))
                                ${{ number_format($order->price_amount / 100, 2, ',', '.') }} {{ $order->currency }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-3 py-2 align-top text-xs">
                            {{ $order->created_at?->format('Y-m-d H:i') }}
                        </td>
                        <td class="px-3 py-2 align-top text-right">
                            <a href="{{ route('admin.credit-purchases.show', $order) }}" class="text-xs text-slate-700 hover:underline">Ver</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-3 py-6 text-center text-slate-500 text-sm">No hay órdenes registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $orders->links() }}
    </div>
@endcomponent
