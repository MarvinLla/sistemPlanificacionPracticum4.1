@extends('layouts.app')

@section('title', 'Editar Programa')

@section('content')
<div style="max-width: 700px; margin: auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
    
    <h2 style="font-size: 1.6rem; font-weight: bold; color: #1e293b; margin-bottom: 25px; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px;">
        Editar Programa: {{ $programa->nombrePrograma }}
    </h2>

    {{-- Alerta de Errores de Validación --}}
    @if ($errors->any())
        <div style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <strong>¡Atención!</strong> Por favor corrige los errores:
            <ul style="margin-top: 5px; margin-left: 20px; font-size: 0.9rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('programas.update', $programa->id) }}" method="POST" style="display: flex; flex-direction: column; gap: 20px;">
        @csrf
        @method('PUT')

        {{-- Nombre del Programa --}}
        <div>
            <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Nombre del Programa</label>
            <input type="text" name="nombrePrograma" value="{{ old('nombrePrograma', $programa->nombrePrograma) }}" required 
                style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 1rem;">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            {{-- Tipo de Programa --}}
            <div>
                <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Tipo</label>
                <input type="text" name="tipoPrograma" value="{{ old('tipoPrograma', $programa->tipoPrograma) }}" required 
                    style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 1rem;">
            </div>

            {{-- Versión --}}
            <div>
                <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Versión</label>
                <input type="text" name="version" value="{{ old('version', $programa->version) }}" required 
                    style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 1rem;">
            </div>
        </div>

        {{-- Responsable --}}
        <div>
            <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Responsable del Programa</label>
            <input type="text" name="responsablePrograma" value="{{ old('responsablePrograma', $programa->responsablePrograma) }}" required 
                style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 1rem;">
        </div>

        {{-- Botones de Acción --}}
        <div style="display: flex; gap: 15px; border-top: 1px solid #f1f5f9; padding-top: 25px; margin-top: 10px;">
            <button type="submit" style="flex: 2; background: #2563eb; color: white; padding: 12px; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 1rem; transition: background 0.2s;">
                💾 Actualizar Programa
            </button>
            <a href="{{ route('programas.index') }}" style="flex: 1; text-align: center; background: #f1f5f9; color: #475569; padding: 12px; border-radius: 8px; text-decoration: none; font-weight: bold; line-height: 1.5;">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection