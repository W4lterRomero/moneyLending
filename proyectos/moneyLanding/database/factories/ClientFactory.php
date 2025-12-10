<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'second_phone' => $this->faker->phoneNumber(),
            'document_type' => 'ID',
            'document_number' => (string) $this->faker->numerify('#########'),
            'birth_date' => $this->faker->dateTimeBetween('-60 years', '-18 years'),
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
            'marital_status' => $this->faker->randomElement(['single', 'married', 'divorced', 'widowed']),
            'dependents' => $this->faker->numberBetween(0, 3),
            'nationality' => 'El Salvador',
            'occupation' => $this->faker->jobTitle(),
            'place_of_birth' => $this->faker->city(),
            'address' => $this->faker->address(),
            'city' => $this->faker->city(),
            'country' => $this->faker->country(),
            'company_name' => $this->faker->company(),
            'job_title' => $this->faker->jobTitle(),
            'employment_type' => $this->faker->randomElement(['permanent', 'temporary', 'freelance', 'self_employed']),
            'monthly_income' => $this->faker->numberBetween(500, 3000),
            'work_phone' => $this->faker->phoneNumber(),
            'work_address' => $this->faker->address(),
            'employment_start_date' => $this->faker->dateTimeBetween('-5 years', '-3 months'),
            'supervisor_name' => $this->faker->name(),
            'supervisor_phone' => $this->faker->phoneNumber(),
            'bank_name' => $this->faker->company().' Bank',
            'bank_account_number' => $this->faker->bankAccountNumber(),
            'bank_account_type' => $this->faker->randomElement(['savings', 'checking']),
            'status' => 'active',
            'notes' => $this->faker->sentence(),
            'tags' => $this->faker->randomElements(['vip', 'recurrente', 'alto_riesgo', 'preferente'], 2),
        ];
    }
}
