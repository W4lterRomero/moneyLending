<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Money Landing') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="text-slate-800" x-data="{ mobileNav: false }">
    <div class="min-h-screen flex">
        <aside class="w-72 hidden md:flex flex-col p-4 gap-4">
            <div class="panel-apple p-4">
                <div class="text-xl font-semibold text-slate-900 flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-sky-100 text-sky-600">
                        <x-icon name="chart-bar" class="w-5 h-5" />
                    </span>
                    Money Landing
                </div>
                <div class="text-xs text-slate-500 mt-1">Gestión de préstamos</div>
            </div>
            <nav class="panel-apple flex-1 p-3 space-y-1 text-sm">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-sky-50 {{ request()->routeIs('dashboard') ? 'bg-sky-50 text-sky-700' : '' }}">
                    <x-icon name="chart-bar" class="w-4 h-4" /> Dashboard
                </a>
                <a href="{{ route('clients.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-sky-50 {{ request()->is('clients*') ? 'bg-sky-50 text-sky-700' : '' }}">
                    <x-icon name="users" class="w-4 h-4" /> Clientes
                </a>
                <a href="{{ route('loans.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-sky-50 {{ request()->is('loans*') ? 'bg-sky-50 text-sky-700' : '' }}">
                    <x-icon name="credit-card" class="w-4 h-4" /> Préstamos
                </a>
                <a href="{{ route('payments.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-sky-50 {{ request()->is('payments*') ? 'bg-sky-50 text-sky-700' : '' }}">
                    <x-icon name="banknotes" class="w-4 h-4" /> Pagos
                </a>
                <a href="{{ route('reports.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-sky-50 {{ request()->is('reports*') ? 'bg-sky-50 text-sky-700' : '' }}">
                    <x-icon name="document-chart" class="w-4 h-4" /> Reportes
                </a>
                <a href="{{ route('settings.business') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-sky-50 {{ request()->is('settings*') ? 'bg-sky-50 text-sky-700' : '' }}">
                    <x-icon name="cog" class="w-4 h-4" /> Configuración
                </a>
            </nav>
            <div class="text-xs text-slate-500 panel-apple p-3 text-center">⌘ / Ctrl + K — búsqueda global</div>
        </aside>

        <main class="flex-1">
            <header class="sticky top-0 z-30 backdrop-blur bg-white/70 border-b border-slate-200/70">
                <div class="px-4 md:px-6 h-16 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <button class="md:hidden px-3 py-2 rounded-lg border border-slate-200 text-sm" aria-label="Abrir menú" @click="mobileNav = true">
                            <x-icon name="menu" class="w-5 h-5" />
                        </button>
                        <div class="text-sm text-slate-500">Sistema de Gestión de Préstamos</div>
                    </div>
                    <div class="flex items-center gap-3">
                        <button x-data @click="$dispatch('open-search')"
                            class="btn-outline-apple text-sm">
                            Búsqueda (⌘/Ctrl + K)
                        </button>
                        <button @click="$store.theme.toggle()" aria-label="Cambiar tema" class="btn-outline-apple px-3 py-2">
                            <x-icon x-show="$store.theme.current === 'light'" name="moon" class="w-5 h-5" />
                            <x-icon x-show="$store.theme.current === 'dark'" name="sun" class="w-5 h-5" />
                        </button>
                        @auth
                            <div class="text-sm text-slate-700">{{ auth()->user()->name }}</div>
                        @endauth
                    </div>
                </div>
            </header>

            <div class="px-4 md:px-6 py-6 space-y-4">
                @if (session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Mobile drawer -->
    <div class="fixed inset-0 z-50 md:hidden" x-show="mobileNav" x-transition.opacity>
        <div class="absolute inset-0 bg-black/40" @click="mobileNav = false"></div>
        <aside class="absolute top-0 left-0 w-72 h-full bg-white shadow-xl p-4 space-y-4 panel-apple" x-transition
            x-trap.inert.noscroll="mobileNav">
            <div class="flex items-center justify-between">
                <div class="text-lg font-semibold">Menú</div>
                <button class="text-slate-500" aria-label="Cerrar menú" @click="mobileNav = false">
                    <x-icon name="close" class="w-5 h-5" />
                </button>
            </div>
            <nav class="space-y-1 text-sm">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-slate-100 {{ request()->routeIs('dashboard') ? 'bg-slate-100 text-sky-600' : '' }}">
                    <span>📊</span> Dashboard
                </a>
                <a href="{{ route('clients.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-slate-100 {{ request()->is('clients*') ? 'bg-slate-100 text-sky-600' : '' }}">
                    <span>🧑</span> Clientes
                </a>
                <a href="{{ route('loans.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-slate-100 {{ request()->is('loans*') ? 'bg-slate-100 text-sky-600' : '' }}">
                    <span>💳</span> Préstamos
                </a>
                <a href="{{ route('payments.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-slate-100 {{ request()->is('payments*') ? 'bg-slate-100 text-sky-600' : '' }}">
                    <span>💵</span> Pagos
                </a>
                <a href="{{ route('reports.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-slate-100 {{ request()->is('reports*') ? 'bg-slate-100 text-sky-600' : '' }}">
                    <span>📑</span> Reportes
                </a>
                <a href="{{ route('settings.business') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-slate-100 {{ request()->is('settings*') ? 'bg-slate-100 text-sky-600' : '' }}">
                    <span>⚙️</span> Configuración
                </a>
            </nav>
        </aside>
    </div>

    <livewire:global-search />
    @livewireScripts
    @stack('scripts')
</body>

</html>
