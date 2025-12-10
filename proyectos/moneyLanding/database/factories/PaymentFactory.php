<?php

namespace Database\Factories;

use App\Models\Loan;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'loan_id' => Loan::factory(),
            'paid_at' => now(),
            'amount' => 220,
            'interest_amount' => 70,
            'principal_amount' => 150,
            'method' => 'cash',
            'reference' => $this->faker->uuid(),
        ];
    }
}
