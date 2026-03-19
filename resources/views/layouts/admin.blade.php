<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ trim($title ?? 'Admin') }} - {{ config('app.name', 'Laravel') }}</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-screen bg-slate-100 text-slate-900 flex">
    <aside class="w-64 bg-white border-r border-slate-200 hidden md:flex flex-col">
        <div class="px-4 py-4 border-b border-slate-200">
            <div class="text-lg font-semibold">{{ config('app.name', 'Laravel') }}</div>
            <div class="text-xs text-slate-500">Admin</div>
        </div>
        <nav class="flex-1 px-3 py-4 space-y-1 text-sm">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2 rounded hover:bg-slate-100 {{ request()->routeIs('admin.dashboard') ? 'bg-slate-100 font-semibold' : '' }}">
                Dashboard
            </a>
            <a href="{{ route('admin.credit-packs.index') }}" class="flex items-center px-3 py-2 rounded hover:bg-slate-100 {{ request()->routeIs('admin.credit-packs.*') ? 'bg-slate-100 font-semibold' : '' }}">
                Packs de créditos
            </a>
            <a href="{{ route('admin.credit-config.index') }}" class="flex items-center px-3 py-2 rounded hover:bg-slate-100 {{ request()->routeIs('admin.credit-config.*') ? 'bg-slate-100 font-semibold' : '' }}">
                Configuración créditos
            </a>
            <a href="{{ route('admin.credit-purchases.index') }}" class="flex items-center px-3 py-2 rounded hover:bg-slate-100 {{ request()->routeIs('admin.credit-purchases.*') ? 'bg-slate-100 font-semibold' : '' }}">
                Órdenes de compra
            </a>
            <a href="{{ route('admin.wallets.index') }}" class="flex items-center px-3 py-2 rounded hover:bg-slate-100 {{ request()->routeIs('admin.wallets.*') ? 'bg-slate-100 font-semibold' : '' }}">
                Wallets
            </a>
            <a href="{{ route('admin.webhooks.index') }}" class="flex items-center px-3 py-2 rounded hover:bg-slate-100 {{ request()->routeIs('admin.webhooks.*') ? 'bg-slate-100 font-semibold' : '' }}">
                Webhooks
            </a>
        </nav>
    </aside>

    <div class="flex-1 flex flex-col min-w-0">
        <header class="w-full bg-white border-b border-slate-200 px-4 py-3 flex items-center justify-between">
            <div>
                <h1 class="text-base font-semibold text-slate-900">{{ $title ?? 'Panel admin' }}</h1>
                @if (! empty($subtitle))
                    <p class="text-xs text-slate-500 mt-1">{{ $subtitle }}</p>
                @endif
            </div>
            <div class="text-xs text-slate-500 flex items-center gap-3">
                @auth
                    <span>{{ auth()->user()->email }}</span>
                @endauth
            </div>
        </header>

        <main class="flex-1 px-4 py-6 md:px-8 md:py-8 overflow-x-auto">
            @if (session('status'))
                <div class="mb-4 rounded border border-emerald-300 bg-emerald-50 text-emerald-800 text-sm px-4 py-2">
                    {{ session('status') }}
                </div>
            @endif

            {{ $slot ?? '' }}
        </main>
    </div>
</body>
</html>
