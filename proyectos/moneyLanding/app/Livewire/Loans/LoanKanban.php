<?php

namespace App\Livewire\Loans;

use App\Enums\LoanStatus;
use App\Models\Loan;
use Illuminate\Support\Facades\DB;
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
    public int $maxItems = 20;
    public array $columnCounts = [];

    public function render()
    {
        $this->columnCounts = Loan::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $loans = Loan::with('client')
            ->latest('updated_at')
            ->limit(200)
            ->get()
            ->groupBy(function ($loan) {
                return $loan->status instanceof BackedEnum ? $loan->status->value : $loan->status;
            })
            ->map(fn ($group) => $group->take($this->maxItems));

        return view('livewire.loans.loan-kanban', compact('loans'));
    }

    public function updateStatus(string $loanId, string $status): void
    {
        $allowed = array_keys($this->columns);
        if (!in_array($status, $allowed, true)) {
            return;
        }

        $loan = Loan::findOrFail($loanId);
        $loan->update(['status' => LoanStatus::from($status)]);

        $this->dispatch('$refresh');
    }
}
