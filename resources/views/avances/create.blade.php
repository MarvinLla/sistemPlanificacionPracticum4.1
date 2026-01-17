@extends('layouts.app')

@section('content')
<div style="max-width: 700px; margin: auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
    <h2 style="margin-bottom: 20px; color: #1e293b;">Registrar Avance de Proyecto</h2>

    {{-- Mensaje de Error General --}}
    @if ($errors->any())
        <div style="background: #fee2e2; border: 1px solid #ef4444; color: #b91c1c; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
            <strong>⚠️ Por favor corrige los errores:</strong>
            <ul style="margin-top: 5px; font-size: 0.9rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('avances.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Seleccionar Proyecto</label>
            <select name="proyecto_id" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid {{ $errors->has('proyecto_id') ? '#ef4444' : '#cbd5e1' }};">
                <option value="">Elija un proyecto...</option>
                @foreach($proyectos as $proy)
                    <option value="{{ $proy->id }}" {{ old('proyecto_id') == $proy->id ? 'selected' : '' }}>
                        {{ $proy->nombre }} (Disponible: ${{ number_format($proy->presupuestoRestante(), 2) }})
                    </option>
                @endforeach
            </select>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
            <div>
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Título del Avance</label>
                <input type="text" name="titulo" value="{{ old('titulo') }}" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid {{ $errors->has('titulo') ? '#ef4444' : '#cbd5e1' }};">
            </div>
            <div>
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Monto a Ejecutar ($)</label>
                <input type="number" name="monto_gastado" value="{{ old('monto_gastado') }}" step="0.01" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid {{ $errors->has('monto_gastado') ? '#ef4444' : '#cbd5e1' }};">
                @error('monto_gastado')
                    <span style="color: #ef4444; font-size: 0.8rem; font-weight: bold;">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Descripción de actividades</label>
            <textarea name="descripcion" rows="3" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid {{ $errors->has('descripcion') ? '#ef4444' : '#cbd5e1' }};">{{ old('descripcion') }}</textarea>
            @error('descripcion')
                <span style="color: #ef4444; font-size: 0.8rem; font-weight: bold;">{{ $message }}</span>
            @enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
            <div>
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Subir Foto (Opcional)</label>
                <input type="file" name="foto" accept="image/*">
            </div>
            <div>
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Subir Documento (Opcional)</label>
                <input type="file" name="archivo" accept=".pdf,.docx,.zip">
            </div>
        </div>

        <input type="hidden" name="fecha_avance" value="{{ date('Y-m-d') }}">

        <button type="submit" style="width: 100%; background: #059669; color: white; padding: 12px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; transition: background 0.3s;">
            ✅ Guardar Avance y Restar Presupuesto
        </button>
    </form>
</div>
@endsection