<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('photo_path')->nullable()->after('tags');
            $table->string('document_path')->nullable()->after('photo_path');
            $table->timestamp('archived_at')->nullable()->after('document_path');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['photo_path', 'document_path', 'archived_at']);
        });
    }
};
