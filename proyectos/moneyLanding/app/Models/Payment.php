<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Laravel\Scout\Searchable;

class Payment extends Model
{
    use HasFactory, Searchable;

    protected static function booted(): void
    {
        // Invalidar caché de KPIs cuando se cree, actualice o elimine un pago
        static::saved(fn () => self::clearKpiCache());
        static::deleted(fn () => self::clearKpiCache());
    }

    /**
     * Limpia todas las claves de caché de KPIs
     */
    public static function clearKpiCache(): void
    {
        // Limpiar caché sin rango de fechas
        Cache::forget('kpi:all:null:null');

        // Limpiar cachés con rangos de fechas predefinidos
        $now = now();

        // Today
        $todayStart = $now->copy()->startOfDay()->timestamp;
        $todayEnd = $now->copy()->endOfDay()->timestamp;
        Cache::forget("kpi:today:{$todayStart}:{$todayEnd}");

        // Week
        $weekStart = $now->copy()->startOfWeek()->timestamp;
        $weekEnd = $now->copy()->endOfWeek()->timestamp;
        Cache::forget("kpi:week:{$weekStart}:{$weekEnd}");

        // Month (default)
        $monthStart = $now->copy()->startOfMonth()->timestamp;
        $monthEnd = $now->copy()->endOfMonth()->timestamp;
        Cache::forget("kpi:month:{$monthStart}:{$monthEnd}");
        Cache::forget("kpi:null:{$monthStart}:{$monthEnd}");

        // Year
        $yearStart = $now->copy()->startOfYear()->timestamp;
        $yearEnd = $now->copy()->endOfYear()->timestamp;
        Cache::forget("kpi:year:{$yearStart}:{$yearEnd}");
    }

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
        'photo_path',
        'receipt_path',
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
