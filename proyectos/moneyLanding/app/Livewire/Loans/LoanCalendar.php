<?php

namespace App\Livewire\Loans;

use App\Enums\InstallmentStatus;
use App\Models\Installment;
use Livewire\Component;

class LoanCalendar extends Component
{
    public function render()
    {
        $start = now()->startOfMonth()->subMonths(1);
        $end = now()->endOfMonth()->addMonths(2);

        $events = Installment::with('loan')
            ->whereBetween('due_date', [$start, $end])
            ->get()
            ->map(function ($installment) {
                $status = $installment->status instanceof \BackedEnum ? $installment->status->value : $installment->status;
                $color = match ($status) {
                    InstallmentStatus::Overdue->value => '#f59e0b',
                    InstallmentStatus::Paid->value => '#10b981',
                    default => '#4dabf7',
                };

                return [
                    'title' => "{$installment->loan?->code} #{$installment->number}",
                    'start' => $installment->due_date->toDateString(),
                    'color' => $color,
                    'status' => $status,
                    'url' => route('loans.show', $installment->loan_id),
                ];
            });

        return view('livewire.loans.loan-calendar', [
            'events' => $events,
        ]);
    }
}
