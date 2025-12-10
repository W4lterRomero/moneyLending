<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Loan;

class LoanRequest extends FormRequest
{
    public function authorize(): bool
    {
        $loan = $this->route('loan');
        if ($loan instanceof Loan) {
            return $this->user()?->can('update', $loan) ?? false;
        }
        return $this->user()?->can('create', Loan::class) ?? false;
    }

    public function rules(): array
    {
        $loanId = $this->route('loan')?->id;

        return [
            'client_id' => ['required', 'exists:clients,id'],
            'principal' => ['required', 'numeric', 'min:1', 'max:10000000'],
            'interest_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'term_months' => ['required', 'integer', 'min:1', 'max:360'],
            'frequency' => ['required', 'in:monthly,biweekly,weekly'],
            'start_date' => ['required', 'date'],
            'late_fee_rate' => ['nullable', 'numeric', 'min:0'],
            'penalty_rate' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:draft,active,completed,delinquent,cancelled'],
            'purpose' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
