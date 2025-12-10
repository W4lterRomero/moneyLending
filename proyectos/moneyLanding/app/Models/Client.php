<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

class Client extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'document_type',
        'document_number',
        'birth_date',
        'address',
        'city',
        'country',
        'status',
        'tags',
        'notes',
        'photo_path',
        'document_path',
        'archived_at',
        'second_phone',
        'gender',
        'marital_status',
        'dependents',
        'nationality',
        'occupation',
        'place_of_birth',
        'company_name',
        'job_title',
        'employment_type',
        'monthly_income',
        'work_phone',
        'work_address',
        'employment_start_date',
        'supervisor_name',
        'supervisor_phone',
        'bank_name',
        'bank_account_number',
        'bank_account_type',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'tags' => 'array',
        'archived_at' => 'datetime',
        'monthly_income' => 'decimal:2',
        'employment_start_date' => 'date',
    ];

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ClientDocument::class);
    }

    public function references(): HasMany
    {
        return $this->hasMany(ClientReference::class)->orderBy('priority');
    }

    public function scopeWithCompleteInfo($query)
    {
        return $query->with(['documents', 'references', 'loans']);
    }

    public function getHasAllDocumentsAttribute(): bool
    {
        $required = ['dui_front', 'dui_back', 'proof_of_income'];
        $existing = $this->documents()->whereIn('type', $required)->pluck('type')->toArray();

        return count(array_diff($required, $existing)) === 0;
    }

    public function toSearchableArray()
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ];
    }
}
