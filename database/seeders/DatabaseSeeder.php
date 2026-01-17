<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
{
    // 1. Primero creamos los roles
    $this->call([
        RoleSeeder::class,
    ]);

    // 2. Creamos tu usuario administrador directamente aquí
    $admin = User::factory()->create([
        'name' => 'Administrador',
        'email' => 'admin@gmail.com', // <--- USA ESTE PARA LOGUEARTE
        'password' => bcrypt('123456'), // Pon una contraseña que recuerdes
    ]);

    // 3. Le asignamos el rol que acabamos de crear en el RoleSeeder
    $admin->assignRole('admin');
}
}
