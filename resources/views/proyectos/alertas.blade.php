@extends('layouts.app')

@section('content')
<div style="padding: 20px;">
    <div style="margin-bottom: 25px;">
        <h2 style="color: #1e293b; font-size: 2rem; font-weight: bold;">🔔 Centro de Alertas Críticas</h2>
        <p style="color: #64748b;">Proyectos que requieren atención inmediata por presupuesto o tiempo.</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 20px;">
        @forelse($proyectos as $proy)
            @php
                // Usamos los métodos del modelo que creamos antes
                $porcentaje = $proy->porcentajePresupuesto(); 
                $fechaFinal = \Carbon\Carbon::parse($proy->fecha_final);
                $diasRestantes = (int) \Carbon\Carbon::now()->diffInDays($fechaFinal, false);
                
                // Definir severidad: Crítico si supera 95% de gasto o quedan menos de 5 días
                $esCritico = $porcentaje >= 95 || $diasRestantes <= 5;
            @endphp

            <div style="background: white; border-left: 8px solid {{ $esCritico ? '#ef4444' : '#f59e0b' }}; border-radius: 12px; padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px;">
                    <div>
                        <h3 style="margin: 0; color: #1e293b; font-size: 1.2rem;">{{ $proy->nombre }}</h3>
                        <small style="color: #64748b;">{{ $proy->entidad->nombre ?? 'Sin entidad' }}</small>
                    </div>
                    <span style="background: {{ $esCritico ? '#fee2e2' : '#fef3c7' }}; color: {{ $esCritico ? '#b91c1c' : '#92400e' }}; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: bold; text-transform: uppercase;">
                        {{ $esCritico ? 'Crítico' : 'Advertencia' }}
                    </span>
                </div>

                <div style="margin-bottom: 15px;">
                    <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 5px;">
                        <span>Presupuesto Utilizado:</span>
                        <span style="font-weight: bold; color: {{ $porcentaje >= 90 ? '#ef4444' : '#1e293b' }}">
                            {{ $porcentaje }}%
                        </span>
                    </div>
                    <div style="width: 100%; background: #e2e8f0; height: 8px; border-radius: 4px; overflow: hidden;">
                        <div style="width: {{ min($porcentaje, 100) }}%; background: {{ $porcentaje >= 90 ? '#ef4444' : '#f59e0b' }}; height: 100%; transition: width 0.5s ease-in-out;"></div>
                    </div>
                    <small style="color: #94a3b8; font-size: 0.75rem;">
                        Gastado: ${{ number_format($proy->presupuestoConsumido(), 2) }} de ${{ number_format($proy->presupuesto_estimado, 2) }}
                    </small>
                </div>

                <div style="display: flex; align-items: center; gap: 10px; color: {{ $diasRestantes <= 7 ? '#ef4444' : '#475569' }}; font-size: 0.9rem;">
                    <span>📅</span>
                    <span>
                        @if($diasRestantes < 0)
                            <strong style="color: #ef4444;">Vencido hace {{ abs($diasRestantes) }} días</strong>
                        @elseif($diasRestantes == 0)
                            <strong style="color: #ef4444;">Vence HOY</strong>
                        @else
                            Vence en {{ $diasRestantes }} días ({{ $fechaFinal->format('d/m/Y') }})
                        @endif
                    </span>
                </div>

                <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                    <a href="{{ route('proyectos.show', $proy->id) }}" style="color: #64748b; text-decoration: none; font-size: 0.85rem;">Ver detalles</a>
                    <a href="{{ route('avances.create', ['proyecto_id' => $proy->id]) }}" style="color: #2563eb; text-decoration: none; font-size: 0.9rem; font-weight: bold;">Registrar Avance →</a>
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 50px; background: #f8fafc; border-radius: 15px; border: 2px dashed #cbd5e1;">
                <p style="color: #64748b; font-size: 1.1rem;">✅ No hay alertas pendientes. Todos los proyectos están bajo control.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection