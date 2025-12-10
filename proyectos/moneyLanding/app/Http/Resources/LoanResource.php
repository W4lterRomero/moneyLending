<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'client' => new ClientResource($this->whenLoaded('client')),
            'principal' => $this->principal,
            'interest_rate' => $this->interest_rate,
            'status' => $this->status,
            'next_due_date' => optional($this->next_due_date)->toDateString(),
        ];
    }
}
