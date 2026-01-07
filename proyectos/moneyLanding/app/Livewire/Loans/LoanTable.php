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

    // Existing properties
    public $limit = null;
    
    #[Url]
    public string $search = '';

    // Sorting
    #[Url]
    public string $sortField = 'created_at';
    #[Url]
    public string $sortDirection = 'desc';

    // New: Per page / Limit selector
    #[Url]
    public int $perPage = 10;

    // New: Frequency filter
    #[Url]
    public string $filterFrequency = '';

    // New: Visible columns (stored in session/local)
    public array $visibleColumns = [
        'cliente' => true,
        'monto' => true,
        'interes' => true,
        'frecuencia' => true,
        'ganancia' => true,
        'acciones' => true,
    ];

    public function mount(): void
    {
        // Load saved column preferences from session
        $saved = session('loan_table_columns');
        if ($saved) {
            $this->visibleColumns = array_merge($this->visibleColumns, $saved);
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterFrequency(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    public function toggleColumn(string $column): void
    {
        if (isset($this->visibleColumns[$column])) {
            $this->visibleColumns[$column] = !$this->visibleColumns[$column];
            session(['loan_table_columns' => $this->visibleColumns]);
        }
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'desc';
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
            ->when($this->filterFrequency, function (Builder $query) {
                $query->where('frequency', $this->filterFrequency);
            })
            ->orderBy($this->sortField, $this->sortDirection);

        if ($this->limit) {
            $loans = $query->take($this->limit)->get();
        } else {
            $loans = $query->paginate($this->perPage);
        }

        return view('livewire.loans.loan-table', [
            'loans' => $loans,
        ]);
    }
}
