<?php

namespace App\Services;

use App\Enums\InstallmentStatus;
use App\Models\Loan;
use Carbon\Carbon;

class RiskScoringService
{
    public function scoreLoan(Loan $loan): float
    {
        $clientLoans = Loan::where('client_id', $loan->client_id)->where('id', '!=', $loan->id)->get();
        $prevLoansCount = $clientLoans->count();

        $installments = $loan->installments;
        $paid = $installments->where('status', InstallmentStatus::Paid);
        $overdue = $installments->where('status', InstallmentStatus::Overdue);

        $onTimeRatio = $installments->count() > 0 ? ($paid->count() / $installments->count()) : 1;
        $avgDelay = $overdue->count() > 0
            ? $overdue->avg(function ($i) {
                return Carbon::parse($i->due_date)->diffInDays(Carbon::parse($i->paid_at ?? now()));
            })
            : 0;

        $historialScore = 100 * $onTimeRatio; // 40%
        $prevLoansScore = max(0, 100 - ($prevLoansCount * 10)); // 20%
        $delayScore = max(0, 100 - ($avgDelay * 5)); // 30%
        $amountVsHistory = $this->amountVsHistoryScore($loan, $clientLoans); // 10%

        $score = ($historialScore * 0.4)
            + ($prevLoansScore * 0.2)
            + ($delayScore * 0.3)
            + ($amountVsHistory * 0.1);

        return round(min(100, max(0, $score)), 2);
    }

    protected function amountVsHistoryScore(Loan $loan, $clientLoans): float
    {
        $avg = $clientLoans->avg('principal') ?: 0;
        if ($avg === 0) {
            return 70; // sin historial
        }

        $ratio = $loan->principal / $avg;
        if ($ratio <= 1) {
            return 100;
        }

        if ($ratio <= 1.5) {
            return 80;
        }

        return 60;
    }
}
