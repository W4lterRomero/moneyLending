<?php

namespace Database\Factories;

use App\Models\Installment;
use App\Models\Loan;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstallmentFactory extends Factory
{
    protected $model = Installment::class;

    public function definition(): array
    {
        return [
            'loan_id' => Loan::factory(),
            'number' => 1,
            'due_date' => now()->addMonth(),
            'amount' => 200,
            'principal_amount' => 150,
            'interest_amount' => 50,
            'status' => 'pending',
        ];
    }
}
