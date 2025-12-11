<?php

namespace App\Http\Controllers\Payments;

use App\Enums\InstallmentStatus;
use App\Http\Controllers\Controller;
use App\Enums\LoanStatus;
use App\Http\Requests\PaymentRequest;
use App\Models\Installment;
use App\Models\Loan;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Payment::class, 'payment');
    }

    public function index()
    {
        $payments = Payment::with(['loan.client', 'installment'])->latest()->paginate(20);
        $loans = $this->loanOptions();

        return view('payments.index', compact('payments', 'loans'));
    }

    public function create()
    {
        return view('payments.create', [
            'loans' => $this->loanOptions(),
            'installments' => collect(),
        ]);
    }

    public function store(PaymentRequest $request)
    {
        $payment = null;

        \DB::transaction(function () use ($request, &$payment) {
            $data = collect($request->validated())->except(['photo', 'receipt'])->toArray();

            $payment = Payment::create([
                ...$data,
                'recorded_by' => auth()->id(),
            ]);

            $this->storeFile($payment, $request, 'photo', 'photo_path');
            $this->storeFile($payment, $request, 'receipt', 'receipt_path');

            $this->syncInstallment($payment);
        });

        return redirect()->route('payments.show', $payment)->with('success', 'Pago registrado');
    }

    public function show(Payment $payment)
    {
        $payment->load(['loan', 'installment']);

        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        return view('payments.edit', [
            'payment' => $payment,
            'loans' => $this->loanOptions(),
            'installments' => Installment::where('loan_id', $payment->loan_id)->get(),
        ]);
    }

    public function update(PaymentRequest $request, Payment $payment)
    {
        \DB::transaction(function () use ($request, $payment) {
            $payment->update(collect($request->validated())->except(['photo', 'receipt'])->toArray());

            $this->storeFile($payment, $request, 'photo', 'photo_path');
            $this->storeFile($payment, $request, 'receipt', 'receipt_path');

            $this->syncInstallment($payment->fresh());
        });

        return redirect()->route('payments.show', $payment)->with('success', 'Pago actualizado');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('payments.index')->with('success', 'Pago eliminado');
    }

    protected function syncInstallment(Payment $payment): void
    {
        if ($payment->installment) {
            $installmentAmount = $payment->installment->amount;

            if ($payment->amount >= $installmentAmount) {
                $payment->installment->update([
                    'paid_at' => $payment->paid_at,
                    'status' => InstallmentStatus::Paid,
                ]);
            } else {
                $payment->installment->update([
                    'status' => method_exists(InstallmentStatus::class, 'PartiallyPaid') ? InstallmentStatus::PartiallyPaid : InstallmentStatus::Pending,
                ]);
            }
        }

        $nextPending = $payment->loan->installments()->where('status', InstallmentStatus::Pending)->orderBy('number')->first();
        $payment->loan->update([
            'next_due_date' => $nextPending?->due_date,
            'status' => $nextPending ? $payment->loan->status : LoanStatus::Completed,
        ]);
    }

    protected function loanOptions(): Collection
    {
        return Loan::with('client')
            ->get()
            ->sortBy(fn ($loan) => strtolower($loan->client?->name ?? ''));
    }

    protected function storeFile(Payment $payment, Request $request, string $input, string $column): void
    {
        if (!$request->hasFile($input)) {
            return;
        }

        // Evitar fallos si la migración aún no se ha ejecutado
        if (!Schema::hasColumn('payments', $column)) {
            return;
        }

        if ($payment->{$column}) {
            Storage::disk('public')->delete($payment->{$column});
        }

        $path = $request->file($input)->store("payments/{$payment->id}", 'public');
        $payment->update([$column => $path]);
    }
}
