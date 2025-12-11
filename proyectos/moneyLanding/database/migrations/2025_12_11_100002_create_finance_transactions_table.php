<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('finance_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade');
            $table->enum('type', ['income', 'expense']);
            $table->decimal('amount', 12, 2);
            $table->string('category');                      // e.g., "Food", "Transport", "Payout"
            $table->string('description')->nullable();
            $table->date('transaction_date');
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['account_id', 'transaction_date']);
            $table->index('type');
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_transactions');
    }
};
