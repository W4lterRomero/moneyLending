<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;

class Payment extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'loan_id',
        'installment_id',
        'recorded_by',
        'paid_at',
        'amount',
        'interest_amount',
        'principal_amount',
        'method',
        'reference',
        'notes',
    ];

    protected $casts = [
        'paid_at' => 'date',
        'amount' => 'decimal:2',
        'interest_amount' => 'decimal:2',
        'principal_amount' => 'decimal:2',
        'method' => PaymentMethod::class,
    ];

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    public function installment(): BelongsTo
    {
        return $this->belongsTo(Installment::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function toSearchableArray()
    {
        return [
            'reference' => $this->reference,
            'method' => $this->method,
        ];
    }
}
