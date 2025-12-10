<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class AmortizationService
{
    public function generateSchedule(
        float $principal,
        float $annualRate,
        int $termMonths,
        string $frequency,
        Carbon $startDate
    ): array {
        $periodsPerYear = $this->periodsPerYear($frequency);
        $totalPeriods = $termMonths / (12 / $periodsPerYear);
        $periodRate = ($annualRate / 100) / $periodsPerYear;

        $payment = $periodRate === 0
            ? $principal / $totalPeriods
            : $principal * ($periodRate * pow(1 + $periodRate, $totalPeriods)) / (pow(1 + $periodRate, $totalPeriods) - 1);

        $balance = $principal;
        $schedule = [];
        $currentDate = $startDate->clone();

        for ($i = 1; $i <= $totalPeriods; $i++) {
            $interest = round($balance * $periodRate, 2);
            $principalPayment = round($payment - $interest, 2);
            $balance = round($balance - $principalPayment, 2);

            $schedule[] = [
                'number' => $i,
                'due_date' => $currentDate->copy(),
                'amount' => round($payment, 2),
                'principal_amount' => $principalPayment,
                'interest_amount' => $interest,
                'balance' => max($balance, 0),
            ];

            $currentDate = $this->incrementDate($currentDate, $frequency);
        }

        return [
            'payment' => round($payment, 2),
            'total_interest' => round(collect($schedule)->sum('interest_amount'), 2),
            'total_amount' => round($payment * $totalPeriods, 2),
            'schedule' => collect($schedule),
        ];
    }

    protected function periodsPerYear(string $frequency): int
    {
        return match ($frequency) {
            'weekly' => 52,
            'biweekly' => 26,
            default => 12,
        };
    }

    protected function incrementDate(Carbon $date, string $frequency): Carbon
    {
        return match ($frequency) {
            'weekly' => $date->addWeek(),
            'biweekly' => $date->addWeeks(2),
            default => $date->addMonth(),
        };
    }
}
