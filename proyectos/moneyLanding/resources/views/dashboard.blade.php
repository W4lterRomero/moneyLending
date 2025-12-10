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
            <div class="card">
                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <span>💰</span> Total prestado
                </div>
                <div class="text-2xl font-semibold text-slate-900">${{ number_format($metrics['total_lent'], 2) }}</div>
                <div class="text-xs text-slate-500">{{ $metrics['range'][0] }} - {{ $metrics['range'][1] }}</div>
            </div>
            <div class="card">
                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <span>📥</span> Total cobrado
                </div>
                <div class="text-2xl font-semibold text-slate-900">${{ number_format($metrics['total_collected'], 2) }}</div>
                <div class="text-xs text-slate-500">{{ $metrics['range'][0] }} - {{ $metrics['range'][1] }}</div>
            </div>
            <div class="card">
                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <span>📂</span> Préstamos activos
                </div>
                <div class="text-2xl font-semibold text-slate-900">{{ $metrics['active_loans'] }}</div>
            </div>
            <div class="card">
                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <span>⚠️</span> Tasa morosidad
                </div>
                <div class="text-2xl font-semibold text-amber-600">{{ $metrics['delinquency_rate'] }}%</div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-4">
            <div class="lg:col-span-2 card">
                <div class="flex items-center justify-between mb-3">
                    <div class="font-semibold text-slate-800">Ingresos vs tiempo</div>
                </div>
                <canvas id="incomeChart" data-range="{{ $range }}" data-chart='@json($chartData)'></canvas>
            </div>
            <div class="card">
                <div class="font-semibold text-slate-800 mb-3">Cartera por estado</div>
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
