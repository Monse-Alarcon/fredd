<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Usuario
        User::create([
            'name' => 'Usuario',
            'email' => 'usuario@ejemplo.com',
            'password' => '12345678',
            'role' => 'usuario',
            'departamento' => 'General',
            'email_verified_at' => now(),
        ]);

        // Auxiliar
        User::create([
            'name' => 'Auxiliar',
            'email' => 'auxiliar@ejemplo.com',
            'password' => '12345678',
            'role' => 'auxiliar',
            'departamento' => 'Soporte',
            'email_verified_at' => now(),
        ]);

        // Jefe
        User::create([
            'name' => 'Jefe',
            'email' => 'jefe@ejemplo.com',
            'password' => '12345678',
            'role' => 'jefe',
            'departamento' => 'Administración',
            'email_verified_at' => now(),
        ]);
    }
}
