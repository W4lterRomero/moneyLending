<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // Datos personales adicionales
            $table->string('second_phone')->nullable()->after('phone');
            $table->enum('gender', ['male', 'female', 'other', 'prefer_not_to_say'])->nullable()->after('birth_date');
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed', 'separated'])->nullable();
            $table->integer('dependents')->default(0);
            $table->string('nationality')->default('El Salvador');
            $table->string('occupation')->nullable();
            $table->string('place_of_birth')->nullable();

            // Información laboral
            $table->string('company_name')->nullable();
            $table->string('job_title')->nullable();
            $table->enum('employment_type', ['permanent', 'temporary', 'freelance', 'self_employed', 'unemployed'])->nullable();
            $table->decimal('monthly_income', 10, 2)->nullable();
            $table->string('work_phone')->nullable();
            $table->text('work_address')->nullable();
            $table->date('employment_start_date')->nullable();
            $table->string('supervisor_name')->nullable();
            $table->string('supervisor_phone')->nullable();

            // Información bancaria
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->enum('bank_account_type', ['savings', 'checking'])->nullable();

            // Índices
            $table->index('company_name');
            $table->index('monthly_income');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropIndex(['company_name']);
            $table->dropIndex(['monthly_income']);

            $table->dropColumn([
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
            ]);
        });
    }
};
