<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="text-xs uppercase text-slate-500">Visión general</div>
            <h1 class="text-2xl font-semibold text-slate-900">Dashboard</h1>
        </div>
        <form wire:submit.prevent="filter" class="flex flex-col md:flex-row md:items-center gap-2">
            <label class="text-xs text-slate-500" for="range">Rango</label>
            <select id="range" wire:model.defer="range" class="px-3 py-2 rounded-lg border border-slate-200 text-sm" aria-label="Seleccionar rango de fechas">
                <option value="today">Hoy</option>
                <option value="week">Semana</option>
                <option value="month">Mes</option>
                <option value="year">Año</option>
            </select>
            <label class="sr-only" for="start">Desde</label>
            <input id="start" type="date" wire:model.defer="start" class="px-3 py-2 rounded-lg border border-slate-200 text-sm"
                aria-label="Fecha inicio">
            <label class="sr-only" for="end">Hasta</label>
            <input id="end" type="date" wire:model.defer="end" class="px-3 py-2 rounded-lg border border-slate-200 text-sm"
                aria-label="Fecha fin">
            <button class="px-4 py-2 bg-sky-500 text-white rounded-lg">Filtrar</button>
        </form>
    </div>

    <div class="grid md:grid-cols-4 gap-3">
        <div class="card border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 1.343-3 3m6 0c0-1.657-1.343-3-3-3m0 0V4m0 4a3 3 0 110 6m-3 0a3 3 0 106 0m-6 0H7m11 0h-4m-4 0H7m10 0a5 5 0 11-10 0"/></svg>
                    <span> Total prestado</span>
                </div>
                <span class="text-[10px] px-2 py-1 rounded-full bg-sky-50 text-sky-700">USD</span>
            </div>
            <div class="text-2xl font-semibold text-slate-900 mt-2">${{ number_format($metrics['total_lent'] ?? 0, 2) }}</div>
            <div class="text-xs text-slate-500">{{ $metrics['range'][0] ?? '' }} - {{ $metrics['range'][1] ?? '' }}</div>
        </div>
        <div class="card border border-slate-200/80 shadow-sm">
            <div class="flex items-center gap-2 text-xs text-slate-500">
                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h5l2-6 4 12 2-6h5"/></svg>
                <span>Total cobrado</span>
            </div>
            <div class="text-2xl font-semibold text-slate-900 mt-2">${{ number_format($metrics['total_collected'] ?? 0, 2) }}</div>
            <div class="text-xs text-slate-500">{{ $metrics['range'][0] ?? '' }} - {{ $metrics['range'][1] ?? '' }}</div>
        </div>
        <div class="card border border-slate-200/80 shadow-sm">
            <div class="flex items-center gap-2 text-xs text-slate-500">
                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h10M4 18h6"/></svg>
                <span>Préstamos activos</span>
            </div>
            <div class="text-2xl font-semibold text-slate-900 mt-2">{{ $metrics['active_loans'] ?? 0 }}</div>
        </div>
        <div class="card border border-slate-200/80 shadow-sm">
            <div class="flex items-center gap-2 text-xs text-slate-500">
                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M3.1 16.2L12 3l8.9 13.2A1 1 0 0119.9 18H4.1a1 1 0 01-.99-1.8z"/></svg>
                <span>Tasa morosidad</span>
            </div>
            <div class="text-2xl font-semibold text-amber-600 mt-2">{{ $metrics['delinquency_rate'] ?? 0 }}%</div>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2 card border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="font-semibold text-slate-800">Ingresos vs tiempo</div>
                <span class="text-xs text-slate-500">Datos reales</span>
            </div>
            <canvas id="incomeChart" wire:ignore data-range="{{ $range }}" data-chart='@json($chartData)'></canvas>
        </div>
        <div class="card border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="font-semibold text-slate-800">Cartera por estado</div>
                <span class="text-xs text-slate-500">Live</span>
            </div>
            <canvas id="statusChart" wire:ignore data-status='@json($statusCounts)'></canvas>
        </div>
    </div>
</div>
