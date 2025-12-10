<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Money Landing') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 flex items-center justify-center" x-data>
    <div class="w-full max-w-md bg-white shadow-xl rounded-2xl border border-slate-200 p-6">
        <div class="text-center space-y-1 mb-6">
            <div class="text-xl font-semibold text-slate-900">Money Landing</div>
            <div class="text-sm text-slate-500">Sistema de gestión de préstamos</div>
        </div>
        <div class="flex justify-end mb-4">
            <button @click="$store.theme.toggle()" class="px-3 py-2 rounded-lg border border-slate-200 text-sm hover:border-sky-300 hover:text-sky-600">
                <span x-show="$store.theme.current === 'light'">🌙</span>
                <span x-show="$store.theme.current === 'dark'">☀️</span>
            </button>
        </div>
        @yield('content')
    </div>
    @livewireScripts
</body>

</html>
