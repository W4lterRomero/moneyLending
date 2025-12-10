<?php

namespace App\Livewire\Loans;

use App\Models\Loan;
use Livewire\Component;
use BackedEnum;

class LoanKanban extends Component
{
    public array $columns = [
        'draft' => 'Borrador',
        'active' => 'Activo',
        'delinquent' => 'Moroso',
        'completed' => 'Completado',
    ];

    public function render()
    {
        $loans = Loan::with('client')
            ->latest()
            ->take(200)
            ->get()
            ->groupBy(function ($loan) {
                return $loan->status instanceof BackedEnum ? $loan->status->value : $loan->status;
            });

        return view('livewire.loans.loan-kanban', compact('loans'));
    }

    public function updateStatus(string $loanId, string $status): void
    {
        $allowed = array_keys($this->columns);
        if (!in_array($status, $allowed, true)) {
            return;
        }

        $loan = Loan::findOrFail($loanId);
        $loan->update(['status' => $status]);

        $this->dispatch('$refresh');
    }
}
