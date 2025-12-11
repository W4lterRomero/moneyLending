<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'loan_id' => ['required', 'exists:loans,id'],
            'installment_id' => ['nullable', 'exists:installments,id'],
            'paid_at' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0'],
            'interest_amount' => ['nullable', 'numeric', 'min:0'],
            'principal_amount' => ['nullable', 'numeric', 'min:0'],
            'method' => ['required', 'in:cash,transfer,card,deposit'],
            'reference' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'max:5120'],
            'receipt' => ['nullable', 'file', 'max:5120', 'mimes:jpg,jpeg,png,pdf'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
