<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php($appTitle = $appName ?? config('app.name', 'Lending Money'))
    <title>{{ $appTitle }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        (() => {
            const stored = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = stored ?? (prefersDark ? 'dark' : 'light');
            document.documentElement.classList.toggle('dark', theme === 'dark');
            window.__theme = theme;
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="text-slate-800" x-data="{ mobileNav: false }">
    <div class="min-h-screen flex">
        <aside class="w-72 hidden md:flex flex-col p-4 gap-4 sidebar">
            <div class="panel-apple p-4">
                <div class="text-xl font-semibold text-slate-900 flex items-center gap-3">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-sky-400 to-sky-600 text-white shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    <span>{{ $appTitle }}</span>
                </div>
                <div class="text-xs text-slate-500 mt-1">Gestión de préstamos</div>
            </div>
            <nav class="panel-apple flex-1 p-3 space-y-1 text-sm">
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'sidebar-link--active' : '' }}">
                    <x-icon name="chart-bar" class="w-4 h-4" /> Dashboard
                </a>
                <a href="{{ route('clients.index') }}" class="sidebar-link {{ request()->is('clients*') ? 'sidebar-link--active' : '' }}">
                    <x-icon name="users" class="w-4 h-4" /> Clientes
                </a>
                <a href="{{ route('loans.index') }}" class="sidebar-link {{ request()->is('loans*') ? 'sidebar-link--active' : '' }}">
                    <x-icon name="credit-card" class="w-4 h-4" /> Préstamos
                </a>
                <a href="{{ route('payments.index') }}" class="sidebar-link {{ request()->is('payments*') ? 'sidebar-link--active' : '' }}">
                    <x-icon name="banknotes" class="w-4 h-4" /> Pagos
                </a>
                <a href="{{ route('settings.business') }}" class="sidebar-link {{ request()->is('settings*') ? 'sidebar-link--active' : '' }}">
                    <x-icon name="cog" class="w-4 h-4" /> Configuración
                </a>
            </nav>
            <div class="text-xs text-slate-500 panel-apple p-3 text-center">⌘ / Ctrl + K — búsqueda global</div>
        </aside>

        <main class="flex-1">
            <header class="sticky top-0 z-30 topbar-apple backdrop-blur">
                <div class="px-4 md:px-6 py-3 sm:h-16 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <button class="md:hidden px-3 py-2 rounded-lg text-sm hamburger-apple" aria-label="Abrir menú" @click="mobileNav = true">
                            <x-icon name="menu" class="w-5 h-5" />
                        </button>
                        <div class="text-sm text-slate-500">Sistema de Gestión de Préstamos</div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto sm:justify-end">
                        <button x-data @click="$dispatch('open-search')"
                            class="btn-outline-apple text-sm hidden sm:inline-flex">
                            Búsqueda (⌘/Ctrl + K)
                        </button>
                        <button x-data="{ initialTheme: window.__theme ?? 'light' }" @click="$store.theme.toggle()" aria-label="Cambiar tema" class="btn-outline-apple px-3 py-2 flex items-center gap-2 w-12 sm:w-auto justify-center">
                            <x-icon x-cloak x-bind:class="(($store.theme?.current ?? initialTheme) === 'light') ? 'w-4 h-4 transition block' : 'w-4 h-4 transition hidden'" name="sun" />
                            <x-icon x-cloak x-bind:class="(($store.theme?.current ?? initialTheme) === 'dark') ? 'w-4 h-4 transition block' : 'w-4 h-4 transition hidden'" name="moon" />
                            <span class="sr-only">Cambiar tema</span>
                        </button>
                        @auth
                            <div class="relative w-full sm:w-auto" x-data="{ openUser: false }">
                                <button type="button" @click="openUser = !openUser" class="user-chip w-full sm:w-auto justify-between sm:justify-start gap-2">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-sky-500 text-white text-xs font-bold">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        </span>
                                        <span class="hidden sm:inline">{{ auth()->user()->name }}</span>
                                    </div>
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6" />
                                    </svg>
                                </button>
                                <div x-show="openUser" x-cloak @click.away="openUser = false" x-transition
                                    class="absolute right-0 mt-2 w-48 panel-apple p-2 shadow-lg border border-slate-200 z-50">
                                    <div class="px-3 py-2 text-sm text-slate-500">{{ auth()->user()->email }}</div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-3 py-2 rounded-lg hover:bg-slate-100 text-sm text-slate-800">
                                            Cerrar sesión
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endauth
                    </div>
                </div>
            </header>

            <div class="px-4 md:px-6 py-6 space-y-4 max-w-7xl mx-auto">
                @if (session('success'))
                    <div class="tag tag-success w-full">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="tag tag-danger w-full">
                        {{ session('error') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="tag tag-warning w-full">
                        {{ __('Por favor corrige los errores marcados en el formulario.') }}
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Mobile drawer -->
    <div class="fixed inset-0 z-50 md:hidden" x-show="mobileNav" x-transition.opacity>
        <div class="absolute inset-0 bg-black/40" @click="mobileNav = false"></div>
        <aside class="absolute top-0 left-0 w-full max-w-xs h-full bg-white shadow-xl p-4 space-y-4 panel-apple" x-transition
            x-trap.inert.noscroll="mobileNav">
            <div class="flex items-center justify-between">
                <div class="text-lg font-semibold">Menú</div>
                <button class="text-slate-500" aria-label="Cerrar menú" @click="mobileNav = false">
                    <x-icon name="close" class="w-5 h-5" />
                </button>
            </div>
            <nav class="space-y-1 text-sm">
                <a href="{{ route('dashboard') }}" @click="mobileNav = false" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-slate-100 {{ request()->routeIs('dashboard') ? 'bg-slate-100 text-sky-600' : '' }}">
                    <x-icon name="chart-bar" class="w-4 h-4" /> Dashboard
                </a>
                <a href="{{ route('clients.index') }}" @click="mobileNav = false" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-slate-100 {{ request()->is('clients*') ? 'bg-slate-100 text-sky-600' : '' }}">
                    <x-icon name="users" class="w-4 h-4" /> Clientes
                </a>
                <a href="{{ route('loans.index') }}" @click="mobileNav = false" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-slate-100 {{ request()->is('loans*') ? 'bg-slate-100 text-sky-600' : '' }}">
                    <x-icon name="credit-card" class="w-4 h-4" /> Préstamos
                </a>
                <a href="{{ route('payments.index') }}" @click="mobileNav = false" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-slate-100 {{ request()->is('payments*') ? 'bg-slate-100 text-sky-600' : '' }}">
                    <x-icon name="banknotes" class="w-4 h-4" /> Pagos
                </a>
                <a href="{{ route('settings.business') }}" @click="mobileNav = false" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-slate-100 {{ request()->is('settings*') ? 'bg-slate-100 text-sky-600' : '' }}">
                    <x-icon name="cog" class="w-4 h-4" /> Configuración
                </a>
            </nav>
        </aside>
    </div>

    <livewire:global-search />
    @livewireScripts
    @stack('scripts')
</body>

</html>
