<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Financing extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'product_name',
        'product_image',
        'product_price',
        'balance',
        'notes',
        'status',
    ];

    protected $casts = [
        'product_price' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Amount already paid
     */
    public function getPaidAmountAttribute(): float
    {
        return $this->product_price - $this->balance;
    }

    /**
     * Payment progress percentage
     */
    public function getProgressPercentAttribute(): float
    {
        if ($this->product_price <= 0) return 100;
        return min(100, (($this->product_price - $this->balance) / $this->product_price) * 100);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }
}
