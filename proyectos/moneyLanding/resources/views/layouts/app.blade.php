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
            const theme = stored === 'dark' ? 'dark' : 'light';
            // Always start by removing dark class
            document.documentElement.classList.remove('dark');
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            }
            window.__theme = theme;
            console.log('[Theme] Applied:', theme, 'localStorage:', stored);
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
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                    Pagos
                </a>
                <a href="{{ route('finance.index') }}" class="sidebar-link {{ request()->is('finance*') ? 'sidebar-link--active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Finanzas
                </a>
                <a href="{{ route('financings.index') }}" class="sidebar-link {{ request()->is('financings*') ? 'sidebar-link--active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Financiamientos
                </a>
                <a href="{{ route('settings.business') }}" class="sidebar-link {{ request()->is('settings*') ? 'sidebar-link--active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Configuración
                </a>
            </nav>
            <div class="text-xs text-slate-500 panel-apple p-3 text-center">⌘ / Ctrl + K — búsqueda global</div>
        </aside>

        <main class="flex-1">
            <header class="sticky top-0 z-30 topbar-apple backdrop-blur">
                <div class="px-4 md:px-6 h-14 flex items-center justify-between gap-3">
                    {{-- Left side: hamburger + title --}}
                    <div class="flex items-center gap-3">
                        <button class="md:hidden p-2 rounded-lg hamburger-apple" aria-label="Abrir menú" @click="mobileNav = true">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        <span class="hidden sm:block text-sm text-slate-500 dark:text-slate-400">Sistema de Gestión de Préstamos</span>
                    </div>
                    
                    {{-- Right side: search + theme + user --}}
                    <div class="flex items-center gap-2">
                        {{-- Search button (mobile) --}}
                        <button x-data @click="window.dispatchEvent(new CustomEvent('open-search'))" 
                            class="p-2 rounded-lg text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
                            aria-label="Buscar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7 7 0 105 5a7 7 0 0011.65 11.65z"/>
                            </svg>
                        </button>
                        
                        {{-- Theme toggle --}}
                        <button x-data="{ initialTheme: window.__theme ?? 'light' }" @click="$store.theme.toggle()" 
                            aria-label="Cambiar tema" 
                            class="p-2 rounded-lg text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                            <svg x-cloak x-show="($store.theme?.current ?? initialTheme) === 'light'" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <svg x-cloak x-show="($store.theme?.current ?? initialTheme) === 'dark'" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                            </svg>
                        </button>
                        
                        {{-- User dropdown --}}
                        @auth
                            <div class="relative" x-data="{ openUser: false }">
                                <button type="button" @click="openUser = !openUser" 
                                    class="flex items-center gap-2 px-2 py-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-sky-500 text-white text-xs font-bold">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </span>
                                    <span class="hidden sm:inline text-sm text-slate-700 dark:text-slate-300">{{ auth()->user()->name }}</span>
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Préstamos
                </a>
                <a href="{{ route('payments.index') }}" @click="mobileNav = false" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-slate-100 {{ request()->is('payments*') ? 'bg-slate-100 text-sky-600' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                    Pagos
                </a>
                <a href="{{ route('finance.index') }}" @click="mobileNav = false" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-slate-100 {{ request()->is('finance*') ? 'bg-slate-100 text-sky-600' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Finanzas
                </a>
                <a href="{{ route('settings.business') }}" @click="mobileNav = false" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-slate-100 {{ request()->is('settings*') ? 'bg-slate-100 text-sky-600' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Configuración
                </a>
            </nav>
        </aside>
    </div>

    <livewire:global-search />

    @livewireScripts
    @stack('scripts')
</body>

</html>
