@extends('layouts.app')

@section('content')
<div style="padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2 style="font-size: 1.8rem; font-weight: bold; color: #1e293b; margin: 0;">🚀 Seguimiento de Avances</h2>
        @can('crear proyectos')
            <a href="{{ route('avances.create') }}" style="background: #059669; color: white; padding: 12px 24px; border-radius: 10px; text-decoration: none; font-weight: bold; box-shadow: 0 4px 6px rgba(5, 150, 105, 0.2);">
                + Registrar Avance
            </a>
        @endcan
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 25px;">
        @foreach($proyectos as $proyecto)
            @php
                // Cálculo de días restantes
                $fechaFin = \Carbon\Carbon::parse($proyecto->fecha_final);
                $hoy = \Carbon\Carbon::now();
                $diasRestantes = (int) $hoy->diffInDays($fechaFin, false);
                
                // Cálculo de porcentaje de presupuesto usado
                $gastado = $proyecto->avances->sum('monto_gastado');
                $porcentajeUso = $proyecto->presupuesto_estimado > 0 
                    ? ($gastado / $proyecto->presupuesto_estimado) * 100 
                    : 0;
            @endphp

            <div style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); border-top: 5px solid {{ $diasRestantes < 0 ? '#ef4444' : '#3b82f6' }}; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                
                <h3 style="margin: 0 0 10px 0; color: #1e293b; font-size: 1.25rem;">{{ $proyecto->nombre }}</h3>
                <span style="font-size: 0.85rem; color: #64748b; font-weight: 500;">📍 {{ $proyecto->entidad->nombre ?? 'Sin Entidad' }}</span>

                <hr style="border: 0; border-top: 1px solid #f1f5f9; margin: 15px 0;">

                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                    <div style="background: {{ $diasRestantes < 0 ? '#fee2e2' : '#dbeafe' }}; padding: 10px; border-radius: 10px; text-align: center; min-width: 80px;">
                        <div style="font-size: 1.2rem; font-weight: bold; color: {{ $diasRestantes < 0 ? '#b91c1c' : '#1e40af' }};">
                            {{ $diasRestantes < 0 ? 'Expiró' : $diasRestantes }}
                        </div>
                        <div style="font-size: 0.7rem; color: {{ $diasRestantes < 0 ? '#b91c1c' : '#1e40af' }}; text-transform: uppercase;">
                            {{ $diasRestantes < 0 ? 'Hace días' : 'Días restantes' }}
                        </div>
                    </div>
                    <div style="font-size: 0.85rem; color: #475569;">
                        Fin: <strong>{{ \Carbon\Carbon::parse($proyecto->fecha_final)->format('d M, Y') }}</strong>
                    </div>
                </div>

                <div style="margin-bottom: 10px;">
                    <div style="display: flex; justify-content: space-between; font-size: 0.85rem; margin-bottom: 5px;">
                        <span style="color: #64748b;">Presupuesto Ejecutado</span>
                        <span style="font-weight: bold; color: #1e293b;">{{ number_format($porcentajeUso, 1) }}%</span>
                    </div>
                    <div style="width: 100%; background: #e2e8f0; height: 10px; border-radius: 5px; overflow: hidden;">
                        <div style="width: {{ min($porcentajeUso, 100) }}%; background: {{ $porcentajeUso > 90 ? '#ef4444' : '#10b981' }}; height: 100%; border-radius: 5px;"></div>
                    </div>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
                    <div>
                        <div style="font-size: 0.75rem; color: #94a3b8;">Saldo Disponible</div>
                        <div style="font-weight: bold; color: #059669;">${{ number_format($proyecto->presupuestoRestante(), 2) }}</div>
                    </div>  
                    <a href="{{ route('kardex.index', $proyecto->id) }}" style="text-decoration: none; color: #3b82f6; font-size: 0.9rem; font-weight: 600;">
                    Ver Kardex →
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection