<?php

namespace App\Livewire\Loans;

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
                return [
                    'title' => "{$installment->loan?->code} #{$installment->number}",
                    'start' => $installment->due_date->toDateString(),
                    'color' => $status === 'overdue' ? '#f59e0b' : '#4dabf7',
                    'url' => route('loans.show', $installment->loan_id),
                ];
            });

        return view('livewire.loans.loan-calendar', [
            'events' => $events,
        ]);
    }
}
