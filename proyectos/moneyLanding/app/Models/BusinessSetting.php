<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_name',
        'legal_id',
        'currency',
        'timezone',
        'default_interest_rate',
        'default_penalty_rate',
        'contract_templates',
        'notification_channels',
    ];

    protected $casts = [
        'default_interest_rate' => 'decimal:2',
        'default_penalty_rate' => 'decimal:2',
        'contract_templates' => 'array',
        'notification_channels' => 'array',
    ];
}
