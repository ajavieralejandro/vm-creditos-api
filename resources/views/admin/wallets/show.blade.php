@php
    /** @var \App\Models\UserWallet $wallet */
@endphp

@component('layouts.admin', ['title' => $title ?? 'Detalle wallet'])
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm mb-6">
        <div class="bg-white border border-slate-200 rounded p-4 space-y-2 md:col-span-1">
            <h2 class="text-sm font-semibold text-slate-800 mb-2">Resumen wallet</h2>
            <div class="grid grid-cols-2 gap-2 text-xs">
                <div class="text-slate-500">ID</div>
                <div>#{{ $wallet->id }}</div>
                <div class="text-slate-500">Usuario</div>
                <div>
                    @if($wallet->user)
                        <div>{{ $wallet->user->name }}</div>
                        <div class="text-[11px] text-slate-500">{{ $wallet->user->email }}</div>
                    @else
                        <span class="text-[11px] text-slate-400">Sin usuario</span>
                    @endif
                </div>
                <div class="text-slate-500">External ID</div>
                <div>{{ $wallet->external_user_id ?? '-' }}</div>
                <div class="text-slate-500">Saldo</div>
                <div>{{ $wallet->balance }}</div>
                <div class="text-slate-500">Actualizada</div>
                <div>{{ $wallet->updated_at?->format('Y-m-d H:i') }}</div>
            </div>
        </div>

        <div class="md:col-span-2">
            <form method="GET" action="{{ route('admin.wallets.show', $wallet) }}" class="flex flex-wrap gap-2 items-end text-sm mb-3">
                <div>
                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Tipo</label>
                    <select name="type" class="border rounded px-2 py-1">
                        <option value="">Todos</option>
                        <option value="credit" @selected(($filters['type'] ?? '') === 'credit')>Crédito</option>
                        <option value="debit" @selected(($filters['type'] ?? '') === 'debit')>Débito</option>
                    </select>
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
                    <a href="{{ route('admin.wallets.show', $wallet) }}" class="text-xs text-slate-600 hover:underline">Limpiar</a>
                </div>
            </form>

            <div class="bg-white rounded border border-slate-200 overflow-hidden text-sm">
                <table class="min-w-full">
                    <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase text-slate-500">
                        <tr>
                            <th class="px-3 py-2 text-left">ID</th>
                            <th class="px-3 py-2 text-left">Tipo</th>
                            <th class="px-3 py-2 text-right">Monto</th>
                            <th class="px-3 py-2 text-right">Balance antes</th>
                            <th class="px-3 py-2 text-right">Balance después</th>
                            <th class="px-3 py-2 text-left">Origen</th>
                            <th class="px-3 py-2 text-left">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $tx)
                            <tr class="border-b border-slate-100 hover:bg-slate-50">
                                <td class="px-3 py-2 align-top text-xs">#{{ $tx->id }}</td>
                                <td class="px-3 py-2 align-top">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] {{ $tx->type->value === 'credit' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                                        {{ $tx->type->value }}
                                    </span>
                                </td>
                                <td class="px-3 py-2 align-top text-right">{{ $tx->amount }}</td>
                                <td class="px-3 py-2 align-top text-right text-xs">{{ $tx->balance_before }}</td>
                                <td class="px-3 py-2 align-top text-right text-xs">{{ $tx->balance_after }}</td>
                                <td class="px-3 py-2 align-top text-xs">
                                    @if($tx->source_type === \App\Models\CreditPurchaseOrder::class && $tx->source_id)
                                        <a href="{{ route('admin.credit-purchases.show', $tx->source_id) }}" class="text-slate-700 hover:underline">Orden #{{ $tx->source_id }}</a>
                                    @else
                                        <span class="text-[11px] text-slate-500">{{ class_basename($tx->source_type) }} #{{ $tx->source_id }}</span>
                                    @endif
                                </td>
                                <td class="px-3 py-2 align-top text-xs">{{ $tx->created_at?->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-3 py-6 text-center text-slate-500 text-sm">No hay movimientos para esta wallet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
@endcomponent
