@component('layouts.admin', ['title' => $title ?? 'Dashboard', 'subtitle' => $subtitle ?? null])
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 text-sm">
        <div class="bg-white border border-slate-200 rounded p-4">
            <div class="text-xs uppercase text-slate-500 mb-1">Packs activos</div>
            <div class="text-2xl font-semibold text-slate-900">{{ $metrics['active_packs'] ?? 0 }}</div>
        </div>
        <div class="bg-white border border-slate-200 rounded p-4">
            <div class="text-xs uppercase text-slate-500 mb-1">Órdenes pendientes</div>
            <div class="text-2xl font-semibold text-amber-600">{{ $metrics['orders_pending'] ?? 0 }}</div>
        </div>
        <div class="bg-white border border-slate-200 rounded p-4">
            <div class="text-xs uppercase text-slate-500 mb-1">Órdenes acreditadas</div>
            <div class="text-2xl font-semibold text-emerald-700">{{ $metrics['orders_accredited'] ?? 0 }}</div>
        </div>
        <div class="bg-white border border-slate-200 rounded p-4">
            <div class="text-xs uppercase text-slate-500 mb-1">Webhooks con error</div>
            <div class="text-2xl font-semibold text-red-600">{{ $metrics['webhooks_failed'] ?? 0 }}</div>
        </div>
        <div class="bg-white border border-slate-200 rounded p-4">
            <div class="text-xs uppercase text-slate-500 mb-1">Créditos acreditados</div>
            <div class="text-2xl font-semibold text-slate-900">{{ $metrics['total_credits_accredited'] ?? 0 }}</div>
        </div>
        <div class="bg-white border border-slate-200 rounded p-4">
            <div class="text-xs uppercase text-slate-500 mb-1">Saldo total wallets</div>
            <div class="text-2xl font-semibold text-slate-900">{{ $metrics['total_wallet_balance'] ?? 0 }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
        <div class="bg-white border border-slate-200 rounded overflow-hidden">
            <div class="px-4 py-2 border-b border-slate-200 bg-slate-50 text-xs font-semibold text-slate-600 uppercase">Últimas órdenes</div>
            <table class="min-w-full text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase">
                    <tr>
                        <th class="px-3 py-2 text-left">ID</th>
                        <th class="px-3 py-2 text-left">Usuario</th>
                        <th class="px-3 py-2 text-left">Pack</th>
                        <th class="px-3 py-2 text-left">Estado</th>
                        <th class="px-3 py-2 text-right">Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr class="border-b border-slate-100 hover:bg-slate-50">
                            <td class="px-3 py-2 align-top">
                                <a href="{{ route('admin.credit-purchases.show', $order) }}" class="text-slate-800 hover:underline">#{{ $order->id }}</a>
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
                            <td class="px-3 py-2 align-top text-right">
                                @if(!is_null($order->price_amount))
                                    ${{ number_format($order->price_amount / 100, 2, ',', '.') }} {{ $order->currency }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-3 py-6 text-center text-slate-500">Sin órdenes recientes.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-white border border-slate-200 rounded overflow-hidden">
            <div class="px-4 py-2 border-b border-slate-200 bg-slate-50 text-xs font-semibold text-slate-600 uppercase">Últimos webhooks</div>
            <table class="min-w-full text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase">
                    <tr>
                        <th class="px-3 py-2 text-left">ID</th>
                        <th class="px-3 py-2 text-left">Proveedor</th>
                        <th class="px-3 py-2 text-left">Estado</th>
                        <th class="px-3 py-2 text-left">Topic</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentWebhooks as $webhook)
                        <tr class="border-b border-slate-100 hover:bg-slate-50">
                            <td class="px-3 py-2 align-top">
                                <a href="{{ route('admin.webhooks.show', $webhook) }}" class="text-slate-800 hover:underline">#{{ $webhook->id }}</a>
                            </td>
                            <td class="px-3 py-2 align-top">{{ $webhook->provider }}</td>
                            <td class="px-3 py-2 align-top">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] {{ $webhook->status?->value === 'failed' ? 'bg-red-50 text-red-700' : ($webhook->status?->value === 'processed' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600') }}">
                                    {{ $webhook->status?->value ?? '-' }}
                                </span>
                            </td>
                            <td class="px-3 py-2 align-top">{{ $webhook->topic }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-3 py-6 text-center text-slate-500">Sin webhooks recientes.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endcomponent
