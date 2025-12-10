<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientReference extends Model
{
    protected $fillable = [
        'client_id',
        'type',
        'name',
        'phone',
        'second_phone',
        'email',
        'relationship',
        'address',
        'occupation',
        'verified',
        'verified_at',
        'verified_by',
        'verification_notes',
        'priority',
        'notes',
    ];

    protected $casts = [
        'verified' => 'boolean',
        'verified_at' => 'datetime',
        'priority' => 'integer',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getTypeNameAttribute(): string
    {
        return match ($this->type) {
            'personal' => 'Personal',
            'family' => 'Familiar',
            'work' => 'Laboral',
            default => $this->type,
        };
    }
}
