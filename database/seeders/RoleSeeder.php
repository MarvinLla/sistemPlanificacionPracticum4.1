<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. CREAR O BUSCAR ROLES (Usamos firstOrCreate para evitar el error)
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $user  = Role::firstOrCreate(['name' => 'usuario']);

        // 2. CREAR O BUSCAR PERMISOS
        // Entidades
        Permission::firstOrCreate(['name' => 'ver entidades'])->assignRole([$admin, $user]);
        Permission::firstOrCreate(['name' => 'crear entidades'])->assignRole($admin);
        Permission::firstOrCreate(['name' => 'editar entidades'])->assignRole($admin);
        
        // Programas
        Permission::firstOrCreate(['name' => 'ver programas'])->assignRole([$admin, $user]);
        Permission::firstOrCreate(['name' => 'crear programas'])->assignRole($admin);

        // Usuarios
        Permission::firstOrCreate(['name' => 'gestionar usuarios'])->assignRole($admin);

        // Proyectos
        Permission::firstOrCreate(['name' => 'ver proyectos'])->assignRole([$admin, $user]);
        Permission::firstOrCreate(['name' => 'crear proyectos'])->assignRole($admin);
        Permission::firstOrCreate(['name' => 'editar proyectos'])->assignRole($admin);
        Permission::firstOrCreate(['name' => 'cambiar estados'])->assignRole($admin);
        
        // NUEVOS PERMISOS (Agrégalos aquí abajo siguiendo el mismo formato)
   
        // Permission::firstOrCreate(['name' => 'borrar proyectos'])->assignRole($admin);

        // 3. ASIGNAR ROL AL ADMINISTRADOR INICIAL
        $myUser = User::where('email', 'admin@gmail.com')->first();
        if ($myUser) {
            $myUser->assignRole($admin);
        }
    }
}