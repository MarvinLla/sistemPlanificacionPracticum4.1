@extends('layouts.app')

@section('content')
<div style="padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2 style="font-size: 1.8rem; font-weight: bold; color: #1e293b; margin: 0;">🚀 Seguimiento de Avances</h2>
        @can('crear proyectos')
            <a href="{{ route('avances.create') }}" style="background: #0f172a; color: white; padding: 12px 24px; border-radius: 10px; text-decoration: none; font-weight: bold; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                + Registro General
            </a>
        @endcan 
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 25px;">
        @foreach($proyectos as $proyecto)
            @php
                // CALCULOS GLOBALES
                $gastadoGlobal = $proyecto->avances->sum('monto_gastado'); 
                $presupuestoTotal = $proyecto->presupuesto_estimado;
                $saldoDisponible = $presupuestoTotal - $gastadoGlobal;
                $porcentajeUsoGlobal = $presupuestoTotal > 0 ? ($gastadoGlobal / $presupuestoTotal) * 100 : 0;

                $fechaFin = \Carbon\Carbon::parse($proyecto->fecha_final);
                $diasRestantes = (int) \Carbon\Carbon::now()->diffInDays($fechaFin, false);
            @endphp

            <div style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); border-top: 5px solid {{ $diasRestantes < 0 ? '#ef4444' : '#3b82f6' }}; min-height: 480px; display: flex; flex-direction: column;">
                
                <div style="flex-grow: 1;">
                    <h3 style="margin: 0; color: #1e293b; font-size: 1.25rem;">{{ $proyecto->nombre }}</h3>
                    <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 15px;">📍 {{ $proyecto->entidad->nombre ?? 'Sin Entidad' }}</p>

                    {{-- Tiempo --}}
                    <div style="background: {{ $diasRestantes < 0 ? '#fee2e2' : '#f1f5f9' }}; padding: 10px; border-radius: 10px; margin-bottom: 20px; display: flex; justify-content: space-between;">
                        <span style="font-size: 0.75rem; font-weight: 800; color: {{ $diasRestantes < 0 ? '#b91c1c' : '#1e40af' }};">
                            {{ $diasRestantes < 0 ? 'EXPIRADO' : $diasRestantes . ' DÍAS RESTANTES' }}
                        </span>
                        <span style="font-size: 0.75rem; color: #475569;">{{ $fechaFin->format('d/m/Y') }}</span>
                    </div>

                    {{-- METAS INDIVIDUALES --}}
                    <div style="background: #f8fafc; padding: 15px; border-radius: 10px; border: 1px solid #e2e8f0; margin-bottom: 20px;">
                        <p style="font-size: 0.7rem; font-weight: bold; color: #6366f1; text-transform: uppercase; margin-bottom: 10px;">🎯 Cumplimiento de Metas</p>
                        
                        @forelse($proyecto->indicadores as $indicador)
                            @php
                                $montoInd = $indicador->avances->sum('monto_gastado');
                                $metaFija = $indicador->valor_meta_fijo;
                                $porcentajeInd = $metaFija > 0 ? ($montoInd / $metaFija) * 100 : 0;
                            @endphp
                            
                            <div style="margin-bottom: 12px;">
                                <div style="display: flex; justify-content: space-between; font-size: 0.75rem; margin-bottom: 4px;">
                                    <span style="color: #475569;">• {{ Str::limit($indicador->nombre_indicador, 30) }}</span>
                                    <span style="font-weight: bold; color: {{ $porcentajeInd >= 100 ? '#059669' : '#1e293b' }};">
                                        {{ $porcentajeInd >= 100 ? '100%' : number_format($porcentajeInd, 0) . '%' }}
                                    </span>
                                </div>
                                <div style="width: 100%; background: #e2e8f0; height: 6px; border-radius: 3px; overflow: hidden;">
                                    <div style="width: {{ min($porcentajeInd, 100) }}%; background: {{ $porcentajeInd >= 100 ? '#059669' : '#6366f1' }}; height: 100%;"></div>
                                </div>
                                <div style="font-size: 0.65rem; color: #94a3b8; margin-top: 2px;">
                                    Meta: ${{ number_format($metaFija, 2) }}
                                </div>
                            </div>
                        @empty
                            <p style="font-size: 0.75rem; color: #94a3b8; text-align: center;">Sin indicadores</p>
                        @endforelse
                    </div>
                </div>

                {{-- FOOTER CON ACCIONES --}}
                <div style="margin-top: auto;">
                    <div style="display: flex; justify-content: space-between; font-size: 0.85rem; margin-bottom: 8px;">
                        <span style="color: #475569; font-weight: bold;">Uso del Presupuesto</span>
                        <span style="font-weight: 900; color: #1e293b;">{{ number_format($porcentajeUsoGlobal, 2) }}%</span>
                    </div>
                    
                    <div style="width: 100%; background: #f1f5f9; height: 12px; border-radius: 6px; overflow: hidden; border: 1px solid #e2e8f0;">
                        <div style="width: {{ min($porcentajeUsoGlobal, 100) }}%; background: #10b981; height: 100%;"></div>
                    </div>

                    <div style="display: flex; justify-content: space-between; font-size: 0.7rem; margin-top: 6px; color: #64748b; font-weight: bold;">
                        <span>GASTADO: ${{ number_format($gastadoGlobal, 2) }}</span>
                        <span>TOTAL: ${{ number_format($presupuestoTotal, 2) }}</span>
                    </div>

                    {{-- SECCIÓN DE BOTONES --}}
                    <div style="margin-top: 15px; padding-top: 15px; border-top: 1px dashed #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <span style="font-size: 0.65rem; color: #94a3b8; display: block;">Saldo Disponible</span>
                            <span style="font-size: 1.1rem; font-weight: 800; color: #059669;">${{ number_format($saldoDisponible, 2) }}</span>
                        </div>
                        
                        <div style="display: flex; gap: 8px;">
                            {{-- Botón para ir directo a crear avance de ESTE proyecto --}}
                            @can('crear proyectos')
                                <a href="{{ route('avances.create', ['proyecto_id' => $proyecto->id]) }}" 
                                   style="background: #059669; color: white; padding: 8px 12px; border-radius: 8px; text-decoration: none; font-size: 0.75rem; font-weight: bold; display: flex; align-items: center; gap: 4px;"
                                   title="Registrar nuevo avance">
                                    <span>+</span> Avance
                                </a>
                            @endcan

                            <a href="{{ route('kardex.index', $proyecto->id) }}" 
                               style="background: #3b82f6; color: white; padding: 8px 12px; border-radius: 8px; text-decoration: none; font-size: 0.75rem; font-weight: bold;"
                               title="Ver historial detallado">
                                Kardex →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection