@extends('layouts.app')

@section('content')
<div style="max-width: 600px; margin: auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
    <h2 style="font-size: 1.4rem; font-weight: bold; color: #1e293b; margin-bottom: 20px;">Configurar Nuevo Objetivo PND</h2>

    <form action="{{ route('pnd.store') }}" method="POST">
        @csrf
        <div style="margin-bottom: 15px;">
            <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Eje Estratégico</label>
            <input type="text" name="eje" placeholder="Ej: Eje Social, Eje Económico..." required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Nombre del Objetivo Nacional</label>
            <textarea name="nombre_objetivo" rows="3" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;" placeholder="Escriba el objetivo tal cual aparece en el Plan Nacional..."></textarea>
        </div>

        <div style="display: flex; gap: 10px; margin-top: 20px;">
            <button type="submit" style="flex: 2; background: #059669; color: white; padding: 12px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">Guardar Objetivo</button>
            <a href="{{ route('pnd.index') }}" style="flex: 1; text-align: center; background: #f1f5f9; color: #475569; padding: 12px; border-radius: 8px; text-decoration: none;">Cancelar</a>
        </div>
    </form>
</div>
@endsection