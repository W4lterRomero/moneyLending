<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="text-xs uppercase text-slate-500">Resumen General</div>
            <h1 class="text-2xl font-semibold text-slate-900">Dashboard</h1>
        </div>
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

    <div class="text-xs text-slate-500 flex items-center gap-2">
        <span>Última actualización:</span>
        <span class="font-semibold text-slate-700">{{ $refreshedAt }}</span>
    </div>
</div>
