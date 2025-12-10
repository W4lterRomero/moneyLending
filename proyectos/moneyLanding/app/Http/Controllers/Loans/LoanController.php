<?php

namespace App\Http\Controllers\Loans;

use App\Enums\LoanStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoanRequest;
use App\Models\Client;
use App\Models\Loan;
use App\Models\Installment;
use App\Services\AmortizationService;
use App\Services\FilterBuilder;
use App\Services\RiskScoringService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LoanController extends Controller
{
    public function __construct(
        private readonly AmortizationService $amortization,
        private readonly FilterBuilder $filters,
        private readonly RiskScoringService $riskScoring
    ) {
        $this->authorizeResource(Loan::class, 'loan');
    }

    public function index(Request $request)
    {
        $query = Loan::with(['client', 'owner'])->latest();

        if ($request->filled('filters')) {
            $this->filters->apply($query, $request->input('filters'));
        }

        if ($term = $request->get('search')) {
            $query->where(function ($q) use ($term) {
                $q->where('code', 'like', "%{$term}%")
                    ->orWhereHas('client', fn ($c) => $c->where('name', 'like', "%{$term}%"));
            });
        }

        $loans = $query->paginate(15)->withQueryString();

        return view('loans.index', [
            'loans' => $loans,
            'clients' => Client::orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('loans.create', [
            'clients' => Client::orderBy('name')->get(),
        ]);
    }

    public function store(LoanRequest $request)
    {
        $data = $request->validated();
        $loan = null;

        DB::transaction(function () use (&$loan, $data) {
            $loan = Loan::create([
                ...$data,
                'user_id' => auth()->id(),
                'code' => 'LN-'.Str::upper(Str::random(6)),
            ]);

            $this->buildSchedule($loan);
        });

        return redirect()->route('loans.show', $loan)->with('success', 'Préstamo creado');
    }

    public function show(Loan $loan)
    {
        $loan->load(['client', 'installments', 'payments', 'owner']);

        return view('loans.show', compact('loan'));
    }

    public function edit(Loan $loan)
    {
        return view('loans.edit', [
            'loan' => $loan,
            'clients' => Client::orderBy('name')->get(),
        ]);
    }

    public function update(LoanRequest $request, Loan $loan)
    {
        $loan->update($request->validated());
        $this->buildSchedule($loan);

        return redirect()->route('loans.show', $loan)->with('success', 'Préstamo actualizado');
    }

    public function destroy(Loan $loan)
    {
        $loan->delete();

        return redirect()->route('loans.index')->with('success', 'Préstamo eliminado');
    }

    protected function buildSchedule(Loan $loan): void
    {
        $calc = $this->amortization->generateSchedule(
            $loan->principal,
            $loan->interest_rate,
            $loan->term_months,
            $loan->frequency,
            $loan->start_date
        );

        $loan->update([
            'installment_amount' => $calc['payment'],
            'total_amount' => $calc['total_amount'],
            'status' => LoanStatus::Active,
            'next_due_date' => $calc['schedule']->first()['due_date'] ?? null,
        ]);

        $loan->installments()->delete();

        foreach ($calc['schedule'] as $row) {
            $loan->installments()->create([
                'number' => $row['number'],
                'due_date' => $row['due_date'],
                'amount' => $row['amount'],
                'principal_amount' => $row['principal_amount'],
                'interest_amount' => $row['interest_amount'],
                'status' => 'pending',
            ]);
        }

        // Actualizar puntaje de riesgo
        $loan->update(['risk_score' => $this->riskScoring->scoreLoan($loan)]);
    }
}
