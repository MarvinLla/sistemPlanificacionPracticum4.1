@extends('layouts.app')

@section('content')
<div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <div>
            <h2 style="color: #1e293b; margin: 0;">📇 Kardex de Movimientos Financieros</h2>
            <p style="color: #64748b; margin: 5px 0 0 0;">Historial detallado de ejecución presupuestaria</p>
        </div>
        <a href="{{ route('avances.create') }}" style="background: #2563eb; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: bold;">
            + Nuevo Gasto
        </a>
    </div>

    <table style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                <th style="padding: 12px; color: #475569;">Fecha</th>
                <th style="padding: 12px; color: #475569;">Proyecto</th>
                <th style="padding: 12px; color: #475569;">Concepto / Título</th>
                <th style="padding: 12px; color: #475569; text-align: right;">Monto Ejecutado</th>
                <th style="padding: 12px; color: #475569; text-align: center;">Evidencia</th>
            </tr>
        </thead>
        <tbody>
            @forelse($movimientos as $mov)
            <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#fcfcfc'" onmouseout="this.style.background='transparent'">
                <td style="padding: 12px; color: #1e293b; font-weight: 500;">
                    {{ \Carbon\Carbon::parse($mov->fecha_avance)->format('d/m/Y') }}
                </td>
                <td style="padding: 12px; color: #64748b; font-size: 0.9rem;">
                    {{ $mov->proyecto->nombre }}
                </td>
                <td style="padding: 12px;">
                    <div style="font-weight: bold; color: #1e293b;">{{ $mov->titulo }}</div>
                    <div style="font-size: 0.8rem; color: #94a3b8;">{{ Str::limit($mov->descripcion, 50) }}</div>
                </td>
                <td style="padding: 12px; text-align: right; color: #dc2626; font-weight: bold;">
                    - ${{ number_format($mov->monto_gastado, 2) }}
                </td>
                <td style="padding: 12px; text-align: center;">
                    @if($mov->foto)
                        <a href="{{ asset('storage/'.$mov->foto) }}" target="_blank" title="Ver Foto">🖼️</a>
                    @endif
                    @if($mov->archivo)
                        <a href="{{ asset('storage/'.$mov->archivo) }}" target="_blank" title="Ver Documento">📄</a>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding: 40px; text-align: center; color: #94a3b8;">
                    No hay movimientos registrados en el sistema.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection