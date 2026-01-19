<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ficha Técnica - {{ $proyecto->nombre }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.5; font-size: 12px; }
        .header { text-align: center; border-bottom: 2px solid #2563eb; padding-bottom: 10px; margin-bottom: 20px; }
        .section-title { background: #f1f5f9; padding: 5px 10px; font-weight: bold; color: #1e40af; text-transform: uppercase; margin-top: 15px; border-left: 4px solid #2563eb; }
        .row { margin-bottom: 8px; }
        .label { font-weight: bold; color: #64748b; width: 150px; display: inline-block; }
        .highlight-box { border: 1px solid #cbd5e1; padding: 10px; border-radius: 5px; background: #fafafa; margin-top: 5px; }
        .footer-sign { margin-top: 50px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h2>FICHA TÉCNICA DE PROYECTO DE INVERSIÓN</h2>
        <p>Sistema de Gestión Estratégica - Ecuador</p>
    </div>

    <div class="section-title">1. Información General</div>
    <div class="row"><span class="label">Nombre:</span> {{ $proyecto->nombre }}</div>
    <div class="row"><span class="label">Entidad:</span> {{ $proyecto->entidad->nombre ?? 'No asignada' }}</div>
    <div class="row"><span class="label">Responsable:</span> {{ $proyecto->responsable }}</div>
    <div class="row"><span class="label">Presupuesto:</span> ${{ number_format($proyecto->presupuesto_estimado, 2) }}</div>
    <div class="row"><span class="label">Estado:</span> {{ $proyecto->estado }}</div>

    <div class="section-title">2. Descripción y Objetivos</div>
    <div class="highlight-box"><strong>Descripción:</strong><br>{{ $proyecto->descripcion }}</div>
    <div class="highlight-box"><strong>Objetivos:</strong><br>{{ $proyecto->objetivos }}</div>

    <div class="section-title">3. Alineación Estratégica Nacional (PND)</div>
    <div class="row"><span class="label">Eje PND:</span> {{ $proyecto->pndObjetivo->eje ?? 'No asignado' }}</div>
    <div class="highlight-box">
        <strong>Objetivo Nacional:</strong><br>
        {{ $proyecto->pndObjetivo->nombre_objetivo ?? 'Sin vinculación específica' }}
    </div>
    <div class="highlight-box">
        <strong>Justificación de Alineación:</strong><br>
        {{ $proyecto->justificacion_pnd ?? 'No proporcionada' }}
    </div>

    <div class="section-title">4. Alineación Internacional (ODS)</div>
    <div class="row">
        <span class="label">ODS Vinculados:</span> 
        {{-- Corregido: Pluck para obtener nombres de la colección --}}
        {{ $proyecto->ods->pluck('nombreObjetivo')->implode(', ') ?: 'No asignados' }}
    </div>

    @if($proyecto->metas_finales)
    <div class="highlight-box">
        <strong>Metas Específicas ODS:</strong><br>
        {{-- Limpiamos el separador || por saltos de línea --}}
        {!! nl2br(e(str_replace(' || ', "\n", $proyecto->metas_finales))) !!}
    </div>
    @endif

    <div class="footer-sign">
        <p style="margin-bottom: 60px;">Documento generado automáticamente el {{ date('d/m/Y') }}</p>
        <p>__________________________</p>
        <p><strong>{{ $proyecto->responsable }}</strong></p>
        <p>Firma del Responsable</p>
    </div>
</body>
</html>