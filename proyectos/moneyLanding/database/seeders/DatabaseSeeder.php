<?php

namespace Database\Seeders;

use App\Models\BusinessSetting;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Solo crear usuario admin y configuración básica
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('mlending'),
            'role' => 'admin',
        ]);

        BusinessSetting::create([
            'business_name' => 'Lending Money',
            'currency' => 'USD',
            'default_interest_rate' => 12,
            'default_penalty_rate' => 0,
        ]);

        // No se crean clientes, préstamos ni pagos de ejemplo
        // El usuario empieza con sistema limpio
    }
}
