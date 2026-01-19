@extends('layouts.app')

@section('content')
<div style="max-width: 1000px; margin: auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; border-bottom: 2px solid #f1f5f9; padding-bottom: 15px;">
        <h2 style="font-size: 1.6rem; font-weight: bold; color: #1e293b; margin: 0;">
            Gestión del Plan Nacional de Desarrollo (PND)
        </h2>
        <a href="{{ route('pnd.create') }}" style="background: #2563eb; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 0.9rem;">
            + Nuevo Objetivo
        </a>
    </div>

    @if(session('success'))
        <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                <th style="padding: 12px; text-align: left; color: #64748b;">Eje Estratégico</th>
                <th style="padding: 12px; text-align: left; color: #64748b;">Objetivo</th>
                <th style="padding: 12px; text-align: center; color: #64748b;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($objetivos as $obj)
            <tr style="border-bottom: 1px solid #f1f5f9;">
                <td style="padding: 15px;">
                    <span style="background: #eff6ff; color: #1e40af; padding: 4px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: bold;">
                        {{ $obj->eje }}
                    </span>
                </td>
                <td style="padding: 15px; color: #1e293b; font-weight: 500;">
                    {{ $obj->nombre_objetivo }}
                </td>
                <td style="padding: 15px; text-align: center;">
                    <a href="{{ route('pnd.edit', $obj->id) }}" style="color: #3b82f6; text-decoration: none; margin-right: 10px;">Editar</a>
                    <form action="{{ route('pnd.destroy', $obj->id) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" style="color: #ef4444; background: none; border: none; cursor: pointer;" onclick="return confirm('¿Eliminar este objetivo?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection