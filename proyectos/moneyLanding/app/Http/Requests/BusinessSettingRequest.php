<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BusinessSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'business_name' => ['required', 'string', 'max:255'],
            'legal_id' => ['nullable', 'string', 'max:255'],
            'currency' => ['required', 'string', 'max:10'],
            'timezone' => ['required', 'string', 'max:50'],
            'default_interest_rate' => ['required', 'numeric', 'min:0'],
            'default_penalty_rate' => ['nullable', 'numeric', 'min:0'],
            'contract_templates' => ['nullable', 'array'],
            'notification_channels' => ['nullable', 'array'],
        ];
    }
}
