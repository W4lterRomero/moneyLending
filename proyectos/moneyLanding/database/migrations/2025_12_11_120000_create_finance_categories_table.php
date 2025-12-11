<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('finance_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['income', 'expense']);
            $table->timestamps();
            
            $table->unique(['name', 'type']);
        });
        
        // Seed default categories
        $defaults = [
            ['name' => 'Salario', 'type' => 'income'],
            ['name' => 'Ventas', 'type' => 'income'],
            ['name' => 'Préstamos cobrados', 'type' => 'income'],
            ['name' => 'Intereses', 'type' => 'income'],
            ['name' => 'Otros ingresos', 'type' => 'income'],
            ['name' => 'Comida', 'type' => 'expense'],
            ['name' => 'Transporte', 'type' => 'expense'],
            ['name' => 'Servicios', 'type' => 'expense'],
            ['name' => 'Renta', 'type' => 'expense'],
            ['name' => 'Préstamos otorgados', 'type' => 'expense'],
            ['name' => 'Otros gastos', 'type' => 'expense'],
        ];
        
        foreach ($defaults as $cat) {
            \DB::table('finance_categories')->insert([
                'name' => $cat['name'],
                'type' => $cat['type'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_categories');
    }
};
