<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_references', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['personal', 'family', 'work'])->index();
            $table->string('name');
            $table->string('phone');
            $table->string('second_phone')->nullable();
            $table->string('email')->nullable();
            $table->string('relationship');
            $table->text('address')->nullable();
            $table->string('occupation')->nullable();
            $table->boolean('verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('verification_notes')->nullable();
            $table->integer('priority')->default(1);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['client_id', 'type']);
            $table->index('verified');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_references');
    }
};
