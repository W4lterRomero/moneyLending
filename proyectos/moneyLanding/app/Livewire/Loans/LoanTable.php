<?php

namespace App\Livewire\Loans;

use App\Models\Loan;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class LoanTable extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = 'all';
    public string $range = 'month';
    public array $columns = ['code', 'client', 'principal', 'interest_rate', 'status', 'next_due_date'];

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => 'all'],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function toggleColumn(string $column): void
    {
        if (in_array($column, $this->columns)) {
            $this->columns = array_values(array_diff($this->columns, [$column]));
        } else {
            $this->columns[] = $column;
        }
    }

    public function render()
    {
        $loans = Loan::with('client')
            ->when($this->search, function (Builder $query) {
                $query->where('code', 'like', "%{$this->search}%")
                    ->orWhereHas('client', fn ($c) => $c->where('name', 'like', "%{$this->search}%"));
            })
            ->when($this->status !== 'all', fn ($q) => $q->where('status', $this->status))
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.loans.loan-table', [
            'loans' => $loans,
        ]);
    }
}
