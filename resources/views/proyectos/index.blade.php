@extends('layouts.app')

@section('content')
<div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <h2 style="font-size: 1.5rem; font-weight: bold; color: #1e293b;">Gestión de Proyectos y Planes</h2>
        @can('crear proyectos')
            <a href="{{ route('proyectos.create') }}" style="background: #2563eb; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 0.9rem;">
                + Nuevo Proyecto
            </a>
        @endcan
    </div>

    @if(session('success'))
        <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #86efac;">
            {{ session('success') }}
        </div>
    @endif

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <th style="padding: 12px; color: #64748b;">Proyecto</th>
                    <th style="padding: 12px; color: #64748b;">Entidad</th>
                    <th style="padding: 12px; color: #64748b;">Presupuesto Inicial</th>
                    <th style="padding: 12px; color: #64748b;">Saldo Disponible</th> <th style="padding: 12px; color: #64748b; text-align: center; min-width: 150px;">Estado</th>
                    <th style="padding: 12px; color: #64748b; text-align: center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($proyectos as $proyecto)
                <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 15px;">
                        <div style="font-weight: bold; color: #1e293b;">{{ $proyecto->nombre }}</div>
                        <div style="font-size: 0.8rem; color: #94a3b8;">{{ $proyecto->fecha_inicio }} al {{ $proyecto->fecha_final }}</div>
                    </td>
                    <td style="padding: 15px; color: #475569;">{{ $proyecto->entidad->nombre ?? 'N/A' }}</td>
                    <td style="padding: 15px; color: #475569;">${{ number_format($proyecto->presupuesto_estimado, 2) }}</td>
                    
                    <td style="padding: 15px;">
                        <div style="font-weight: bold; color: {{ $proyecto->presupuestoRestante() <= 0 ? '#dc2626' : '#1e293b' }}">
                            ${{ number_format($proyecto->presupuestoRestante(), 2) }}
                        </div>
                        
                        @if($proyecto->presupuestoRestante() <= 0)
                            <span style="font-size: 0.7rem; color: white; background: #dc2626; padding: 2px 6px; border-radius: 4px; font-weight: bold; text-transform: uppercase;">Agotado</span>
                        @elseif($proyecto->presupuestoRestante() < ($proyecto->presupuesto_estimado * 0.15))
                            <span style="font-size: 0.7rem; color: #9a3412; background: #ffedd5; padding: 2px 6px; border-radius: 4px; font-weight: bold;">⚠️ Crítico</span>
                        @endif
                    </td>

                    <td style="padding: 15px; text-align: center; white-space: nowrap;">
                        @if($proyecto->estado == 'Pendiente' || $proyecto->estado == 'En Revisión')
                            <span style="background: #fef9c3; color: #854d0e; padding: 6px 14px; border-radius: 20px; font-weight: bold; font-size: 0.75rem; border: 1px solid #fde047; text-transform: uppercase;">
                                🟡 {{ $proyecto->estado }}
                            </span>
                        @elseif($proyecto->estado == 'Aprobado' || $proyecto->estado == 'aprobado')
                            <span style="background: #dcfce7; color: #166534; padding: 6px 14px; border-radius: 20px; font-weight: bold; font-size: 0.75rem; border: 1px solid #86efac; text-transform: uppercase;">
                                🟢 Aprobado
                            </span>
                        @else
                            <span style="background: #f1f5f9; color: #475569; padding: 6px 14px; border-radius: 20px; font-weight: bold; font-size: 0.75rem; border: 1px solid #e2e8f0;">
                                {{ $proyecto->estado }}
                            </span>
                        @endif
                    </td>

                    <td style="padding: 15px; text-align: center;">
                        <div style="display: flex; gap: 12px; justify-content: center; align-items: center;">
                            @can('cambiar estados')
                                @if(strtolower($proyecto->estado) != 'aprobado')
                                    <form action="{{ route('proyectos.aprobar', $proyecto->id) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" style="background: #10b981; color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 0.75rem; font-weight: bold; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                                            Aprobar
                                        </button>
                                    </form>
                                @endif
                            @endcan

                            <a href="{{ route('proyectos.show', $proyecto->id) }}" title="Ver Detalles" style="text-decoration: none; font-size: 1.1rem; filter: grayscale(100%); transition: filter 0.2s;" onmouseover="this.style.filter='none'" onmouseout="this.style.filter='grayscale(100%)'">👁️</a>
                            
                            @can('editar proyectos')
                                <a href="{{ route('proyectos.edit', $proyecto->id) }}" style="text-decoration: none; font-size: 1.1rem;" title="Editar">📝</a>
                            @endcan
                            <a href="{{ route('proyectos.pdf', $proyecto->id) }}" class="btn btn-sm btn-danger">
                             📥 Exportar PDF
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection