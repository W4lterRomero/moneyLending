<?php

namespace App\Livewire\Payments;

use App\Models\Payment;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentTable extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $payments = Payment::with(['loan.client'])
            ->when($this->search, function ($query) {
                $query->whereHas('loan.client', function ($q) {
                    $q->where('name', 'like', "%{$this->search}%");
                });
            })
            ->latest('paid_at')
            ->paginate(15);

        return view('livewire.payments.payment-table', [
            'payments' => $payments,
        ]);
    }
}
