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
        if ($principal <= 0 || $termMonths <= 0) {
            throw new \InvalidArgumentException('Invalid loan parameters');
        }

        $periodsPerYear = $this->periodsPerYear($frequency);
        $totalPeriods = ceil($termMonths / (12 / $periodsPerYear));
        
        if ($totalPeriods <= 0) {
            throw new \InvalidArgumentException('Invalid total periods');
        }

        // Interés Simple (Flat)
        // Ganancia = Monto * (Tasa / 100)
        // NOTA: Asumimos que la tasa es "Global" por el periodo del préstamo si el usuario así lo piensa,
        // o si es anual, se prorratea.
        // Según requerimiento: "monto * interes% = ganancia". 
        // Interpretación: Tasa es directa sobre el capital, sin importar el tiempo (tasa plana total)
        // O si es anual, se ajusta.
        // Para simplificar al máximo y seguir "monto * interes%": usaremos la tasa como porcentaje total de ganancia.
        // Si el usuario pone 10%, es 10% de ganancia sobre el capital.
        
        $totalInterest = $principal * ($annualRate / 100);
        $totalAmount = $principal + $totalInterest;
        $payment = $totalAmount / $totalPeriods;
        
        // Distribución equitativa de capital e interés por cuota
        $principalPerInstallment = $principal / $totalPeriods;
        $interestPerInstallment = $totalInterest / $totalPeriods;

        $balance = $totalAmount;
        $schedule = [];
        $currentDate = $startDate->clone();

        for ($i = 1; $i <= $totalPeriods; $i++) {
            // Ajuste de centavos en la última cuota
            if ($i == $totalPeriods) {
                // Lo que falte para cerrar
                $paymentAmount = $balance;
                // Recalculamos componentes marginalmente si es necesario, 
                // pero en flat simple suele ser fijo. Ajustaremos el principal para que cierre a 0.
                $principalPayment = $principal - ($principalPerInstallment * ($totalPeriods - 1));
                $interestPayment = $totalInterest - ($interestPerInstallment * ($totalPeriods - 1));
                $paymentAmount = $principalPayment + $interestPayment;
                $balance = 0;
            } else {
                $paymentAmount = $payment;
                $principalPayment = $principalPerInstallment;
                $interestPayment = $interestPerInstallment;
                $balance -= $paymentAmount;
            }

            $schedule[] = [
                'number' => $i,
                'due_date' => $currentDate->copy(),
                'amount' => round($paymentAmount, 2),
                'principal_amount' => round($principalPayment, 2),
                'interest_amount' => round($interestPayment, 2),
                'balance' => max(round($balance, 2), 0),
            ];

            $currentDate = $this->incrementDate($currentDate, $frequency);
        }

        return [
            'payment' => round($payment, 2),
            'total_interest' => round($totalInterest, 2),
            'total_amount' => round($totalAmount, 2),
            'schedule' => collect($schedule),
        ];
    }

    protected function periodsPerYear(string $frequency): int
    {
        return match ($frequency) {
            'daily' => 365,
            'weekly' => 52,
            'biweekly' => 26,
            default => 12,
        };
    }

    protected function incrementDate(Carbon $date, string $frequency): Carbon
    {
        return match ($frequency) {
            'daily' => $date->addDay(),
            'weekly' => $date->addWeek(),
            'biweekly' => $date->addWeeks(2),
            default => $date->addMonth(),
        };
    }
}
