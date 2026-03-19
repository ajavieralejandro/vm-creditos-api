@php
    /** @var \App\Models\CreditPurchaseOrder $order */
@endphp

@component('layouts.admin', ['title' => $title ?? 'Detalle orden'])
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
        <div class="bg-white border border-slate-200 rounded p-4 space-y-3">
            <h2 class="text-sm font-semibold text-slate-800 mb-2">Datos de la orden</h2>
            <div class="grid grid-cols-2 gap-2 text-xs">
                <div class="text-slate-500">ID</div>
                <div>#{{ $order->id }}</div>
                <div class="text-slate-500">External ref</div>
                <div>{{ $order->external_reference }}</div>
                <div class="text-slate-500">Usuario</div>
                <div>
                    @if($order->user)
                        <div>{{ $order->user->name }}</div>
                        <div class="text-[11px] text-slate-500">{{ $order->user->email }}</div>
                    @else
                        <span class="text-[11px] text-slate-400">Sin usuario</span>
                    @endif
                </div>
                <div class="text-slate-500">Pack</div>
                <div>{{ $order->creditPack->name ?? '-' }}</div>
                <div class="text-slate-500">Estado</div>
                <div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] {{ $order->status?->value === 'accredited' ? 'bg-emerald-50 text-emerald-700' : ($order->status?->value === 'approved' ? 'bg-sky-50 text-sky-700' : ($order->status?->value === 'failed' || $order->status?->value === 'rejected' ? 'bg-red-50 text-red-700' : 'bg-slate-100 text-slate-600')) }}">
                        {{ $order->status?->value ?? '-' }}
                    </span>
                </div>
                <div class="text-slate-500">Monto</div>
                <div>
                    @if(!is_null($order->price_amount))
                        ${{ number_format($order->price_amount / 100, 2, ',', '.') }} {{ $order->currency }}
                    @else
                        -
                    @endif
                </div>
                <div class="text-slate-500">Créditos</div>
                <div>{{ $order->credits_amount ?? '-' }}</div>
                <div class="text-slate-500">Wallet</div>
                <div>
                    @if($order->wallet)
                        <a href="{{ route('admin.wallets.show', $order->wallet) }}" class="text-slate-800 hover:underline">Wallet #{{ $order->wallet->id }}</a>
                    @else
                        <span class="text-[11px] text-slate-400">Sin wallet asociada</span>
                    @endif
                </div>
                <div class="text-slate-500">Fechas</div>
                <div class="space-y-1">
                    <div>Creada: {{ $order->created_at?->format('Y-m-d H:i') }}</div>
                    <div>Aprobada: {{ $order->approved_at?->format('Y-m-d H:i') ?? '-' }}</div>
                    <div>Acreditada: {{ $order->accredited_at?->format('Y-m-d H:i') ?? '-' }}</div>
                    <div>Fallida: {{ $order->failed_at?->format('Y-m-d H:i') ?? '-' }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded p-4 space-y-3">
            <h2 class="text-sm font-semibold text-slate-800 mb-2">Pago y webhook</h2>
            <div class="grid grid-cols-2 gap-2 text-xs">
                <div class="text-slate-500">Proveedor</div>
                <div>{{ $order->payment_provider?->value ?? '-' }}</div>
                <div class="text-slate-500">Payment ID</div>
                <div>{{ $order->mp_payment_id ?? '-' }}</div>
                <div class="text-slate-500">Merchant order</div>
                <div>{{ $order->mp_merchant_order_id ?? '-' }}</div>
                <div class="text-slate-500">Payment status</div>
                <div>{{ $order->payment_status ?? '-' }} ({{ $order->payment_status_detail ?? '-' }})</div>
            </div>

            <div class="mt-3">
                <h3 class="text-xs font-semibold text-slate-700 mb-1">Payload de pago</h3>
                @if($order->payment_payload)
                    <pre class="text-[11px] bg-slate-950 text-slate-100 rounded p-3 overflow-auto max-h-80">{{ json_encode($order->payment_payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                @else
                    <p class="text-[11px] text-slate-500">Sin payload almacenado.</p>
                @endif
            </div>
        </div>
    </div>
@endcomponent
