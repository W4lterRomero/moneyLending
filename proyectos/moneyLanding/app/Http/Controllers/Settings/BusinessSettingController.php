<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\BusinessSettingRequest;
use App\Models\BusinessSetting;
use App\Models\Client;
use App\Models\Loan;
use App\Models\Payment;
use App\Models\FinanceAccount;
use App\Models\FinanceTransaction;
use App\Models\Financing;

class BusinessSettingController extends Controller
{
    public function edit()
    {
        $settings = BusinessSetting::first();

        return view('settings.business', compact('settings'));
    }

    public function update(BusinessSettingRequest $request)
    {
        $settings = BusinessSetting::firstOrNew([]);
        $settings->fill($request->validated());
        $settings->save();

        return back()->with('success', 'Configuración guardada exitosamente');
    }

    public function downloadBackup()
    {
        try {
            $data = [
                'exported_at' => now()->toIso8601String(),
                'app_name' => config('app.name'),
                'clients' => Client::with(['loans.payments', 'documents'])->get()->toArray(),
                'loans' => Loan::with('payments')->get()->toArray(),
                'payments' => Payment::all()->toArray(),
                'finance_accounts' => class_exists(FinanceAccount::class) ? FinanceAccount::all()->toArray() : [],
                'finance_transactions' => class_exists(FinanceTransaction::class) ? FinanceTransaction::all()->toArray() : [],
                'financings' => class_exists(Financing::class) ? Financing::all()->toArray() : [],
                'settings' => BusinessSetting::first()?->toArray() ?? [],
            ];

            $filename = 'backup-' . now()->format('Y-m-d-His') . '.json';
            $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            return response()->streamDownload(function () use ($json) {
                echo $json;
            }, $filename, [
                'Content-Type' => 'application/json',
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al generar backup: ' . $e->getMessage());
        }
    }
}
