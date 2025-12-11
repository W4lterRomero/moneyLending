<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
    ];
    
    public function scopeIncome($query)
    {
        return $query->where('type', 'income');
    }
    
    public function scopeExpense($query)
    {
        return $query->where('type', 'expense');
    }
}
