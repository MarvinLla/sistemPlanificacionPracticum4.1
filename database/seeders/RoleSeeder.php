<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash; // ¡IMPORTANTE! Agregamos esta línea

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. PRIMERO CREAMOS LOS ROLES (para poder usarlos después)
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $user  = Role::firstOrCreate(['name' => 'usuario']);

        // 2. CREAMOS EL USUARIO (usando el rol que acabamos de crear arriba)
        $adminUser = User::updateOrCreate(
    ['email' => 'admin@gmail.com'], // Condición de búsqueda
    [
        'name' => 'Administrador',
        'password' => Hash::make('12345678'),
    ]
);
        
        // Asignamos el rol al usuario
        $adminUser->assignRole($admin);

        // 3. CREAR PERMISOS Y ASIGNARLOS AL ROL
        // Entidades (Nota: quitamos [$admin, $user] y dejamos solo $admin para que el usuario no vea el cuadro)
        Permission::firstOrCreate(['name' => 'ver entidades'])->assignRole($admin);
        Permission::firstOrCreate(['name' => 'crear entidades'])->assignRole($admin);
        Permission::firstOrCreate(['name' => 'editar entidades'])->assignRole($admin);
        
        // Programas
        Permission::firstOrCreate(['name' => 'ver programas'])->assignRole($admin);
        Permission::firstOrCreate(['name' => 'crear programas'])->assignRole($admin);

        // Usuarios
        Permission::firstOrCreate(['name' => 'gestionar usuarios'])->assignRole($admin);

        // Proyectos
        Permission::firstOrCreate(['name' => 'ver proyectos'])->assignRole($admin);
        Permission::firstOrCreate(['name' => 'crear proyectos'])->assignRole($admin);
        Permission::firstOrCreate(['name' => 'editar proyectos'])->assignRole($admin);
        Permission::firstOrCreate(['name' => 'cambiar estados'])->assignRole($admin);

        // ODS
        Permission::firstOrCreate(['name' => 'ver ODS'])->assignRole($admin);

        Permission::firstOrCreate(['name' => 'ver alertas'])->assignRole($admin);
    }
}