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
                $query->where(function ($q) {
                    $q->where('reference', 'like', "%{$this->search}%")
                      ->orWhereHas('loan.client', function ($c) {
                          $c->where('name', 'like', "%{$this->search}%");
                      });
                });
            })
            ->latest('paid_at')
            ->paginate(15);

        return view('livewire.payments.payment-table', [
            'payments' => $payments,
        ]);
    }
}
