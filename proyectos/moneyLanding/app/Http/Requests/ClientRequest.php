<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $clientId = $this->route('client')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:clients,email,'.$clientId],
            'phone' => ['required', 'string', 'max:50'],
            'second_phone' => ['nullable', 'string', 'max:50'],
            'document_type' => ['nullable', 'string', 'max:50'],
            'document_number' => ['nullable', 'string', 'max:50', 'unique:clients,document_number,'.$clientId],
            'birth_date' => ['nullable', 'date'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'in:lead,active,suspended'],
            'tags' => ['nullable'],
            'notes' => ['nullable', 'string'],
            'gender' => ['nullable', 'in:male,female,other,prefer_not_to_say'],
            'marital_status' => ['nullable', 'in:single,married,divorced,widowed,separated'],
            'dependents' => ['nullable', 'integer', 'min:0'],
            'nationality' => ['nullable', 'string', 'max:120'],
            'occupation' => ['nullable', 'string', 'max:255'],
            'place_of_birth' => ['nullable', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'job_title' => ['nullable', 'string', 'max:255'],
            'employment_type' => ['nullable', 'in:permanent,temporary,freelance,self_employed,unemployed'],
            'monthly_income' => ['nullable', 'numeric', 'min:0'],
            'work_phone' => ['nullable', 'string', 'max:50'],
            'work_address' => ['nullable', 'string'],
            'employment_start_date' => ['nullable', 'date'],
            'supervisor_name' => ['nullable', 'string', 'max:255'],
            'supervisor_phone' => ['nullable', 'string', 'max:50'],
            'bank_name' => ['nullable', 'string', 'max:255'],
            'bank_account_number' => ['nullable', 'string', 'max:255'],
            'bank_account_type' => ['nullable', 'in:savings,checking'],
            'photo' => ['nullable', 'image', 'max:5120'],
            'dui_front' => ['nullable', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'dui_back' => ['nullable', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'selfie_with_id' => ['nullable', 'image', 'max:5120'],
            'proof_of_income' => ['nullable', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'utility_bill' => ['nullable', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ];
    }
}
