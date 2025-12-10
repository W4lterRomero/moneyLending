@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="text-2xl font-semibold text-slate-900">Configuración del negocio</h1>
        <p class="text-sm text-slate-500">Moneda, tasas por defecto y plantillas.</p>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4">
        <form method="POST" action="{{ route('settings.business.update') }}" class="space-y-4">
            @csrf
            @method('PUT')
            <div class="grid md:grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm text-slate-600">Nombre</label>
                    <input type="text" name="business_name" value="{{ old('business_name', $settings->business_name ?? '') }}"
                        class="w-full px-3 py-2 rounded-lg border border-slate-200" />
                </div>
                <div>
                    <label class="block text-sm text-slate-600">RUC/ID</label>
                    <input type="text" name="legal_id" value="{{ old('legal_id', $settings->legal_id ?? '') }}"
                        class="w-full px-3 py-2 rounded-lg border border-slate-200" />
                </div>
                <div>
                    <label class="block text-sm text-slate-600">Moneda</label>
                    <input type="text" name="currency" value="{{ old('currency', $settings->currency ?? 'USD') }}"
                        class="w-full px-3 py-2 rounded-lg border border-slate-200" />
                </div>
                <div>
                    <label class="block text-sm text-slate-600">Zona horaria</label>
                    <input type="text" name="timezone" value="{{ old('timezone', $settings->timezone ?? 'UTC') }}"
                        class="w-full px-3 py-2 rounded-lg border border-slate-200" />
                </div>
                <div>
                    <label class="block text-sm text-slate-600">Interés por defecto (%)</label>
                    <input type="number" step="0.01" name="default_interest_rate"
                        value="{{ old('default_interest_rate', $settings->default_interest_rate ?? 12) }}"
                        class="w-full px-3 py-2 rounded-lg border border-slate-200" />
                </div>
                <div>
                    <label class="block text-sm text-slate-600">Penalización (%)</label>
                    <input type="number" step="0.01" name="default_penalty_rate"
                        value="{{ old('default_penalty_rate', $settings->default_penalty_rate ?? 0) }}"
                        class="w-full px-3 py-2 rounded-lg border border-slate-200" />
                </div>
            </div>
            <div>
                <label class="block text-sm text-slate-600">Plantillas de contrato (JSON)</label>
                <textarea name="contract_templates" rows="3" class="w-full px-3 py-2 rounded-lg border border-slate-200">{{ old('contract_templates', json_encode($settings->contract_templates ?? [])) }}</textarea>
            </div>
            <div class="flex justify-end">
                <button class="px-4 py-2 bg-slate-900 text-white rounded-lg shadow">Guardar</button>
            </div>
        </form>
    </div>
@endsection
