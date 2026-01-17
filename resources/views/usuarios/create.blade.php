@extends('layouts.app')

@section('content')
<div style="max-width: 600px; margin: auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
    <h2 style="font-size: 1.5rem; font-weight: bold; color: #1e293b; margin-bottom: 20px;">Crear Nuevo Usuario</h2>
@if ($errors->any())
    <div style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <form action="{{ route('usuarios.store') }}" method="POST">
        @csrf

        <div style="margin-bottom: 15px;">
            <label style="display: block; color: #64748b; margin-bottom: 5px;">Nombre Completo</label>
            <input type="text" name="name" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; color: #64748b; margin-bottom: 5px;">Correo Electrónico</label>
            <input type="email" name="email" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; color: #64748b; margin-bottom: 5px;">Asignar Rol</label>
            <select name="rol" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                <option value="">Seleccione un rol</option>
                @foreach($roles as $rol)
                    <option value="{{ $rol->name }}">{{ ucfirst($rol->name) }}</option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; color: #64748b; margin-bottom: 5px;">Contraseña</label>
            <input type="password" name="password" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; color: #64748b; margin-bottom: 5px;">Confirmar Contraseña</label>
            <input type="password" name="password_confirmation" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" style="flex: 1; background: #2563eb; color: white; padding: 12px; border: none; border-radius: 8px; cursor: pointer; font-weight: bold;">
                Guardar Usuario
            </button>
            <a href="{{ route('usuarios.index') }}" style="flex: 1; text-align: center; background: #f1f5f9; color: #475569; padding: 12px; border-radius: 8px; text-decoration: none; font-weight: bold;">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection