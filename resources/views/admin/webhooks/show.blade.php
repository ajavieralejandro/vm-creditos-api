@php
    /** @var \App\Models\PaymentWebhook $webhook */
@endphp

@component('layouts.admin', ['title' => $title ?? 'Detalle webhook'])
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
        <div class="bg-white border border-slate-200 rounded p-4 space-y-3">
            <h2 class="text-sm font-semibold text-slate-800 mb-2">Datos del webhook</h2>
            <div class="grid grid-cols-2 gap-2 text-xs">
                <div class="text-slate-500">ID</div>
                <div>#{{ $webhook->id }}</div>
                <div class="text-slate-500">Proveedor</div>
                <div>{{ $webhook->provider }}</div>
                <div class="text-slate-500">Topic</div>
                <div>{{ $webhook->topic }}</div>
                <div class="text-slate-500">External ID</div>
                <div>{{ $webhook->external_id }}</div>
                <div class="text-slate-500">Estado</div>
                <div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] {{ $webhook->status?->value === 'failed' ? 'bg-red-50 text-red-700' : ($webhook->status?->value === 'processed' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600') }}">
                        {{ $webhook->status?->value ?? '-' }}
                    </span>
                </div>
                <div class="text-slate-500">Intentos</div>
                <div>{{ $webhook->attempt_count }}</div>
                <div class="text-slate-500">Procesado</div>
                <div>{{ $webhook->processed_at?->format('Y-m-d H:i') ?? '-' }}</div>
            </div>

            <div class="mt-3 text-xs">
                <div class="text-slate-500 mb-1">Orden relacionada</div>
                @if($relatedOrder)
                    <a href="{{ route('admin.credit-purchases.show', $relatedOrder) }}" class="text-slate-800 hover:underline">Orden #{{ $relatedOrder->id }}</a>
                @else
                    <span class="text-[11px] text-slate-400">No se encontró orden relacionada.</span>
                @endif
            </div>

            @if($webhook->last_error)
                <div class="mt-3">
                    <h3 class="text-xs font-semibold text-red-700 mb-1">Último error</h3>
                    <pre class="text-[11px] bg-red-950 text-red-100 rounded p-3 overflow-auto max-h-48">{{ $webhook->last_error }}</pre>
                </div>
            @endif
        </div>

        <div class="bg-white border border-slate-200 rounded p-4 space-y-3">
            <h2 class="text-sm font-semibold text-slate-800 mb-2">Payload y headers</h2>
            <div>
                <h3 class="text-xs font-semibold text-slate-700 mb-1">Payload</h3>
                @if($webhook->payload)
                    <pre class="text-[11px] bg-slate-950 text-slate-100 rounded p-3 overflow-auto max-h-80">{{ json_encode($webhook->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                @else
                    <p class="text-[11px] text-slate-500">Sin payload almacenado.</p>
                @endif
            </div>
            <div>
                <h3 class="text-xs font-semibold text-slate-700 mb-1">Headers</h3>
                @if($webhook->headers)
                    <pre class="text-[11px] bg-slate-950 text-slate-100 rounded p-3 overflow-auto max-h-80">{{ json_encode($webhook->headers, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                @else
                    <p class="text-[11px] text-slate-500">Sin headers almacenados.</p>
                @endif
            </div>
        </div>
    </div>
@endcomponent
