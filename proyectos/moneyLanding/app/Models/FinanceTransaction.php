<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinanceTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'type',
        'amount',
        'category',
        'description',
        'transaction_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Boot method to handle balance updates on create/update/delete
     */
    protected static function booted(): void
    {
        // When transaction is created, update account balance
        static::created(function (FinanceTransaction $transaction) {
            $transaction->updateAccountBalance('add');
        });

        // When transaction is updated, recalculate entire balance (safest approach)
        static::updated(function (FinanceTransaction $transaction) {
            $transaction->account->recalculateBalance();
        });

        // When transaction is deleted, update account balance
        static::deleted(function (FinanceTransaction $transaction) {
            $transaction->updateAccountBalance('subtract');
        });
    }

    /**
     * Update the account balance based on transaction type
     */
    protected function updateAccountBalance(string $operation): void
    {
        $account = $this->account;
        
        if ($operation === 'add') {
            if ($this->type === 'income') {
                $account->current_balance += $this->amount;
            } else {
                $account->current_balance -= $this->amount;
            }
        } else { // subtract (for deletion)
            if ($this->type === 'income') {
                $account->current_balance -= $this->amount;
            } else {
                $account->current_balance += $this->amount;
            }
        }
        
        $account->save();
    }

    /**
     * Common categories for quick access
     */
    public static function commonCategories(): array
    {
        return [
            'income' => [
                'Préstamo Cobrado',
                'Intereses',
                'Depósito',
                'Otro Ingreso',
            ],
            'expense' => [
                'Desembolso de Préstamo',
                'Comida',
                'Transporte',
                'Servicios',
                'Suministros',
                'Salario',
                'Otro Gasto',
            ],
        ];
    }
}
