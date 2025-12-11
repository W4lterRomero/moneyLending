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
    public function metrics(?string $range = null, ?array $custom = null, bool $forceRefresh = false): array
    {
        [$start, $end] = $this->range($range, $custom);

        $cacheKey = 'kpi:'
            . ($range ?? 'custom') . ':'
            . ($start?->timestamp ?? 'null') . ':'
            . ($end?->timestamp ?? 'null');

        if ($forceRefresh) {
            Cache::forget($cacheKey);
        }

        // Caché de 1 hora para Raspberry Pi
        return Cache::remember($cacheKey, 3600, function () use ($start, $end) {
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

            // Top Profesiones (Occupation)
            $topOccupations = Loan::query()
                ->join('clients', 'loans.client_id', '=', 'clients.id')
                ->when($start, fn ($q) => $q->whereBetween('loans.start_date', [$start, $end]))
                ->whereNotNull('clients.occupation')
                ->where('clients.occupation', '!=', '')
                ->selectRaw('clients.occupation as name, count(*) as count, sum(loans.principal) as total_amount')
                ->groupBy('clients.occupation')
                ->orderByDesc('total_amount')
                ->limit(5)
                ->get();

            // Top Empresas (Company Name)
            $topCompanies = Loan::query()
                ->join('clients', 'loans.client_id', '=', 'clients.id')
                ->when($start, fn ($q) => $q->whereBetween('loans.start_date', [$start, $end]))
                ->whereNotNull('clients.company_name')
                ->where('clients.company_name', '!=', '')
                ->selectRaw('clients.company_name as name, count(*) as count, sum(loans.principal) as total_amount')
                ->groupBy('clients.company_name')
                ->orderByDesc('total_amount')
                ->limit(5)
                ->get();

            return [
                'total_lent' => $totalLent,
                'total_collected' => $totalCollected,
                'active_loans' => $activeLoans,
                'delinquency_rate' => $totalInstallments > 0 ? round(($delinquentInstallments / $totalInstallments) * 100, 2) : 0,
                'top_occupations' => $topOccupations,
                'top_companies' => $topCompanies,
                'range' => [$start?->toDateString(), $end?->toDateString()],
            ];
        });
    }

    protected function range(?string $range, ?array $custom): array
    {
        // Si hay fechas custom, usarlas primero
        if (isset($custom['start']) && isset($custom['end'])) {
            try {
                $start = Carbon::parse($custom['start'])->startOfDay();
                $end = Carbon::parse($custom['end'])->endOfDay();
                return [$start, $end];
            } catch (\Exception $e) {
                // Si falla el parsing, usar el rango por defecto
            }
        }

        // Rangos predefinidos
        return match ($range) {
            'today' => [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()],
            'week' => [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()],
            'year' => [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()],
            default => [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()],
        };
    }
}
