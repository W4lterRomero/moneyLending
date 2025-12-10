<?php

namespace App\Models;

use App\Enums\InstallmentStatus;
use App\Enums\LoanStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

class Loan extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'client_id',
        'user_id',
        'code',
        'principal',
        'interest_rate',
        'term_months',
        'frequency',
        'start_date',
        'end_date',
        'total_amount',
        'installment_amount',
        'late_fee_rate',
        'penalty_rate',
        'status',
        'next_due_date',
        'risk_score',
        'purpose',
        'notes',
        'disbursement_method',
        'disbursement_date',
        'disbursement_reference',
        'disbursement_notes',
        'disbursed_by',
        'approved_by',
        'approved_at',
        'approval_notes',
        'has_collateral',
        'collateral_type',
        'collateral_description',
        'collateral_value',
        'has_guarantor',
        'guarantor_name',
        'guarantor_phone',
        'guarantor_relationship',
        'guarantor_address',
        'guarantor_dui',
    ];

    protected $casts = [
        'principal' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'installment_amount' => 'decimal:2',
        'late_fee_rate' => 'decimal:2',
        'penalty_rate' => 'decimal:2',
        'risk_score' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'next_due_date' => 'date',
        'disbursement_date' => 'date',
        'approved_at' => 'datetime',
        'collateral_value' => 'decimal:2',
        'has_collateral' => 'boolean',
        'has_guarantor' => 'boolean',
        'status' => LoanStatus::class,
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function disburser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disbursed_by');
    }

    public function installments(): HasMany
    {
        return $this->hasMany(Installment::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', LoanStatus::Active);
    }

    public function overdueInstallments()
    {
        return $this->installments()->where('status', InstallmentStatus::Overdue);
    }

    public function toSearchableArray()
    {
        return [
            'code' => $this->code,
            'purpose' => $this->purpose,
        ];
    }
}
