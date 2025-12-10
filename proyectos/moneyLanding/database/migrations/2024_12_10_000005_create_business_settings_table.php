<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_settings', function (Blueprint $table) {
            $table->id();
            $table->string('business_name')->default('Finanzas');
            $table->string('legal_id')->nullable();
            $table->string('currency')->default('USD');
            $table->string('timezone')->default('UTC');
            $table->decimal('default_interest_rate', 5, 2)->default(12);
            $table->decimal('default_penalty_rate', 5, 2)->default(0);
            $table->json('contract_templates')->nullable();
            $table->json('notification_channels')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_settings');
    }
};
