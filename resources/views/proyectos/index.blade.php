@extends('layouts.app')

@section('content')
<div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
    {{-- ENCABEZADO --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: bold; color: #1e293b; margin: 0;">Gestión de Proyectos Nacionales</h2>
            <p style="color: #64748b; font-size: 0.85rem; margin-top: 5px;">Seguimiento de planes estratégicos y alineación gubernamental.</p>
        </div>
        @can('crear proyectos')
            <a href="{{ route('proyectos.create') }}" style="background: #2563eb; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 0.9rem;">
                + Nuevo Proyecto
            </a>
        @endcan
    </div>

    @if(session('success'))
        <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #86efac;">
            ✅ {{ session('success') }}
        </div>
    @endif

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; min-width: 1200px;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <th style="padding: 12px; color: #64748b; font-size: 0.85rem;">Proyecto e Impacto</th>
                    <th style="padding: 12px; color: #64748b; font-size: 0.85rem;">Indicadores y Metas</th>
                    <th style="padding: 12px; color: #64748b; font-size: 0.85rem;">Ubicación</th>
                    <th style="padding: 12px; color: #64748b; font-size: 0.85rem;">Alineación PND</th> 
                    <th style="padding: 12px; color: #64748b; font-size: 0.85rem;">Alineación ODS</th>
                    <th style="padding: 12px; color: #64748b; font-size: 0.85rem;">Presupuesto</th>
                    <th style="padding: 12px; color: #64748b; font-size: 0.85rem; text-align: center;">Estado</th>
                    <th style="padding: 12px; color: #64748b; text-align: center; font-size: 0.85rem;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($proyectos as $proyecto)
                <tr style="border-bottom: 1px solid #f1f5f9;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                    
                    {{-- Proyecto e Impacto --}}
                    <td style="padding: 15px;">
                        <div style="font-weight: bold; color: #1e293b; font-size: 0.95rem;">{{ $proyecto->nombre }}</div>
                        <div style="display: flex; gap: 8px; margin-top: 4px;">
                            <span style="font-size: 0.7rem; color: #6366f1; background: #e0e7ff; padding: 1px 6px; border-radius: 4px; font-weight: 600;">
                                👥 {{ number_format($proyecto->beneficiarios_directos ?? 0) }} beneficiarios
                            </span>
                        </div>
                    </td>

                    {{-- Indicadores y Metas (ACTUALIZADO) --}}
                    <td style="padding: 15px; width: 220px;">
                        @php 
                            $totalInd = $proyecto->indicadores->count();
                            $porcentaje = method_exists($proyecto, 'porcentajePresupuesto') ? $proyecto->porcentajePresupuesto() : 0; 
                        @endphp
                        
                        <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                            <span style="font-size: 0.75rem; font-weight: bold; color: #1e293b;">{{ $porcentaje }}%</span>
                            <span style="font-size: 0.65rem; color: #6366f1; font-weight: bold;">
                                📊 {{ $totalInd }} {{ Str::plural('Meta', $totalInd) }}
                            </span>
                        </div>
                        
                        <div style="width: 100%; background: #e2e8f0; height: 6px; border-radius: 10px; margin-bottom: 8px;">
                            <div style="width: {{ $porcentaje }}%; background: #3b82f6; height: 100%; border-radius: 10px;"></div>
                        </div>

                        {{-- Lista resumida de indicadores creados --}}
                        <div style="display: flex; flex-direction: column; gap: 4px;">
                            @forelse($proyecto->indicadores->take(2) as $ind)
                                <div style="font-size: 0.68rem; color: #475569; line-height: 1.2;">
                                    <span style="color: #10b981; font-weight: bold;">•</span> 
                                    {{ Str::limit($ind->nombre_indicador, 35) }}
                                    <span style="display: block; color: #94a3b8; padding-left: 8px;">Meta: ${{ number_format($ind->valor_meta_fijo, 2) }}</span>
                                </div>
                            @empty
                                <div style="font-size: 0.65rem; color: #94a3b8; font-style: italic;">Sin metas configuradas</div>
                            @endforelse

                            @if($totalInd > 2)
                                <div style="font-size: 0.6rem; color: #3b82f6; font-weight: bold; margin-top: 2px; cursor: help;" title="Ver todos los indicadores en el detalle">
                                    + {{ $totalInd - 2 }} indicadores adicionales...
                                </div>
                            @endif
                        </div>
                    </td>

                    {{-- Ubicación --}}
                    <td style="padding: 15px;">
                        <div style="font-size: 0.85rem; color: #475569;">📍 {{ $proyecto->ubicacion_provincia ?? 'No definida' }}</div>
                        <div style="font-size: 0.75rem; color: #94a3b8;">{{ $proyecto->ubicacion_canton }}</div>
                    </td>

                    {{-- Alineación PND --}}
                    <td style="padding: 15px; max-width: 180px;">
                        @if($proyecto->pndObjetivo)
                            <span style="background: #eff6ff; color: #1e40af; font-size: 0.65rem; font-weight: bold; padding: 2px 6px; border-radius: 4px; border: 1px solid #bfdbfe; text-transform: uppercase;">
                                {{ $proyecto->pndObjetivo->eje }}
                            </span>
                            <div style="font-size: 0.75rem; color: #1e293b; margin-top: 4px; line-height: 1.2;">{{ Str::limit($proyecto->pndObjetivo->nombre_objetivo, 40) }}</div>
                        @else
                            <span style="color: #cbd5e1; font-size: 0.75rem;">No vinculado</span>
                        @endif
                    </td>

                    {{-- Alineación ODS --}}
                    <td style="padding: 15px; max-width: 180px;">
                        @if($proyecto->ods && $proyecto->ods->isNotEmpty())
                            <div style="display: flex; flex-direction: column; gap: 6px;">
                                <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                                    @foreach($proyecto->ods as $unOds)
                                        <span style="background: #f0fdf4; color: #166534; font-size: 0.65rem; font-weight: bold; padding: 2px 6px; border-radius: 4px; border: 1px solid #bbf7d0; white-space: nowrap;">
                                            🌍 ODS {{ $unOds->id }}
                                        </span>
                                    @endforeach
                                </div>
                                <div style="font-size: 0.7rem; color: #1e293b; line-height: 1.1;">
                                    {{ Str::limit($proyecto->metas_finales, 45) }}
                                </div>
                            </div>
                        @else
                            <span style="color: #cbd5e1; font-size: 0.75rem;">Sin ODS vinculados</span>
                        @endif
                    </td>

                    {{-- Presupuesto --}}
                    <td style="padding: 15px;">
                        <div style="font-size: 0.9rem; font-weight: bold; color: #1e293b;">${{ number_format($proyecto->presupuesto_estimado, 2) }}</div>
                        <div style="font-size: 0.7rem; color: #64748b;">Saldo: ${{ number_format($proyecto->presupuestoRestante(), 2) }}</div>
                    </td>

                    {{-- Estado --}}
                    <td style="padding: 15px; text-align: center;">
                        @php
                            $estadoNormalizado = strtolower($proyecto->estado);
                            $color = match($estadoNormalizado) {
                                'aprobado' => ['bg' => '#dcfce7', 'text' => '#166534'],
                                'pendiente' => ['bg' => '#fef9c3', 'text' => '#854d0e'],
                                default => ['bg' => '#f1f5f9', 'text' => '#475569']
                            };
                        @endphp
                        <span style="background: {{ $color['bg'] }}; color: {{ $color['text'] }}; padding: 4px 12px; border-radius: 20px; font-weight: bold; font-size: 0.7rem; text-transform: uppercase; display: inline-flex; align-items: center; gap: 4px;">
                            <span style="width: 8px; height: 8px; background: {{ $color['text'] }}; border-radius: 50%;"></span>
                            {{ $proyecto->estado }}
                        </span>
                    </td>

                    {{-- Acciones --}}
                    <td style="padding: 15px; text-align: center;">
                        <div style="display: flex; gap: 10px; justify-content: center; align-items: center;">
                            
                            @if($estadoNormalizado === 'pendiente')
                                @can('aprobar proyectos')
                                    <form action="{{ route('proyectos.aprobar', $proyecto->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('¿Está seguro de aprobar este proyecto?')">
                                        @csrf
                                        <button type="submit" title="Aprobar Proyecto" style="background: #10b981; color: white; border: none; padding: 0 12px; height: 32px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px; box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2); font-size: 0.8rem; font-weight: bold;">
                                            <span>✅</span>
                                            <span>Aprobar</span>
                                        </button>
                                    </form>
                                @endcan
                            @endif

                            <a href="{{ route('proyectos.show', $proyecto->id) }}" title="Ver Detalle" style="text-decoration: none; font-size: 1.2rem; filter: grayscale(0.5);">
                                👁️
                            </a>

                            @can('editar proyectos')
                                <a href="{{ route('proyectos.edit', $proyecto->id) }}" title="Editar Proyecto" style="text-decoration: none; font-size: 1.2rem;">
                                    📝
                                </a>
                            @endcan

                            <a href="{{ route('proyectos.pdf', $proyecto->id) }}" title="Descargar PDF" style="text-decoration: none; display: flex; align-items: center; justify-content: center; background: #fee2e2; padding: 6px; border-radius: 6px; color: #b91c1c; border: 1px solid #fecaca;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                    <polyline points="10 9 9 9 8 9"></polyline>
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($proyectos->isEmpty())
        <div style="text-align: center; padding: 40px; color: #94a3b8;">
            <p>No se encontraron proyectos registrados.</p>
        </div>
    @endif
</div>
@endsection