<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="text-xs uppercase text-slate-500">Resumen General</div>
            <h1 class="text-2xl font-semibold text-slate-900">Dashboard</h1>
        </div>
        <button wire:click="refresh" wire:loading.attr="disabled" class="flex items-center gap-2 px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm font-medium text-slate-600 hover:text-sky-600 hover:border-sky-200 transition-colors shadow-sm">
            <svg wire:loading.class="animate-spin" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            <span wire:loading.remove>Actualizar</span>
            <span wire:loading>Actualizando...</span>
        </button>
    </div>

    {{-- KPIs Principales --}}
    <div class="grid md:grid-cols-4 gap-3">
        <div class="card border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 1.343-3 3m6 0c0-1.657-1.343-3-3-3m0 0V4m0 4a3 3 0 110 6m-3 0a3 3 0 106 0m-6 0H7m11 0h-4m-4 0H7m10 0a5 5 0 11-10 0"/></svg>
                    <span>Total Prestado</span>
                </div>
                <span class="text-[10px] px-2 py-1 rounded-full bg-sky-50 text-sky-700">USD</span>
            </div>
            <div class="text-2xl font-semibold text-slate-900 mt-2">${{ number_format($metrics['total_lent'] ?? 0, 2) }}</div>
        </div>

        <div class="card border border-slate-200/80 shadow-sm">
            <div class="flex items-center gap-2 text-xs text-slate-500">
                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h5l2-6 4 12 2-6h5"/></svg>
                <span>Total Cobrado</span>
            </div>
            <div class="text-2xl font-semibold text-slate-900 mt-2">${{ number_format($metrics['total_collected'] ?? 0, 2) }}</div>
        </div>

        <div class="card border border-slate-200/80 shadow-sm">
            <div class="flex items-center gap-2 text-xs text-slate-500">
                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Pendiente de Cobro</span>
            </div>
            <div class="text-2xl font-semibold text-amber-600 mt-2">${{ number_format(($metrics['total_lent'] ?? 0) - ($metrics['total_collected'] ?? 0), 2) }}</div>
        </div>

        <div class="card border border-slate-200/80 shadow-sm">
            <div class="flex items-center gap-2 text-xs text-slate-500">
                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h10M4 18h6"/></svg>
                <span>Préstamos Activos</span>
            </div>
            <div class="text-2xl font-semibold text-slate-900 mt-2">{{ $metrics['active_loans'] ?? 0 }}</div>
        </div>
    </div>

    {{-- Barra de Progreso Simple --}}
    <div class="card border border-slate-200/80 shadow-sm">
        <div class="text-sm font-semibold text-slate-800 mb-3">Progreso de Cobranza</div>
        @php
            $lent = $metrics['total_lent'] ?? 1;
            $collected = $metrics['total_collected'] ?? 0;
            $percentage = $lent > 0 ? ($collected / $lent) * 100 : 0;
        @endphp
        <div class="w-full bg-slate-200 rounded-full h-6 overflow-hidden">
            <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 h-6 rounded-full flex items-center justify-center text-white text-xs font-semibold"
                 style="width: {{ $percentage }}%">
                {{ number_format($percentage, 1) }}%
            </div>
        </div>
        <div class="mt-2 text-xs text-slate-500">
            Has cobrado <span class="font-semibold text-emerald-600">${{ number_format($collected, 2) }}</span> de <span class="font-semibold">${{ number_format($lent, 2) }}</span>
        </div>
    </div>

    {{-- Top Profesiones y Empresas --}}
    <div class="grid md:grid-cols-2 gap-4">
        <div class="card border border-slate-200/80 shadow-sm">
            <div class="text-sm font-semibold text-slate-800 mb-3">Top Profesiones</div>
            @if(!empty($metrics['top_occupations']) && count($metrics['top_occupations']) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-slate-500 uppercase bg-slate-50">
                            <tr>
                                <th class="px-4 py-2">Profesión</th>
                                <th class="px-4 py-2 text-right">Préstamos</th>
                                <th class="px-4 py-2 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($metrics['top_occupations'] as $occupation)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-2 font-medium text-slate-900">{{ $occupation->name }}</td>
                                    <td class="px-4 py-2 text-right text-slate-600">{{ $occupation->count }}</td>
                                    <td class="px-4 py-2 text-right font-semibold text-sky-600">${{ number_format($occupation->total_amount, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-8 text-slate-400">
                    <svg class="w-10 h-10 mb-2 opacity-50" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                    <p class="text-sm">No hay datos de profesiones en este periodo.</p>
                </div>
            @endif
        </div>

        <div class="card border border-slate-200/80 shadow-sm">
            <div class="text-sm font-semibold text-slate-800 mb-3">Top Empresas</div>
            @if(!empty($metrics['top_companies']) && count($metrics['top_companies']) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-slate-500 uppercase bg-slate-50">
                            <tr>
                                <th class="px-4 py-2">Empresa</th>
                                <th class="px-4 py-2 text-right">Préstamos</th>
                                <th class="px-4 py-2 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($metrics['top_companies'] as $company)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-2 font-medium text-slate-900">{{ $company->name }}</td>
                                    <td class="px-4 py-2 text-right text-slate-600">{{ $company->count }}</td>
                                    <td class="px-4 py-2 text-right font-semibold text-sky-600">${{ number_format($company->total_amount, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-8 text-slate-400">
                    <svg class="w-10 h-10 mb-2 opacity-50" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <p class="text-sm">No hay datos de empresas en este periodo.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="text-xs text-slate-500 flex items-center gap-2">
        <span>Última actualización:</span>
        <span class="font-semibold text-slate-700">{{ $refreshedAt }}</span>
    </div>
</div>
