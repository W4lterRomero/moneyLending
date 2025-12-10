<?php

namespace App\Http\Controllers;

use App\Services\KpiAggregator;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct(private readonly KpiAggregator $aggregator)
    {
        $this->middleware('auth');
    }

    public function __invoke(Request $request)
    {
        $range = $request->get('range', 'month');
        $metrics = $this->aggregator->metrics($range, [
            'start' => $request->get('start'),
            'end' => $request->get('end'),
        ]);

        $chartData = $this->buildChartData();
        $statusCounts = $this->statusCounts();

        return view('dashboard', compact('metrics', 'range', 'chartData', 'statusCounts'));
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
