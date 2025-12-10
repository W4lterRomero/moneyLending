<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $loanId = $this->route('loan')?->id;

        return [
            'client_id' => ['required', 'exists:clients,id'],
            'principal' => ['required', 'numeric', 'min:0'],
            'interest_rate' => ['required', 'numeric', 'min:0'],
            'term_months' => ['required', 'integer', 'min:1'],
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
