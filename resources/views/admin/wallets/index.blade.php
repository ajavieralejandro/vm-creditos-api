@php
    /** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator $wallets */
@endphp

@component('layouts.admin', ['title' => $title ?? 'Wallets de usuarios'])
    <form method="GET" action="{{ route('admin.wallets.index') }}" class="flex flex-wrap gap-2 items-end text-sm mb-4">
        <div>
            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Usuario (ID, external ID, email, nombre)</label>
            <input type="text" name="user" value="{{ $filters['user'] ?? '' }}" class="border rounded px-2 py-1 min-w-[220px]">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded bg-slate-900 text-white text-xs font-medium hover:bg-slate-800">Filtrar</button>
            <a href="{{ route('admin.wallets.index') }}" class="text-xs text-slate-600 hover:underline">Limpiar</a>
        </div>
    </form>

    <div class="bg-white rounded border border-slate-200 overflow-hidden text-sm">
        <table class="min-w-full">
            <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase text-slate-500">
                <tr>
                    <th class="px-3 py-2 text-left">ID</th>
                    <th class="px-3 py-2 text-left">Usuario</th>
                    <th class="px-3 py-2 text-left">External ID</th>
                    <th class="px-3 py-2 text-right">Saldo</th>
                    <th class="px-3 py-2 text-right">Movimientos</th>
                    <th class="px-3 py-2 text-left">Actualizada</th>
                    <th class="px-3 py-2 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($wallets as $wallet)
                    <tr class="border-b border-slate-100 hover:bg-slate-50">
                        <td class="px-3 py-2 align-top">#{{ $wallet->id }}</td>
                        <td class="px-3 py-2 align-top">
                            @if($wallet->user)
                                <span class="block">{{ $wallet->user->name }}</span>
                                <span class="block text-[11px] text-slate-500">{{ $wallet->user->email }}</span>
                            @else
                                <span class="text-[11px] text-slate-400">Sin usuario</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 align-top text-xs">{{ $wallet->external_user_id ?? '-' }}</td>
                        <td class="px-3 py-2 align-top text-right">{{ $wallet->balance }}</td>
                        <td class="px-3 py-2 align-top text-right">{{ $wallet->transactions_count }}</td>
                        <td class="px-3 py-2 align-top text-xs">{{ $wallet->updated_at?->format('Y-m-d H:i') }}</td>
                        <td class="px-3 py-2 align-top text-right">
                            <a href="{{ route('admin.wallets.show', $wallet) }}" class="text-xs text-slate-700 hover:underline">Ver</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-3 py-6 text-center text-slate-500 text-sm">No hay wallets registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $wallets->links() }}
    </div>
@endcomponent
