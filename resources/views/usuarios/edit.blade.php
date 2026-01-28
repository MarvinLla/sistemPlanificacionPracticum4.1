@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            
            <div style="margin-bottom: 20px; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px;">
                <h2 style="font-size: 1.5rem; font-weight: bold; color: #1e293b;">
                     Editar Usuario: {{ $usuario->name }}
                </h2>
            </div>

            <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px;">
                    <div>
                        <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Nombre Completo</label>
                        <input type="text" name="name" value="{{ old('name', $usuario->name) }}" required 
                               style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
                    </div>
                    <div>
                        <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Correo Electrónico</label>
                        <input type="email" name="email" value="{{ old('email', $usuario->email) }}" required 
                               style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
                    </div>
                </div>

                <div style="margin-bottom: 25px; background: #fffbeb; padding: 15px; border-radius: 10px; border: 1px solid #fef3c7;">
                    <label style="display: block; color: #92400e; font-weight: 600; margin-bottom: 5px;">Nueva Contraseña (Opcional)</label>
                    <input type="password" name="password" placeholder="Dejar en blanco para no cambiar"
                           style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
                </div>

                <div style="margin-bottom: 25px; background: #f8fafc; padding: 15px; border-radius: 10px; border: 1px solid #e2e8f0;">
                    <h3 style="font-size: 1rem; font-weight: bold; color: #334155; margin-bottom: 10px;">Rol del Sistema</h3>
                    <select name="rol" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
                        @foreach($roles as $rol)
                            <option value="{{ $rol->name }}" {{ $usuario->hasRole($rol->name) ? 'selected' : '' }}>
                                {{ ucfirst($rol->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 30px;">
                    <h3 style="font-size: 1rem; font-weight: bold; color: #334155; margin-bottom: 10px;">Permisos Individuales</h3>
                    
                    @if($permisos->isEmpty())
                        <div style="padding: 20px; background: #fee2e2; color: #b91c1c; border-radius: 8px; border: 1px solid #fecaca;">
                            ⚠️ No hay permisos registrados en la base de datos. Ejecuta los comandos en la terminal.
                        </div>
                    @else
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px;">
                            @foreach($permisos as $permiso)
                                <label style="display: flex; align-items: center; padding: 10px; background: white; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer;">
                                    <input type="checkbox" name="permisos[]" value="{{ $permiso->name }}" 
                                        {{ $usuario->hasDirectPermission($permiso->name) ? 'checked' : '' }}
                                        style="width: 18px; height: 18px; margin-right: 10px;">
                                    <span style="font-size: 0.9rem; color: #475569;">{{ ucfirst($permiso->name) }}</span>
                                </label>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div style="display: flex; gap: 15px;">
                    <button type="submit" style="flex: 2; background: #2563eb; color: white; padding: 12px; border: none; border-radius: 8px; cursor: pointer; font-weight: bold;">
                        Actualizar Usuario
                    </button>
                    <a href="{{ route('usuarios.index') }}" style="flex: 1; text-align: center; background: #f1f5f9; color: #475569; padding: 12px; border-radius: 8px; text-decoration: none;">
                        Cancelar
                    </a>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection