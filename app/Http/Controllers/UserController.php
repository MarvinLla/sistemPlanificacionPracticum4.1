<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash; // Importación correcta
use Illuminate\Support\Facades\Auth; // Para la validación de borrado

class UserController extends Controller
{
    /**
     * Listado de usuarios con sus roles cargados.
     */
    public function index()
    {
        // Cargamos roles y permisos para evitar múltiples consultas a la BD
        $usuarios = User::with(['roles', 'permissions'])->get(); 
        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Formulario de creación.
     */
    public function create()
    {
        $roles = Role::all();
        return view('usuarios.create', compact('roles'));
    }

    /**
     * Guardar nuevo usuario con rol.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', 
            'rol'      => 'required'
        ]);

        // CREACIÓN DEL USUARIO
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password), // CORREGIDO: H mayúscula
        ]);

        // ASIGNACIÓN DE ROL
        $user->assignRole($request->rol);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente');
    }

    /**
     * Formulario para editar datos y permisos específicos.
     */
    public function edit(string $id)
    {
        $usuario = User::findOrFail($id);
    $roles = Role::all();
    // Esto es vital para que aparezcan los cuadritos en tu vista
    $permisos = Permission::all(); 
    
    return view('usuarios.edit', compact('usuario', 'roles', 'permisos'));
    }

    /**
     * Actualizar datos, rol y permisos específicos.
     */
    public function update(Request $request, string $id)
    {
        $usuario = User::findOrFail($id);

    // Validaciones básicas
    $request->validate([
        'name'  => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id,
        'rol'   => 'required',
    ]);

    $data = ['name' => $request->name, 'email' => $request->email];

    // Si escribiste una contraseña, la actualizamos
    if ($request->filled('password')) {
        $request->validate(['password' => 'min:8']);
        $data['password'] = bcrypt($request->password);
    }

    $usuario->update($data);

    // Sincronizar el Rol
    $usuario->syncRoles($request->rol);

    // Sincronizar Permisos Individuales (los cuadritos que marques)
    // Si no marcas ninguno, enviamos un array vacío para limpiar
    $usuario->syncPermissions($request->permisos ?? []);

    return redirect()->route('usuarios.index')
        ->with('success', 'Usuario y permisos actualizados correctamente.');
    }

    /**
     * Eliminar usuario con protección.
     */
    public function destroy(string $id)
    {
        $usuario = User::findOrFail($id);
        
        // No permitir que el usuario logueado se elimine a sí mismo
        if (Auth::id() == $usuario->id) {
            return redirect()->route('usuarios.index')->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $usuario->delete();
        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado.');
    }
}