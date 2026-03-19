@php
    /** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator $configs */
@endphp

@component('layouts.admin', ['title' => $title ?? 'Configuración de créditos', 'subtitle' => $subtitle ?? null])
    <div class="flex items-center justify-between mb-4">
        <div class="text-sm text-slate-700">
            @if ($current)
                <p>
                    Configuración actual: expiración en <span class="font-semibold">{{ $current->expiration_months }}</span> meses,
                    gracia de cancelación <span class="font-semibold">{{ $current->cancel_grace_minutes }}</span> minutos,
                    modo penalidad <span class="font-semibold">{{ $current->penalty_mode }}</span>,
                    valor <span class="font-semibold">{{ $current->penalty_value }}</span>.
                </p>
            @else
                <p>No hay una configuración de créditos definida todavía.</p>
            @endif
        </div>

        <div class="flex items-center gap-2">
            @if ($current)
                <a href="{{ route('admin.credit-config.edit', $current) }}" class="inline-flex items-center px-4 py-2 rounded bg-slate-900 text-white text-sm font-medium hover:bg-slate-800">
                    Editar configuración
                </a>
            @else
                <a href="{{ route('admin.credit-config.create') }}" class="inline-flex items-center px-4 py-2 rounded bg-slate-900 text-white text-sm font-medium hover:bg-slate-800">
                    Crear configuración
                </a>
            @endif
        </div>
    </div>

    <div class="bg-white rounded border border-slate-200 overflow-hidden text-sm">
        <table class="min-w-full">
            <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase text-slate-500">
                <tr>
                    <th class="px-3 py-2 text-left">ID</th>
                    <th class="px-3 py-2 text-left">Expiración (meses)</th>
                    <th class="px-3 py-2 text-left">Gracia cancelación (min)</th>
                    <th class="px-3 py-2 text-left">Modo penalidad</th>
                    <th class="px-3 py-2 text-left">Valor penalidad</th>
                    <th class="px-3 py-2 text-center">Activa</th>
                    <th class="px-3 py-2 text-right">Creada</th>
                    <th class="px-3 py-2 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($configs as $config)
                    <tr class="border-b border-slate-100 hover:bg-slate-50">
                        <td class="px-3 py-2 align-top text-sm">{{ $config->id }}</td>
                        <td class="px-3 py-2 align-top text-sm">{{ $config->expiration_months }}</td>
                        <td class="px-3 py-2 align-top text-sm">{{ $config->cancel_grace_minutes }}</td>
                        <td class="px-3 py-2 align-top text-sm">{{ $config->penalty_mode }}</td>
                        <td class="px-3 py-2 align-top text-sm">{{ $config->penalty_value }}</td>
                        <td class="px-3 py-2 align-top text-center">
                            @if ($config->is_active)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-xs">Activa</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 text-xs">Inactiva</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 align-top text-right text-xs text-slate-500">{{ $config->created_at }}</td>
                        <td class="px-3 py-2 align-top text-right">
                            <a href="{{ route('admin.credit-config.edit', $config) }}" class="text-xs text-slate-700 hover:underline">Editar</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-3 py-6 text-center text-slate-500 text-sm">No hay configuraciones registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $configs->links() }}
    </div>
@endcomponent
