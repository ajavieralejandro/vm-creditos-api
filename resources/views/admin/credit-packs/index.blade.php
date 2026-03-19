@php
    /** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator $packs */
@endphp

@component('layouts.admin', ['title' => $title ?? 'Packs de créditos'])
    <div class="flex items-center justify-between mb-4">
        <form method="GET" action="{{ route('admin.credit-packs.index') }}" class="flex flex-wrap gap-2 items-center text-sm">
            <select name="status" class="border rounded px-2 py-1">
                <option value="">Todos</option>
                <option value="active" @selected(($filters['status'] ?? '') === 'active')>Activos</option>
                <option value="inactive" @selected(($filters['status'] ?? '') === 'inactive')>Inactivos</option>
            </select>
            <input
                type="text"
                name="search"
                placeholder="Buscar por nombre o código"
                value="{{ $filters['search'] ?? '' }}"
                class="border rounded px-2 py-1 min-w-[220px]"
            >
            <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded bg-slate-900 text-white text-xs font-medium hover:bg-slate-800">
                Filtrar
            </button>
        </form>

        <a href="{{ route('admin.credit-packs.create') }}" class="inline-flex items-center px-4 py-2 rounded bg-slate-900 text-white text-sm font-medium hover:bg-slate-800">
            Nuevo pack
        </a>
    </div>

    <div class="bg-white rounded border border-slate-200 overflow-hidden text-sm">
        <table class="min-w-full">
            <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase text-slate-500">
                <tr>
                    <th class="px-3 py-2 text-left">Código</th>
                    <th class="px-3 py-2 text-left">Nombre</th>
                    <th class="px-3 py-2 text-right">Créditos</th>
                    <th class="px-3 py-2 text-right">Precio ARS</th>
                    <th class="px-3 py-2 text-center">Activo</th>
                    <th class="px-3 py-2 text-right">Orden</th>
                    <th class="px-3 py-2 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($packs as $pack)
                    <tr class="border-b border-slate-100 hover:bg-slate-50">
                        <td class="px-3 py-2 align-top">{{ $pack->code }}</td>
                        <td class="px-3 py-2 align-top">{{ $pack->name }}</td>
                        <td class="px-3 py-2 align-top text-right">{{ $pack->credits }}</td>
                        <td class="px-3 py-2 align-top text-right">${{ number_format($pack->price_ars, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 align-top text-center">
                            @if ($pack->is_active)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-xs">Activo</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 text-xs">Inactivo</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 align-top text-right">{{ $pack->sort_order }}</td>
                        <td class="px-3 py-2 align-top text-right space-x-2">
                            <a href="{{ route('admin.credit-packs.edit', $pack) }}" class="text-xs text-slate-700 hover:underline">Editar</a>

                            <form action="{{ route('admin.credit-packs.toggle', $pack) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-xs text-slate-700 hover:underline">
                                    {{ $pack->is_active ? 'Desactivar' : 'Activar' }}
                                </button>
                            </form>

                            <form action="{{ route('admin.credit-packs.destroy', $pack) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este pack?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-700 hover:underline">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-3 py-6 text-center text-slate-500 text-sm">No hay packs de créditos configurados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $packs->links() }}
    </div>
@endcomponent
