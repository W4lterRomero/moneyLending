<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->enum('disbursement_method', ['cash', 'bank_transfer', 'check', 'mobile_payment'])->nullable()->after('purpose');
            $table->date('disbursement_date')->nullable()->after('disbursement_method');
            $table->string('disbursement_reference')->nullable()->after('disbursement_date');
            $table->text('disbursement_notes')->nullable()->after('disbursement_reference');
            $table->foreignId('disbursed_by')->nullable()->constrained('users')->nullOnDelete()->after('disbursement_notes');

            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete()->after('disbursed_by');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->text('approval_notes')->nullable()->after('approved_at');

            $table->boolean('has_collateral')->default(false)->after('approval_notes');
            $table->string('collateral_type')->nullable()->after('has_collateral');
            $table->text('collateral_description')->nullable()->after('collateral_type');
            $table->decimal('collateral_value', 15, 2)->nullable()->after('collateral_description');

            $table->boolean('has_guarantor')->default(false)->after('collateral_value');
            $table->string('guarantor_name')->nullable()->after('has_guarantor');
            $table->string('guarantor_phone')->nullable()->after('guarantor_name');
            $table->string('guarantor_relationship')->nullable()->after('guarantor_phone');
            $table->text('guarantor_address')->nullable()->after('guarantor_relationship');
            $table->string('guarantor_dui')->nullable()->after('guarantor_address');

            $table->index('disbursement_date');
            $table->index('approved_at');
        });
    }

    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropIndex(['disbursement_date']);
            $table->dropIndex(['approved_at']);

            $table->dropConstrainedForeignId('disbursed_by');
            $table->dropConstrainedForeignId('approved_by');

            $table->dropColumn([
                'disbursement_method',
                'disbursement_date',
                'disbursement_reference',
                'disbursement_notes',
                'approved_at',
                'approval_notes',
                'has_collateral',
                'collateral_type',
                'collateral_description',
                'collateral_value',
                'has_guarantor',
                'guarantor_name',
                'guarantor_phone',
                'guarantor_relationship',
                'guarantor_address',
                'guarantor_dui',
            ]);
        });
    }
};
