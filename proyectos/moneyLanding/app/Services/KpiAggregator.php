<?php

namespace App\Services;

use App\Enums\InstallmentStatus;
use App\Enums\LoanStatus;
use App\Models\Installment;
use App\Models\Loan;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class KpiAggregator
{
    public function metrics(?string $range = null, ?array $custom = null): array
    {
        [$start, $end] = $this->range($range, $custom);

        $cacheKey = 'kpi:'
            . ($range ?? 'custom') . ':'
            . ($start?->timestamp ?? 'null') . ':'
            . ($end?->timestamp ?? 'null');

        return Cache::remember($cacheKey, 60, function () use ($start, $end) {
            $loanQuery = Loan::query()->when($start, fn ($q) => $q->whereBetween('start_date', [$start, $end]));
            $paymentQuery = Payment::query()->when($start, fn ($q) => $q->whereBetween('paid_at', [$start, $end]));

            $totalLent = $loanQuery->sum('principal');
            $totalCollected = $paymentQuery->sum('amount');
            $activeLoans = Loan::where('status', LoanStatus::Active)
                ->when($start, fn ($q) => $q->whereBetween('start_date', [$start, $end]))
                ->count();
            $delinquentInstallments = Installment::where('status', InstallmentStatus::Overdue)
                ->when($start, fn ($q) => $q->whereBetween('due_date', [$start, $end]))
                ->count();
            $totalInstallments = Installment::when($start, fn ($q) => $q->whereBetween('due_date', [$start, $end]))->count();

            return [
                'total_lent' => $totalLent,
                'total_collected' => $totalCollected,
                'active_loans' => $activeLoans,
                'delinquency_rate' => $totalInstallments > 0 ? round(($delinquentInstallments / $totalInstallments) * 100, 2) : 0,
                'range' => [$start?->toDateString(), $end?->toDateString()],
            ];
        });
    }

    protected function range(?string $range, ?array $custom): array
    {
        $end = Carbon::today();
        $start = match ($range) {
            'today' => Carbon::today(),
            'week' => Carbon::now()->startOfWeek(),
            'year' => Carbon::now()->startOfYear(),
            'custom' => isset($custom['start'], $custom['end'])
                ? Carbon::parse($custom['start'])
                : Carbon::now()->startOfMonth(),
            default => Carbon::now()->startOfMonth(),
        };

        if ($range === 'custom' && isset($custom['end'])) {
            $end = Carbon::parse($custom['end']);
        } elseif ($range === 'week') {
            $end = Carbon::now()->endOfWeek();
        } elseif ($range === 'year') {
            $end = Carbon::now()->endOfYear();
        } else {
            $end = Carbon::now()->endOfMonth();
        }

        return [$start, $end];
    }
}
