<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->index('name');
            $table->index('email');
            $table->index('phone');
        });

        Schema::table('loans', function (Blueprint $table) {
            $table->index('code');
            $table->index(['client_id', 'status']);
            $table->index('start_date');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->index('paid_at');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['email']);
            $table->dropIndex(['phone']);
        });

        Schema::table('loans', function (Blueprint $table) {
            $table->dropIndex(['code']);
            $table->dropIndex(['client_id', 'status']);
            $table->dropIndex(['start_date']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['paid_at']);
        });
    }
};
