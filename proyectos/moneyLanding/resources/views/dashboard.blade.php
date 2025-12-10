@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="text-xs uppercase text-slate-500">Visión general</div>
                <h1 class="text-2xl font-semibold text-slate-900">Dashboard</h1>
            </div>
            <form method="GET" class="flex items-center gap-2">
                <label class="text-xs text-slate-500" for="range">Rango</label>
                <select id="range" name="range" class="px-3 py-2 rounded-lg border border-slate-200 text-sm" aria-label="Seleccionar rango de fechas">
                    <option value="today" @selected($range === 'today')>Hoy</option>
                    <option value="week" @selected($range === 'week')>Semana</option>
                    <option value="month" @selected($range === 'month')>Mes</option>
                    <option value="year" @selected($range === 'year')>Año</option>
                </select>
                <label class="sr-only" for="start">Desde</label>
                <input id="start" type="date" name="start" class="px-3 py-2 rounded-lg border border-slate-200 text-sm"
                    value="{{ request('start') }}" aria-label="Fecha inicio">
                <label class="sr-only" for="end">Hasta</label>
                <input id="end" type="date" name="end" class="px-3 py-2 rounded-lg border border-slate-200 text-sm"
                    value="{{ request('end') }}" aria-label="Fecha fin">
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
                <div class="text-2xl font-semibold text-slate-900 mt-2">${{ number_format($metrics['total_lent'], 2) }}</div>
                <div class="text-xs text-slate-500">{{ $metrics['range'][0] }} - {{ $metrics['range'][1] }}</div>
            </div>
            <div class="card border border-slate-200/80 shadow-sm">
                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h5l2-6 4 12 2-6h5"/></svg>
                    <span>Total cobrado</span>
                </div>
                <div class="text-2xl font-semibold text-slate-900 mt-2">${{ number_format($metrics['total_collected'], 2) }}</div>
                <div class="text-xs text-slate-500">{{ $metrics['range'][0] }} - {{ $metrics['range'][1] }}</div>
            </div>
            <div class="card border border-slate-200/80 shadow-sm">
                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h10M4 18h6"/></svg>
                    <span>Préstamos activos</span>
                </div>
                <div class="text-2xl font-semibold text-slate-900 mt-2">{{ $metrics['active_loans'] }}</div>
            </div>
            <div class="card border border-slate-200/80 shadow-sm">
                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M3.1 16.2L12 3l8.9 13.2A1 1 0 0119.9 18H4.1a1 1 0 01-.99-1.8z"/></svg>
                    <span>Tasa morosidad</span>
                </div>
                <div class="text-2xl font-semibold text-amber-600 mt-2">{{ $metrics['delinquency_rate'] }}%</div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-4">
            <div class="lg:col-span-2 card border border-slate-200/80 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <div class="font-semibold text-slate-800">Ingresos vs tiempo</div>
                    <span class="text-xs text-slate-500">Datos reales</span>
                </div>
                <canvas id="incomeChart" data-range="{{ $range }}" data-chart='@json($chartData)'></canvas>
            </div>
            <div class="card border border-slate-200/80 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <div class="font-semibold text-slate-800">Cartera por estado</div>
                    <span class="text-xs text-slate-500">Live</span>
                </div>
                <canvas id="statusChart" data-status='@json($statusCounts)'></canvas>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-4">
            <livewire:loans.loan-calendar />
            <livewire:loans.loan-kanban />
        </div>

        <livewire:loans.loan-table />
        <livewire:loans.loan-calculator />
    </div>
@endsection

@push('scripts')
    <script type="module">
        const renderCharts = () => {
            const incomeCtx = document.getElementById('incomeChart');
            if (incomeCtx && window.Chart) {
                const chartDataset = JSON.parse(incomeCtx.dataset.chart);
                new window.Chart(incomeCtx, {
                    type: 'line',
                    data: {
                        labels: chartDataset.labels,
                        datasets: [
                            {
                                label: 'Prestado',
                                data: chartDataset.lent,
                                borderColor: '#4dabf7',
                                backgroundColor: '#4dabf733',
                                tension: 0.35,
                            },
                            {
                                label: 'Cobrado',
                                data: chartDataset.collected,
                                borderColor: '#10b981',
                                backgroundColor: '#10b98133',
                                tension: 0.35,
                            }
                        ]
                    },
                    options: { plugins: { legend: { display: true } } }
                });
            }

            const statusCtx = document.getElementById('statusChart');
            if (statusCtx && window.Chart) {
                const statusData = JSON.parse(statusCtx.dataset.status);
                const values = Object.values(statusData);
                new window.Chart(statusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Activos', 'Morosos', 'Completados'],
                        datasets: [{
                            data: values,
                            backgroundColor: ['#4dabf7', '#f59e0b', '#10b981']
                        }]
                    },
                    options: { plugins: { legend: { position: 'bottom' } } }
                });
            }
        };

        if (window.Chart) {
            renderCharts();
        } else {
            document.addEventListener('charts:ready', renderCharts, { once: true });
        }
    </script>
@endpush
