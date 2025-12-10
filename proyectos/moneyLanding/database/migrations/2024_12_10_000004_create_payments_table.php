<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('installment_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('recorded_by')->nullable()->references('id')->on('users')->nullOnDelete();
            $table->date('paid_at');
            $table->decimal('amount', 15, 2);
            $table->decimal('interest_amount', 15, 2)->default(0);
            $table->decimal('principal_amount', 15, 2)->default(0);
            $table->enum('method', ['cash', 'transfer', 'card', 'deposit'])->default('cash');
            $table->string('reference')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
