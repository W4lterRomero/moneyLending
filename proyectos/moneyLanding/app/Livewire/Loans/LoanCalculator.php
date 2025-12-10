<?php

namespace App\Livewire\Loans;

use App\Services\AmortizationService;
use Livewire\Component;

class LoanCalculator extends Component
{
    public float $principal = 1000;
    public float $interest = 12;
    public int $term = 12;
    public string $frequency = 'monthly';
    public array $result = [];

    public function render()
    {
        return view('livewire.loans.loan-calculator');
    }

    public function calculate(AmortizationService $amortization): void
    {
        $calc = $amortization->generateSchedule(
            $this->principal,
            $this->interest,
            $this->term,
            $this->frequency,
            now()
        );

        $this->result = [
            'payment' => $calc['payment'],
            'total_interest' => $calc['total_interest'],
            'total_amount' => $calc['total_amount'],
            'schedule' => $calc['schedule']->take(6)->toArray(),
        ];
    }
}
