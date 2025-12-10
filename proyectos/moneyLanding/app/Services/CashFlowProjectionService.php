<?php

namespace App\Services;

use App\Models\Installment;
use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class CashFlowProjectionService
{
    public function project(int $days = 90): array
    {
        $start = Carbon::today();
        $end = Carbon::today()->addDays($days);

        $inflows = Installment::whereBetween('due_date', [$start, $end])
            ->whereIn('status', ['pending', 'overdue'])
            ->get()
            ->groupBy(fn ($i) => $i->due_date->toDateString())
            ->map(fn ($group) => $group->sum('amount'));

        $outflows = Loan::whereBetween('start_date', [$start, $end])
            ->get()
            ->groupBy(fn ($l) => Carbon::parse($l->start_date)->toDateString())
            ->map(fn ($group) => $group->sum('principal'));

        $daysRange = collect(range(0, $days))->map(fn ($i) => $start->copy()->addDays($i)->toDateString());

        $series = $daysRange->map(function ($date) use ($inflows, $outflows) {
            return [
                'date' => $date,
                'inflow' => (float) $inflows->get($date, 0),
                'outflow' => (float) $outflows->get($date, 0),
                'net' => (float) $inflows->get($date, 0) - (float) $outflows->get($date, 0),
            ];
        });

        return [
            'series' => $series,
            'totals' => [
                'inflow' => $series->sum('inflow'),
                'outflow' => $series->sum('outflow'),
                'net' => $series->sum('net'),
            ],
        ];
    }
}
