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
                        class="w-full px-3 py-2 rounded-lg border border-slate-200" placeholder="Lending Money" />
                    <p class="text-xs text-slate-400 mt-1">Este nombre aparecerá en el título de la pestaña del navegador</p>
                </div>
                <div>
                    <label class="block text-sm text-slate-600">Moneda</label>
                    <select name="currency" class="w-full px-3 py-2 rounded-lg border border-slate-200">
                        <option value="USD" @selected(old('currency', $settings->currency ?? 'USD') === 'USD')>USD ($)</option>
                        <option value="EUR" @selected(old('currency', $settings->currency ?? 'USD') === 'EUR')>EUR (€)</option>
                        <option value="MXN" @selected(old('currency', $settings->currency ?? 'USD') === 'MXN')>MXN ($)</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end">
                <button class="px-4 py-2 bg-slate-900 text-white rounded-lg shadow hover:bg-slate-800">Guardar cambios</button>
            </div>
        </form>
    </div>
@endsection
