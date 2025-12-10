<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Loan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class LoanFactory extends Factory
{
    protected $model = Loan::class;

    public function definition(): array
    {
        $principal = $this->faker->numberBetween(1000, 5000);
        $interest = $this->faker->randomFloat(2, 8, 20);

        return [
            'client_id' => Client::factory(),
            'code' => 'LN-'.Str::upper(Str::random(6)),
            'principal' => $principal,
            'interest_rate' => $interest,
            'term_months' => 12,
            'frequency' => 'monthly',
            'start_date' => now()->subMonths(2),
            'end_date' => now()->addMonths(10),
            'total_amount' => $principal * (1 + ($interest / 100)),
            'installment_amount' => $principal / 12,
            'status' => 'active',
            'disbursement_method' => $this->faker->randomElement(['cash', 'bank_transfer', 'check']),
            'disbursement_date' => now()->subMonths(2),
            'disbursement_reference' => 'TRX-'.Str::upper(Str::random(6)),
            'has_collateral' => $this->faker->boolean(50),
            'collateral_type' => $this->faker->randomElement(['vehiculo', 'propiedad', 'electrodoméstico']),
            'collateral_value' => $this->faker->numberBetween(500, 5000),
            'has_guarantor' => $this->faker->boolean(40),
            'guarantor_name' => $this->faker->name(),
            'guarantor_phone' => $this->faker->phoneNumber(),
        ];
    }
}
