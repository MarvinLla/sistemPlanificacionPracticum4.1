@extends('layouts.app')

@section('content')
<div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <h2 style="font-size: 1.8rem; font-weight: bold; color: #1e293b;">Gestión de Usuarios</h2>
        
        <a href="{{ route('usuarios.create') }}" style="background: #2563eb; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 500;">
            + Nuevo Usuario
        </a>
    </div>

    @if(session('success'))
        <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid #f1f5f9; color: #64748b; font-size: 0.9rem; text-transform: uppercase;">
                    <th style="padding: 12px;">Nombre</th>
                    <th style="padding: 12px;">Correo Electrónico</th>
                    <th style="padding: 12px;">Rol</th>
                    <th style="padding: 12px;">Acciones</th>
                </tr>
            </thead>
            <tbody style="color: #334155;">
                @foreach($usuarios as $user)
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 15px;">{{ $user->name }}</td>
                    <td style="padding: 15px;">{{ $user->email }}</td>
                    <td style="padding: 15px;">
                        @foreach($user->roles as $role)
                            <span style="background: #e2e8f0; color: #475569; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: bold;">
                                {{ $role->name }}
                            </span>
                        @endforeach
                    </td>
                    <td style="padding: 15px; display: flex; gap: 10px;">
                        <a href="{{ route('usuarios.edit', $user->id) }}" style="color: #2563eb; text-decoration: none;">Editar</a>
                        
                        <form action="{{ route('usuarios.destroy', $user->id) }}" method="POST" onsubmit="return confirm('¿Eliminar usuario?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color: #dc2626; background: none; border: none; cursor: pointer;">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach 
            </tbody>
        </table>
    </div>
</div>
@endsection 