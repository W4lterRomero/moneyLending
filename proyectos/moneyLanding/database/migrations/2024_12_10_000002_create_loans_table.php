<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('code')->unique();
            $table->decimal('principal', 15, 2);
            $table->decimal('interest_rate', 5, 2);
            $table->integer('term_months');
            $table->enum('frequency', ['monthly', 'biweekly', 'weekly']);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->decimal('installment_amount', 15, 2)->default(0);
            $table->decimal('late_fee_rate', 5, 2)->default(0);
            $table->decimal('penalty_rate', 5, 2)->default(0);
            $table->enum('status', ['draft', 'active', 'completed', 'delinquent', 'cancelled'])->default('draft')->index();
            $table->date('next_due_date')->nullable();
            $table->decimal('risk_score', 5, 2)->nullable();
            $table->string('purpose')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
