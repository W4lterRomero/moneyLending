<?php

namespace Database\Seeders;

use App\Models\BusinessSetting;
use App\Models\Client;
use App\Models\ClientDocument;
use App\Models\ClientReference;
use App\Models\Installment;
use App\Models\Loan;
use App\Models\User;
use App\Services\AmortizationService;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        BusinessSetting::create([
            'business_name' => 'Money Landing',
            'currency' => 'USD',
            'default_interest_rate' => 12,
            'default_penalty_rate' => 1,
        ]);

        $amortization = new AmortizationService();

        // Poblar cartera con más volumen para probar dashboards y vistas
        $statuses = ['draft', 'active', 'delinquent', 'completed'];

        Client::factory(12)->create()->each(function (Client $client) use ($amortization, $admin, $statuses) {
            // Documentos simulados
            $documentTypes = [
                'dui_front',
                'dui_back',
                'proof_of_income',
                'utility_bill',
            ];

            foreach ($documentTypes as $type) {
                $content = "Sample {$type} for {$client->name}";
                $path = "clients/{$client->id}/documents/{$type}_".Str::lower(Str::random(6)).".txt";
                Storage::disk('public')->put($path, $content);

                ClientDocument::create([
                    'client_id' => $client->id,
                    'type' => $type,
                    'file_path' => $path,
                    'original_name' => basename($path),
                    'mime_type' => 'text/plain',
                    'file_size' => strlen($content),
                    'uploaded_by' => $admin->id,
                    'verified' => $type === 'dui_front',
                    'verified_at' => $type === 'dui_front' ? now() : null,
                    'verified_by' => $type === 'dui_front' ? $admin->id : null,
                ]);
            }

            // Referencias
            $referenceTypes = ['personal', 'family', 'work'];
            foreach ($referenceTypes as $type) {
                ClientReference::create([
                    'client_id' => $client->id,
                    'type' => $type,
                    'name' => fake()->name(),
                    'phone' => fake()->phoneNumber(),
                    'relationship' => fake()->randomElement(['Hermano', 'Amigo', 'Colega', 'Jefe']),
                    'occupation' => fake()->jobTitle(),
                    'priority' => 1,
                    'verified' => $type === 'personal',
                    'verified_at' => $type === 'personal' ? now() : null,
                    'verified_by' => $type === 'personal' ? $admin->id : null,
                ]);
            }

            $loans = Loan::factory()
                ->count(3)
                ->state(fn () => ['user_id' => $admin->id])
                ->create([
                    'client_id' => $client->id,
                ]);

            foreach ($loans as $loan) {
                $start = Carbon::now()->subMonths(random_int(0, 6));
                $loan->update([
                    'start_date' => $start,
                    'status' => $statuses[array_rand($statuses)],
                    'disbursement_method' => 'bank_transfer',
                    'disbursement_date' => $start,
                    'disbursement_reference' => 'TRX-'.$loan->code,
                    'has_collateral' => true,
                    'collateral_type' => 'vehiculo',
                    'collateral_value' => 1500,
                    'has_guarantor' => true,
                    'guarantor_name' => fake()->name(),
                    'guarantor_phone' => fake()->phoneNumber(),
                ]);

                $calc = $amortization->generateSchedule(
                    (float) $loan->principal,
                    (float) $loan->interest_rate,
                    (int) $loan->term_months,
                    $loan->frequency,
                    Carbon::parse($loan->start_date)
                );

                $loan->update([
                    'installment_amount' => $calc['payment'],
                    'total_amount' => $calc['total_amount'],
                    'status' => 'active',
                    'next_due_date' => $calc['schedule']->first()['due_date'] ?? null,
                ]);

                $loan->installments()->createMany(
                    $calc['schedule']->map(function ($row) {
                        return [
                            'number' => $row['number'],
                            'due_date' => $row['due_date'],
                            'amount' => $row['amount'],
                            'principal_amount' => $row['principal_amount'],
                            'interest_amount' => $row['interest_amount'],
                            'status' => 'pending',
                        ];
                    })->toArray()
                );

                // Marcar primeras dos cuotas como pagadas para mostrar cobros y morosidad
                $paidInstallments = $loan->installments()->orderBy('number')->take(2)->get();
                foreach ($paidInstallments as $installment) {
                    $loan->payments()->create([
                        'installment_id' => $installment->id,
                        'recorded_by' => $admin->id,
                        'paid_at' => Carbon::now()->subDays(random_int(1, 20)),
                        'amount' => $installment->amount,
                        'interest_amount' => $installment->interest_amount,
                        'principal_amount' => $installment->principal_amount,
                        'method' => 'transfer',
                        'reference' => 'REF-'.str_pad((string) $installment->id, 4, '0', STR_PAD_LEFT),
                    ]);

                    $installment->update([
                        'paid_at' => Carbon::now()->subDays(random_int(1, 20)),
                        'status' => 'paid',
                    ]);
                }

                // Marcar algunas cuotas vencidas para mostrar morosidad
                $loan->installments()
                    ->whereDate('due_date', '<', Carbon::now())
                    ->inRandomOrder()
                    ->take(2)
                    ->update(['status' => 'overdue']);

                $loan->update([
                    'status' => $loan->installments()->where('status', 'overdue')->exists() ? 'delinquent' : $loan->status,
                    'next_due_date' => $loan->installments()->where('status', 'pending')->orderBy('due_date')->value('due_date'),
                ]);
            }
        });
    }
}
