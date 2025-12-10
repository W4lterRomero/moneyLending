<?php

namespace App\Livewire;

use App\Services\KpiAggregator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DashboardWidgets extends Component
{
    public string $range = 'month';
    public ?string $start = null;
    public ?string $end = null;
    public array $metrics = [];
    public array $chartData = [];
    public array $statusCounts = [];

    protected KpiAggregator $aggregator;

    public function boot(KpiAggregator $aggregator): void
    {
        $this->aggregator = $aggregator;
    }

    public function mount(string $range = 'month', ?string $start = null, ?string $end = null): void
    {
        $this->range = $range;
        $this->start = $start;
        $this->end = $end;
        $this->refreshData();
    }

    public function filter(): void
    {
        $this->refreshData();
    }

    public function render()
    {
        return view('livewire.dashboard-widgets');
    }

    protected function refreshData(): void
    {
        $this->metrics = $this->aggregator->metrics($this->range, [
            'start' => $this->start,
            'end' => $this->end,
        ]);

        $this->chartData = $this->buildChartData();
        $this->statusCounts = $this->statusCounts();

        // Notifica al front que debe redibujar los charts
        $this->dispatch('charts-refresh');
    }

    protected function buildChartData(): array
    {
        $months = collect(range(0, 5))
            ->map(fn ($i) => Carbon::now()->subMonths($i)->startOfMonth())
            ->sort()
            ->values();

        $labels = $months->map(fn ($m) => $m->format('M Y'));

        $lent = $months->map(function ($month) {
            return DB::table('loans')
                ->whereBetween('start_date', [$month->toDateString(), $month->copy()->endOfMonth()->toDateString()])
                ->sum('principal');
        });

        $collected = $months->map(function ($month) {
            return DB::table('payments')
                ->whereBetween('paid_at', [$month->toDateString(), $month->copy()->endOfMonth()->toDateString()])
                ->sum('amount');
        });

        return [
            'labels' => $labels,
            'lent' => $lent,
            'collected' => $collected,
        ];
    }

    protected function statusCounts(): array
    {
        $statuses = ['active', 'delinquent', 'completed'];

        return collect($statuses)->mapWithKeys(function ($status) {
            return [$status => DB::table('loans')->where('status', $status)->count()];
        })->toArray();
    }
}
