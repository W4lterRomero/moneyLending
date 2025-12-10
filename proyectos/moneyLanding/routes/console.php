<?php

use App\Jobs\CheckDueLoansJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('data:export', function () {
    $clients = \App\Models\Client::all()->toArray();
    $loans = \App\Models\Loan::with('client')->get()->toArray();
    $payments = \App\Models\Payment::with('loan')->get()->toArray();

    $payload = json_encode(compact('clients', 'loans', 'payments'), JSON_PRETTY_PRINT);
    $path = storage_path('app/backup.json');
    file_put_contents($path, $payload);

    $this->info("Backup generado en {$path}");
})->purpose('Exporta datos principales a JSON');

Artisan::command('data:import-template', function () {
    $template = [
        'clients' => [
            ['name' => 'Cliente demo', 'email' => 'demo@mail.com', 'phone' => '+593', 'document_number' => 'DOC001'],
        ],
        'loans' => [
            ['client_email' => 'demo@mail.com', 'principal' => 1000, 'interest_rate' => 12, 'term_months' => 12, 'frequency' => 'monthly'],
        ],
        'payments' => [
            ['loan_code' => 'LN-DEMO', 'amount' => 120, 'paid_at' => now()->toDateString()],
        ],
    ];

    $path = storage_path('app/import_template.json');
    file_put_contents($path, json_encode($template, JSON_PRETTY_PRINT));

    $this->info("Plantilla guardada en {$path}");
})->purpose('Genera plantilla de importación JSON/CSV');

Schedule::job(new CheckDueLoansJob())->dailyAt('08:00')->onSuccess(function () {
    info('Job CheckDueLoansJob ejecutado.');
});
