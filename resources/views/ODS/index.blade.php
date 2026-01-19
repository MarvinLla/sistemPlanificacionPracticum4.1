@extends('layouts.app')

@section('title', 'Objetivos ODS')

@section('content')

    <h2 class="text-2xl font-bold mb-4">LISTADO OBJETIVOS ODS:</h2>

    {{-- Mensaje de éxito --}}
    @if(session('success'))
        <div style="background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
            {{ session('success') }} 
        </div>
    @endif

    {{-- Botón para crear nuevo objetivo --}}
    <div style="margin-bottom: 20px;">
        <a href="{{ route('ODS.create') }}" class="btn btn-primary" style="background-color: #4A90E2; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;">
            + Nuevo objetivo
        </a>
    </div>

    {{-- Tabla para listar los ODS --}}
    <table style="width: 100%; border-collapse: collapse; background-color: #f8f8fa">
        <thead> 
            <tr style="background-color: #eee;">
                <th style="border: 1px solid #ccc; padding: 8px">ID</th>
                <th style="border: 1px solid #ccc; padding: 8px">ODS</th>
                <th style="border: 1px solid #ccc; padding: 8px">METAS ASOCIADAS</th>
                <th style="border: 1px solid #ccc; padding: 8px">ACCIONES</th>
            </tr>
        </thead>

        <tbody>
    @foreach($objetivosODS as $objetivoODS)
    <tr>
        <td style="border: 1px solid #ccc; padding: 12px; text-align: center; font-weight: bold;">
            {{ $objetivoODS->id }}
        </td>
        <td style="border: 1px solid #ccc; padding: 12px;">
            <strong style="display: block; color: #2563eb;">{{ $objetivoODS->nombreObjetivo }}</strong>
            <span style="font-size: 0.9rem; color: #64748b;">{{ $objetivoODS->descripcion }}</span>
        </td>
        {{-- COLUMNA DE METAS MEJORADA --}}
        <td style="border: 1px solid #ccc; padding: 12px; font-size: 0.85rem;">
            @if($objetivoODS->metasAsociadas)
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach(explode("\n", $objetivoODS->metasAsociadas) as $meta)
                        @if(trim($meta) != "")
                            <li style="margin-bottom: 4px;">{{ $meta }}</li>
                        @endif
                    @endforeach
                </ul>
            @else
                <span style="color: #94a3b8; font-style: italic;">Sin metas</span>
            @endif
        </td>
        <td style="border: 1px solid #ccc; padding: 12px; text-align: center;">
            <div style="display: flex; gap: 10px; justify-content: center;">
                <a href="{{ route('ODS.edit', $objetivoODS->id) }}" 
                   style="color: #2563eb; text-decoration: none; font-weight: 600;">Editar</a>
                
                <form action="{{ route('ODS.destroy', $objetivoODS->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este ODS?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="color: #ef4444; background: none; border: none; cursor: pointer; font-weight: 600; padding: 0;">
                        Eliminar
                    </button>
                </form>
            </div>
        </td>
    </tr>   
    @endforeach
</tbody>
    </table>
@endsection