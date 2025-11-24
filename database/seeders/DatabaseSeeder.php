<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    // Mantenemos el trait si lo usas, pero no es necesario aquí
    // use WithoutModelEvents; 

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 🚨 COMENTAMOS O ELIMINAMOS EL USUARIO DE PRUEBA POR DEFECTO:
        // User::factory(10)->create();
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // 🟢 NUEVA LÍNEA: LLAMAMOS A TU SEEDER DE ADMINISTRADOR
        $this->call(AdminSeeder::class);
    }
}