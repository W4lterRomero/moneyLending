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
    public string $refreshedAt = '';

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

    public function resetFilters(): void
    {
        $this->range = 'month';
        $this->start = null;
        $this->end = null;
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
        $this->refreshedAt = now()->format('d/m/Y H:i');

        // Notifica al front que debe redibujar los charts
        $this->dispatch('charts-refresh');
    }

    protected function buildChartData(): array
    {
        [$rangeStart, $rangeEnd] = $this->dateRange();

        $months = collect();
        $cursor = $rangeStart->copy()->startOfMonth();
        $limit = 18; // evita gráficas interminables
        while ($cursor->lte($rangeEnd) && $months->count() < $limit) {
            $months->push($cursor->copy());
            $cursor->addMonth();
        }

        $labels = $months->map(fn ($m) => $m->format('M Y'));

        $lentGrouped = DB::table('loans')
            ->selectRaw('DATE_FORMAT(start_date, "%Y-%m") as ym, SUM(principal) as total')
            ->whereBetween('start_date', [$rangeStart, $rangeEnd])
            ->groupBy('ym')
            ->pluck('total', 'ym');

        $collectedGrouped = DB::table('payments')
            ->selectRaw('DATE_FORMAT(paid_at, "%Y-%m") as ym, SUM(amount) as total')
            ->whereBetween('paid_at', [$rangeStart, $rangeEnd])
            ->groupBy('ym')
            ->pluck('total', 'ym');

        $lent = $months->map(function ($month) use ($lentGrouped) {
            $key = $month->format('Y-m');
            return (float) ($lentGrouped[$key] ?? 0);
        });

        $collected = $months->map(function ($month) use ($collectedGrouped) {
            $key = $month->format('Y-m');
            return (float) ($collectedGrouped[$key] ?? 0);
        });

        return [
            'labels' => $labels,
            'lent' => $lent,
            'collected' => $collected,
        ];
    }

    protected function statusCounts(): array
    {
        [$start, $end] = $this->dateRange();
        $statuses = ['active', 'delinquent', 'completed'];

        return collect($statuses)->mapWithKeys(function ($status) use ($start, $end) {
            return [
                $status => DB::table('loans')
                    ->where('status', $status)
                    ->whereBetween('start_date', [$start, $end])
                    ->count()
            ];
        })->toArray();
    }

    protected function dateRange(): array
    {
        $start = $this->start ? Carbon::parse($this->start) : null;
        $end = $this->end ? Carbon::parse($this->end) : null;

        if (!$start || !$end) {
            [$start, $end] = match ($this->range) {
                'today' => [now()->startOfDay(), now()->endOfDay()],
                'week' => [now()->startOfWeek(), now()->endOfWeek()],
                'year' => [now()->startOfYear(), now()->endOfYear()],
                default => [now()->startOfMonth(), now()->endOfMonth()],
            };
        }

        if ($start->gt($end)) {
            [$start, $end] = [$end, $start];
        }

        return [$start->startOfDay(), $end->endOfDay()];
    }
}
