<?php

namespace App\Livewire\Payments;

use App\Models\Payment;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentTable extends Component
{
    use WithPagination;

    public string $search = '';

    // Date Range Filter
    #[Url]
    public string $dateRange = 'this_month';
    public ?string $startDate = null;
    public ?string $endDate = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedDateRange(): void
    {
        $this->resetPage();
    }

    public function updatedStartDate(): void
    {
        $this->resetPage();
    }

    public function updatedEndDate(): void
    {
        $this->resetPage();
    }

    // Helper to get current filter label
    public function getDateRangeLabel(): string
    {
        return match($this->dateRange) {
            'this_month' => now()->translatedFormat('F Y'),
            'last_30' => 'Últimos 30 días',
            'all' => 'Todo el historial',
            'custom' => $this->startDate && $this->endDate 
                ? date('d/m/Y', strtotime($this->startDate)) . ' - ' . date('d/m/Y', strtotime($this->endDate))
                : 'Rango personalizado',
            default => 'Este mes',
        };
    }

    public function render()
    {
        $payments = Payment::with(['loan.client'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('reference', 'like', "%{$this->search}%")
                      ->orWhereHas('loan.client', function ($c) {
                          $c->where('name', 'like', "%{$this->search}%");
                      });
                });
            })
            ->when($this->dateRange === 'this_month', fn($q) => 
                $q->whereMonth('paid_at', now()->month)
                  ->whereYear('paid_at', now()->year)
            )
            ->when($this->dateRange === 'last_30', fn($q) => 
                $q->where('paid_at', '>=', now()->subDays(30))
            )
            ->when($this->dateRange === 'custom' && $this->startDate && $this->endDate, fn($q) =>
                $q->whereBetween('paid_at', [$this->startDate, $this->endDate])
            )
            ->latest('paid_at')
            ->paginate(15);

        return view('livewire.payments.payment-table', [
            'payments' => $payments,
        ]);
    }
}
