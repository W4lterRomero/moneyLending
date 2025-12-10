<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'loan' => new LoanResource($this->whenLoaded('loan')),
            'amount' => $this->amount,
            'paid_at' => optional($this->paid_at)->toDateString(),
            'method' => $this->method,
        ];
    }
}
