@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="text-2xl font-semibold text-slate-900">Configuración</h1>
        <p class="text-sm text-slate-500">Personaliza tu sistema de préstamos</p>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4">
        <form method="POST" action="{{ route('settings.business.update') }}" class="space-y-4">
            @csrf
            @method('PUT')
            <div class="grid md:grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm text-slate-600">Nombre de la página web</label>
                    <input type="text" name="business_name" value="{{ old('business_name', $settings->business_name ?? 'Lending Money') }}"
                        class="w-full px-3 py-2 rounded-lg border border-slate-200 @error('business_name') border-red-500 @enderror" placeholder="Lending Money" />
                    <p class="text-xs text-slate-400 mt-1">Este nombre aparecerá en el título de la pestaña del navegador</p>
                    @error('business_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm text-slate-600">Moneda</label>
                    <select name="currency" class="w-full px-3 py-2 rounded-lg border border-slate-200 @error('currency') border-red-500 @enderror">
                        <option value="USD" @selected(old('currency', $settings->currency ?? 'USD') === 'USD')>USD ($)</option>
                        <option value="EUR" @selected(old('currency', $settings->currency ?? 'USD') === 'EUR')>EUR (€)</option>
                        <option value="MXN" @selected(old('currency', $settings->currency ?? 'USD') === 'MXN')>MXN ($)</option>
                    </select>
                    @error('currency') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="flex justify-end">
                <button class="px-4 py-2 bg-slate-900 text-white rounded-lg shadow hover:bg-slate-800">Guardar cambios</button>
            </div>
        </form>
    </div>

    <div class="mt-6 bg-white border border-slate-200 rounded-xl shadow-sm p-4">
        <h2 class="text-lg font-semibold text-slate-900 mb-2">Copia de Seguridad</h2>
        <p class="text-sm text-slate-500 mb-4">Descarga una copia completa de tus datos (clientes, préstamos, pagos) en formato JSON.</p>
        
        <a href="{{ route('settings.backup') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-lg shadow-sm hover:bg-slate-50">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Descargar Backup
        </a>
    </div>
@endsection
