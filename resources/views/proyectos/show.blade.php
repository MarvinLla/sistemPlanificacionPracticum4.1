@extends('layouts.app')

@section('content')
<div style="max-width: 900px; margin: auto; background: white; padding: 40px; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
    
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #f1f5f9; padding-bottom: 20px; margin-bottom: 30px;">
        <h2 style="font-size: 1.8rem; font-weight: bold; color: #1e293b; margin: 0;">
            Detalles del Proyecto
        </h2>
        <span style="padding: 8px 16px; border-radius: 20px; font-weight: bold; background: {{ $proyecto->estado == 'aprobado' ? '#dcfce7' : '#fef9c3' }}; color: {{ $proyecto->estado == 'aprobado' ? '#166534' : '#854d0e' }};">
            ● {{ $proyecto->estado }}
        </span>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
        <div>
            <p style="margin-bottom: 20px;"><strong style="color: #64748b; display: block;">Nombre del Proyecto:</strong> 
            <span style="font-size: 1.1rem; color: #1e293b;">{{ $proyecto->nombre }}</span></p>

            <p style="margin-bottom: 20px;"><strong style="color: #64748b; display: block;">Entidad Responsable:</strong> 
            {{ $proyecto->entidad->nombre }}</p>

            <p style="margin-bottom: 20px;"><strong style="color: #64748b; display: block;">Presupuesto Estimado:</strong> 
            <span style="color: #059669; font-weight: bold;">${{ number_format($proyecto->presupuesto_estimado, 2) }}</span></p>
        </div>

        <div>
            <p style="margin-bottom: 20px;"><strong style="color: #64748b; display: block;">Responsable:</strong> 
            {{ $proyecto->responsable }}</p>

            <p style="margin-bottom: 20px;"><strong style="color: #64748b; display: block;">Contacto:</strong> 
            {{ $proyecto->correo_contacto }} / {{ $proyecto->telefono_contacto }}</p>

            <p style="margin-bottom: 20px;"><strong style="color: #64748b; display: block;">Vigencia:</strong> 
            {{ $proyecto->fecha_inicio }} hasta {{ $proyecto->fecha_final }}</p>
        </div>
    </div>

    <div style="margin-top: 10px; padding: 20px; background: #f8fafc; border-radius: 10px;">
        <strong style="color: #64748b; display: block; margin-bottom: 10px;">Descripción:</strong>
        <p style="color: #334155; line-height: 1.6;">{{ $proyecto->descripcion }}</p>
    </div>

    <div style="margin-bottom: 20px;">
           <strong style="color: #64748b; display: block; margin-bottom: 10px;">Objetivos Generales</strong>
            <p style="color: #334155; line-height: 1.6;">{{ $proyecto->objetivos ?: 'No especificados' }}</p>
    </div>
        

    <div style="margin-top: 20px; padding: 20px; background: #f8fafc; border-radius: 10px;">
        <strong style="color: #64748b; display: block; margin-bottom: 10px;">Objetivos ODS:</strong>
        <p style="color: #334155; line-height: 1.6;">{{ $proyecto->objetivos_ods ?: 'No especificados' }}</p>
    </div>

    <div style="margin-top: 30px; display: flex; gap: 15px;">
        <a href="{{ route('proyectos.index') }}" style="background: #64748b; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: bold;">
            Volver al Listado
        </a>
        
        @can('editar proyectos')
        <a href="{{ route('proyectos.edit', $proyecto->id) }}" style="background: #2563eb; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: bold;">
            Editar Proyecto
        </a>
        @endcan
    </div>
</div>
@endsection