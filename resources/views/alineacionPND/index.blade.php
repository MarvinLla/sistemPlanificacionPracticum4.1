@extends('layouts.app')

@section('content')
<div style="padding: 20px; background: #f1f5f9; min-height: 100vh; font-family: 'Inter', sans-serif;">
    
    {{-- Encabezado Principal --}}
    <div style="margin-bottom: 30px; background: white; padding: 25px; border-radius: 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border-left: 6px solid #1e40af;">
        <h2 style="font-size: 1.8rem; font-weight: 800; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-sitemap" style="color: #1e40af;"></i> Alineación Estratégica PND
        </h2>
        <p style="color: #64748b; margin: 5px 0 0 0; font-size: 1rem;">Seguimiento jerárquico de cumplimiento y metas ODS.</p>
    </div>

    @foreach($objetivos as $obj)
    <div style="background: white; border-radius: 20px; margin-bottom: 50px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); overflow: hidden; border: 1px solid #e2e8f0;">
        
        {{-- NIVEL 1: OBJETIVO NACIONAL --}}
        @php $cumplimientoObj = $obj->calcularCumplimiento(); @endphp
        <div style="background: #1e293b; padding: 25px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px;">
                <div style="max-width: 70%;">
                    <span style="background: #3b82f6; padding: 4px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase;">Nivel 1: Objetivo Nacional</span>
                    <h3 style="margin: 10px 0 0 0; font-size: 1.5rem; line-height: 1.2;">{{ $obj->nombre_objetivo }}</h3>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 2rem; font-weight: 900; color: #3b82f6;">{{ number_format($cumplimientoObj, 1) }}%</div>
                    <div style="font-size: 0.7rem; color: #94a3b8; text-transform: uppercase;">Cumplimiento</div>
                </div>
            </div>
            <div style="width: 100%; background: rgba(255,255,255,0.1); height: 10px; border-radius: 5px; overflow: hidden;">
                <div style="width: {{ min($cumplimientoObj, 100) }}%; background: #3b82f6; height: 100%; transition: width 1s;"></div>
            </div>
        </div>

        <div style="padding: 30px;">
            <div style="display: grid; grid-template-columns: 280px 1fr; gap: 30px;">
                
                {{-- NIVEL 2: POLÍTICAS --}}
                <div style="background: #f8fafc; padding: 20px; border-radius: 15px; border: 1px solid #e2e8f0; align-self: start;">
                    <h5 style="font-size: 0.8rem; font-weight: 800; color: #475569; text-transform: uppercase; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-shield-halved"></i> Políticas
                    </h5>
                    @foreach($obj->politicas as $pol)
                        <div style="padding: 10px; background: white; border-radius: 8px; margin-bottom: 10px; border-left: 3px solid #3b82f6; box-shadow: 0 1px 2px rgba(0,0,0,0.05); font-size: 0.8rem; font-weight: 600;">
                            {{ $pol->nombre }}
                        </div>
                    @endforeach
                </div>

                <div style="display: flex; flex-direction: column; gap: 30px;">
                    @php $proyectosPorPrograma = $obj->proyectos->groupBy('programa_id'); @endphp
                    
                    @foreach($proyectosPorPrograma as $progId => $proyectos)
                        @php $programa = $proyectos->first()->programa; @endphp
                        
                        {{-- NIVEL 3: PROGRAMAS --}}
                        <div style="border: 1px solid #e2e8f0; border-radius: 15px; padding: 0; overflow: hidden;">
                            <div style="background: #f1f5f9; padding: 12px 20px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                                <h4 style="margin: 0; color: #1e293b; font-size: 0.95rem; font-weight: 700;">
                                    <i class="fas fa-layer-group" style="color: #6366f1; margin-right: 8px;"></i> 
                                    Programa: {{ $programa->nombrePrograma ?? 'Sin nombre' }}
                                </h4>
                                <span style="background: #6366f1; color: white; padding: 2px 10px; border-radius: 12px; font-size: 0.75rem; font-weight: bold;">
                                    Promedio: {{ number_format($proyectos->avg(fn($p) => $p->porcentajePresupuesto()), 1) }}%
                                </span>
                            </div>

                            {{-- NIVEL 4: PROYECTOS --}}
                            <div style="padding: 20px; display: flex; flex-direction: column; gap: 20px;">
                                @foreach($proyectos as $proy)
                                    <div style="background: white; border: 1px solid #cbd5e1; border-radius: 12px; padding: 15px;">
                                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                                            <div style="display: flex; flex-direction: column; gap: 4px;">
                                                <span style="font-weight: 700; color: #1e293b; font-size: 0.9rem;">🚀 Proyecto: {{ $proy->nombre }}</span>
                                                <span style="font-size: 0.8rem; color: #64748b; font-weight: 600;">
                                                    <i class="fas fa-hand-holding-dollar" style="color: #10b981;"></i> 
                                                    Inversión Ejecutada: ${{ number_format($proy->avances->sum('monto_gastado'), 2) }}
                                                </span>
                                            </div>
                                            <span style="font-size: 0.8rem; font-weight: 800; color: #059669;">Eje. {{ number_format($proy->porcentajePresupuesto(), 1) }}%</span>
                                        </div>

                                        {{-- NIVEL 5: ODS --}}
                                        <div style="margin-left: 15px; padding-left: 15px; border-left: 2px dashed #cbd5e1;">
                                            @foreach($proy->ods as $ods)
                                                <div style="margin-bottom: 20px;">
                                                    <div style="font-size: 0.85rem; font-weight: 800; color: #1e293b; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                                                        <span style="background: #e11d48; color: white; padding: 2px 6px; border-radius: 4px; font-size: 0.7rem;">ODS {{ $ods->numero }}</span>
                                                        {{ $ods->nombreObjetivo }}
                                                    </div>

                                                    {{-- NIVEL 6: METAS --}}
                                                    @foreach($ods->metas as $meta)
                                                        @php 
                                                            $cumplimientoMeta = $ods->calcularAvanceMetaPorProyecto($proy->id); 
                                                            $colorMeta = $ods->getColorMeta($cumplimientoMeta);
                                                        @endphp
                                                        <div style="margin-left: 25px; margin-bottom: 15px; background: #fafafa; padding: 10px; border-radius: 10px; border: 1px solid #f1f5f9;">
                                                            <div style="display: flex; justify-content: space-between; font-size: 0.75rem; color: #475569; margin-bottom: 5px;">
                                                                <span style="font-weight: 700;">📌 Meta: {{ $meta->nombreObjetivo }}</span>
                                                                <span style="font-weight: 800; color: {{ $colorMeta }};">{{ number_format($cumplimientoMeta, 1) }}%</span>
                                                            </div>
                                                            <div style="width: 100%; background: #e2e8f0; height: 5px; border-radius: 3px; overflow: hidden; margin-bottom: 10px;">
                                                                <div style="width: {{ min($cumplimientoMeta, 100) }}%; background: {{ $colorMeta }}; height: 100%;"></div>
                                                            </div>

                                                            {{-- NIVEL 7: INDICADORES --}}
                                                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px;">
                                                                @forelse($ods->indicadoresPorProyecto($proy->id) as $ind)
                                                                    @php 
                                                                        $yaGastado = $proy->avances->where('indicador_proyecto_id', $ind->id)->sum('monto_gastado');
                                                                        $metaFija = $ind->valor_meta_fijo;
                                                                        $porcentajeInd = $metaFija > 0 ? ($yaGastado / $metaFija) * 100 : 0;
                                                                        $esCompleto = $porcentajeInd >= 100;
                                                                    @endphp
                                                                    <div style="background: white; border: 1px solid {{ $esCompleto ? '#10b981' : '#e2e8f0' }}; padding: 8px; border-radius: 8px; display: flex; flex-direction: column; gap: 4px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                                                                        <div style="font-size: 0.65rem; font-weight: 700; color: #1e293b; line-height: 1.2;">
                                                                            {{ Str::limit($ind->nombre_indicador, 40) }}
                                                                        </div>
                                                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                                                            <div style="flex-grow: 1; background: #f1f5f9; height: 4px; border-radius: 2px; margin-right: 8px; overflow: hidden;">
                                                                                <div style="width: {{ min($porcentajeInd, 100) }}%; background: {{ $esCompleto ? '#10b981' : '#3b82f6' }}; height: 100%;"></div>
                                                                            </div>
                                                                            <span style="font-size: 0.65rem; font-weight: 800; color: {{ $esCompleto ? '#059669' : '#3b82f6' }};">
                                                                                {{ number_format($porcentajeInd, 0) }}%
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                @empty
                                                                    <div style="font-size: 0.65rem; color: #94a3b8; font-style: italic;">
                                                                        <i class="fas fa-info-circle"></i> Sin indicadores registrados
                                                                    </div>
                                                                @endforelse
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection