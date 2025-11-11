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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Usuario agregado según solicitud: Dani
        User::factory()->create([
            'name' => 'Dani',
            'email' => '12450107@upq.edu.mx',
            // modelo User tiene el cast 'password' => 'hashed', así que asignar texto plano es seguro
            'password' => '12345678',
        ]);
    }
}
