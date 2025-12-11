<?php

namespace App\Livewire\Loans;

use App\Models\Loan;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

use Livewire\Attributes\On;
use Livewire\Attributes\Url;

class LoanTable extends Component
{
    use WithPagination;

    public $limit = null;
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';
    
    #[Url]
    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'desc'; // Default to desc mainly for amounts/dates
        }
    }

    #[On('dashboard-refreshed')]
    public function refresh(): void
    {
        // Just triggers re-render
    }

    public function render()
    {
        $query = Loan::with('client')
            ->when($this->search, function (Builder $query) {
                $query->where(function ($q) {
                    $q->where('code', 'like', "%{$this->search}%")
                      ->orWhereHas('client', fn ($c) => $c->where('name', 'like', "%{$this->search}%"));
                });
            })
            ->orderBy($this->sortField, $this->sortDirection);

        if ($this->limit) {
            $loans = $query->take($this->limit)->get();
        } else {
            $loans = $query->paginate(10);
        }

        return view('livewire.loans.loan-table', [
            'loans' => $loans,
        ]);
    }


}
