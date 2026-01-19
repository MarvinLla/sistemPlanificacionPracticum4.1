@extends('layouts.app')

@section('content')
<div style="max-width: 1000px; margin: auto; background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);">
    
    {{-- CABECERA --}}
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px; border-bottom: 2px solid #f1f5f9; padding-bottom: 20px;">
        <div>
            <h2 style="font-size: 1.8rem; font-weight: bold; color: #1e293b; margin: 0;">{{ $proyecto->nombre }}</h2>
            <p style="color: #64748b; margin-top: 5px;">
                ID Proyecto: #{{ $proyecto->id }} | 
                <strong>Programa:</strong> {{ $proyecto->programa->nombrePrograma ?? 'No asignado' }} |
                <strong>Entidad:</strong> {{ $proyecto->entidad->nombreEntidad ?? 'No asignada' }}
            </p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('proyectos.pdf', $proyecto->id) }}" style="background: #ef4444; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: bold;">Exportar PDF</a>
            <a href="{{ route('proyectos.edit', $proyecto->id) }}" style="background: #2563eb; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: bold;">Editar</a>
            <a href="{{ route('proyectos.index') }}" style="background: #f1f5f9; color: #475569; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: bold;">Volver</a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
        
        {{-- COLUMNA IZQUIERDA: CONTENIDO --}}
        <div>
            <h3 style="color: #1e293b; font-size: 1.1rem; border-left: 4px solid #2563eb; padding-left: 10px; margin-bottom: 15px;">Descripción y Objetivos</h3>
            <p style="color: #475569; line-height: 1.6; background: #f8fafc; padding: 15px; border-radius: 10px;">{{ $proyecto->descripcion }}</p>
            
            <h4 style="color: #1e293b; font-size: 1rem; margin-top: 20px;">Objetivos del Proyecto:</h4>
            <p style="color: #475569; font-style: italic;">{{ $proyecto->objetivos }}</p>

            {{-- SECCIÓN ODS (INTERNACIONAL) --}}
            <h3 style="color: #1e293b; font-size: 1.1rem; border-left: 4px solid #10b981; padding-left: 10px; margin-top: 30px; margin-bottom: 15px;">Impacto en ODS y Metas</h3>

            <div style="background: #f0fdf4; border: 1px solid #dcfce7; padding: 20px; border-radius: 12px; margin-bottom: 25px;">
                <h4 style="margin-top: 0; color: #166534; font-size: 0.9rem; text-transform: uppercase;">Objetivos Vinculados (Agenda 2030):</h4>
                
                <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 20px;">
                    @if($proyecto->ods && $proyecto->ods->count() > 0)
                        @foreach($proyecto->ods as $ods)
                            <span style="background: #10b981; color: white; padding: 6px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: bold; display: flex; align-items: center; gap: 5px;">
                                🌍 {{ $ods->nombreObjetivo ?? $ods->nombre ?? 'ODS identificado' }}
                            </span>
                        @endforeach
                    @else
                        <p style="color: #64748b; font-size: 0.9rem; font-style: italic;">No hay ODS vinculados.</p>
                    @endif
                </div>

                <h4 style="color: #166534; font-size: 0.9rem; text-transform: uppercase;">Metas Específicas:</h4>
                <ul style="margin: 0; padding-left: 20px; color: #374151;">
                    @php
                        $metasRaw = explode(" || ", $proyecto->metas_finales ?? '');
                        $metasArray = array_filter(array_map('trim', $metasRaw));
                    @endphp
                    @forelse($metasArray as $meta)
                        <li style="margin-bottom: 8px; line-height: 1.4; list-style-type: disc;">{{ $meta }}</li>
                    @empty
                        <li style="color: #64748b; font-size: 0.9rem; font-style: italic; list-style: none;">No hay metas específicas.</li>
                    @endforelse
                </ul>
            </div>

            {{-- SECCIÓN PND (NACIONAL) - NUEVA INTEGRACIÓN --}}
            <div style="padding: 20px; border: 1px solid #3b82f6; border-radius: 12px; background: #f0f7ff;">
                <h4 style="color: #1e40af; font-weight: bold; margin-top: 0; margin-bottom: 15px; border-bottom: 1px solid #bfdbfe; padding-bottom: 5px; font-size: 0.9rem; text-transform: uppercase;">
                    📍 Alineación con el Plan Nacional de Desarrollo (PND)
                </h4>
                <p style="margin-bottom: 8px;"><strong style="color: #1e40af;">Eje Estratégico:</strong> {{ $proyecto->pndObjetivo->eje ?? 'No definido' }}</p>
                <p style="margin-bottom: 12px;"><strong style="color: #1e40af;">Objetivo Nacional:</strong> {{ $proyecto->pndObjetivo->nombre_objetivo ?? 'No vinculado' }}</p>
                
                <div style="margin-top: 10px; font-style: italic; color: #475569; background: white; padding: 12px; border-radius: 8px; border-left: 4px solid #3b82f6;">
                    <strong>Justificación de la Problemática:</strong><br>
                    {{ $proyecto->justificacion_pnd ?? 'Sin justificación registrada.' }}
                </div>
            </div>
        </div> 

        {{-- COLUMNA DERECHA: ESTADÍSTICAS Y DATOS --}}
        <div style="background: #f8fafc; padding: 20px; border-radius: 15px; border: 1px solid #e2e8f0; height: fit-content;">
            <div style="margin-bottom: 20px;">
                <label style="display: block; color: #64748b; font-size: 0.8rem; font-weight: bold; text-transform: uppercase;">Presupuesto Estimado</label>
                <div style="font-size: 1.4rem; font-weight: bold; color: #1e293b;">${{ number_format($proyecto->presupuesto_estimado, 2) }}</div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; color: #64748b; font-size: 0.8rem; font-weight: bold; text-transform: uppercase;">Presupuesto Consumido</label>
                <div style="font-size: 1.2rem; font-weight: bold; color: #059669;">
                    ${{ number_format($proyecto->presupuestoConsumido(), 2) }}
                    <span style="font-size: 0.9rem; color: #64748b;">({{ $proyecto->porcentajePresupuesto() }}%)</span>
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; color: #64748b; font-size: 0.8rem; font-weight: bold; text-transform: uppercase;">Responsable</label>
                <div style="color: #1e293b; font-weight: 600;">{{ $proyecto->responsable }}</div>
                <div style="color: #64748b; font-size: 0.85rem;">{{ $proyecto->correo_contacto }}</div>
                <div style="color: #64748b; font-size: 0.85rem;">📞 {{ $proyecto->telefono_contacto }}</div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; color: #64748b; font-size: 0.8rem; font-weight: bold; text-transform: uppercase;">Estado</label>
                <div style="display: inline-block; padding: 4px 12px; border-radius: 6px; font-weight: bold; font-size: 0.85rem; background: {{ $proyecto->estado == 'Aprobado' ? '#dcfce7' : '#fef9c3' }}; color: {{ $proyecto->estado == 'Aprobado' ? '#166534' : '#854d0e' }};">
                    {{ $proyecto->estado }}
                </div>
            </div>

            <div style="margin-bottom: 0;">
                <label style="display: block; color: #64748b; font-size: 0.8rem; font-weight: bold; text-transform: uppercase;">Periodo de Ejecución</label>
                <div style="color: #1e293b; font-size: 0.9rem; margin-top: 5px;">
                    <strong>Inicio:</strong> {{ \Carbon\Carbon::parse($proyecto->fecha_inicio)->format('d/m/Y') }}<br>
                    <strong>Fin:</strong> {{ \Carbon\Carbon::parse($proyecto->fecha_final)->format('d/m/Y') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection