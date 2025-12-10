<?php

namespace App\Http\Controllers\Reports;

use App\Exports\SimpleCollectionExport;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Loan;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function exportClients(Request $request)
    {
        $clients = Client::all()->map->toArray();

        return Excel::download(new SimpleCollectionExport(collect($clients)), 'clientes.xlsx');
    }

    public function exportLoans(Request $request)
    {
        $loans = Loan::with('client')->get()->map(function ($loan) {
            return [
                'code' => $loan->code,
                'client' => $loan->client?->name,
                'principal' => $loan->principal,
                'interest_rate' => $loan->interest_rate,
                'status' => $loan->status instanceof \BackedEnum ? $loan->status->value : $loan->status,
            ];
        });

        return Excel::download(new SimpleCollectionExport($loans), 'prestamos.xlsx');
    }

    public function exportPayments(Request $request)
    {
        $payments = Payment::with('loan')->get()->map(function ($payment) {
            return [
                'loan' => $payment->loan?->code,
                'amount' => $payment->amount,
                'paid_at' => $payment->paid_at,
                'method' => $payment->method instanceof \BackedEnum ? $payment->method->value : $payment->method,
                'reference' => $payment->reference,
            ];
        });

        return Excel::download(new SimpleCollectionExport($payments), 'pagos.xlsx');
    }

    public function pdfPortfolio()
    {
        $loans = Loan::with('client')->get();
        $pdf = Pdf::loadView('reports.pdf.portfolio', compact('loans'));

        return $pdf->download('cartera.pdf');
    }
}
