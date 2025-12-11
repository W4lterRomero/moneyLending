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

<body class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 flex items-center justify-center" x-data>
    <div class="w-full max-w-md bg-white shadow-xl rounded-2xl border border-slate-200 p-6">
        <div class="text-center space-y-1 mb-6">
            <div class="text-xl font-semibold text-slate-900">{{ $appTitle }}</div>
            <div class="text-sm text-slate-500">Sistema de gestión de préstamos</div>
        </div>
        <div class="flex justify-end mb-4">
            <button x-data="{ initialTheme: window.__theme ?? 'light' }" @click="$store.theme.toggle()" class="px-3 py-2 rounded-lg border border-slate-200 text-sm hover:border-sky-300 hover:text-sky-600">
                <x-icon x-cloak x-show="($store.theme?.current ?? initialTheme) === 'light'" name="sun" class="w-4 h-4 transition" />
                <x-icon x-cloak x-show="($store.theme?.current ?? initialTheme) === 'dark'" name="moon" class="w-4 h-4 transition" />
            </button>
        </div>
        @yield('content')
    </div>
    @livewireScripts
</body>

</html>
