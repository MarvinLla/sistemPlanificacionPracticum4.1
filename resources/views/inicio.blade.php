@extends('layouts.app')

@section('content')
<style>
    /* Estilos originales preservados */
    .dashboard-grid {
        display: grid !important;
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 25px !important;
        padding: 20px !important;
        width: 100% !important;
    }

    .card-item {
        background: #ffffff !important;
        border-radius: 25px !important;
        border: 1px solid #dee2e6 !important;
        padding: 25px 20px !important; 
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        text-decoration: none !important;
        transition: all 0.3s ease !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06) !important;
        position: relative;
    }

    .card-item:hover {
        transform: translateY(-8px) !important;
        border-color: #2563eb !important;
        box-shadow: 0 15px 30px rgba(0,0,0,0.12) !important;
    }

    .card-content-visual {
        font-size: 4.5rem !important; 
        margin-bottom: 15px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    .card-icon-img {
        width: 90px !important;
        height: 90px !important;
        object-fit: contain !important;
    }

    .card-item h3 {
        color: #1e293b !important;
        font-size: 1.6rem !important;
        font-weight: bold !important;
        margin: 0 !important;
    }

    .badge-count {
        position: absolute;
        top: 15px;
        right: 20px;
        background: #2563eb;
        color: white;
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 0.9rem;
        font-weight: bold;
    }

    .badge-revision { background: #eab308 !important; }
    .badge-danger { background: #ef4444 !important; }

    @media (max-width: 768px) {
        .dashboard-grid {
            grid-template-columns: 1fr !important;
        }
    }
</style>

<div style="margin-bottom: 30px; padding-left: 20px;">
    <h2 style="font-size: 2.2rem; font-weight: 800; color: #1e293b; margin-bottom: 5px;">Panel de Gestión</h2>
    <p style="color: #64748b; font-size: 1.1rem;">Bienvenido. Seleccione un módulo para administrar la información.</p>
</div>

<div class="dashboard-grid">
    {{-- ENTIDADES --}}
    @can('ver entidades')
    <a href="{{ route('entidades.index') }}" class="card-item">
        <span class="badge-count">{{ $totalEntidades ?? 0 }}</span>
        <div class="card-content-visual">🏢</div>
        <h3>Entidades</h3>
    </a>
    @endcan

    {{-- PROYECTOS --}}
    @can('ver proyectos')
    <a href="{{ route('proyectos.index') }}" class="card-item">
        <span class="badge-count badge-revision">{{ $proyectosRevision ?? 0 }} Pendientes</span>
        <div class="card-content-visual">
            <img src="{{ asset('img/iconos/proyecto.png') }}" alt="proyecto" class="card-icon-img" onerror="this.parentElement.innerHTML='📁'">
        </div> 
        <h3>Proyectos</h3>
    </a>
    @endcan

    {{-- PROGRAMAS --}}
    @can('ver programas')
    <a href="{{ route('programas.index') }}" class="card-item">
        <div class="card-content-visual">
            <img src="{{ asset('img/iconos/programa.png') }}" alt="programa" class="card-icon-img" onerror="this.parentElement.innerHTML='⚙️'">
        </div>
        <h3>Programas</h3>
    </a>
    @endcan

    {{-- OBJETIVOS ODS--}}
    @can('ver ODS')
    <a href="{{ route('ODS.index') }}" class="card-item">
        <span class="badge-count">{{ $totalODS ?? 0 }}</span>
        <div class="card-content-visual">
            <img src="{{ asset('img/iconos/objetivo.png') }}" alt="objetivoDS" class="card-icon-img" onerror="this.parentElement.innerHTML='🎯'">
        </div> 
        <h3>Objetivos ODS</h3>
    </a>
    @endcan

    {{-- NUEVO: PLAN NACIONAL DE DESARROLLO (PND) --}}
    {{-- Asumo que el permiso es 'ver proyectos' o puedes crear uno llamado 'ver PND' --}}
    @can('ver proyectos') 
    <a href="{{ route('pnd.index') }}" class="card-item" style="border: 2px dashed #3b82f6;">
        <div class="card-content-visual">
            <img src="{{ asset('img/iconos/objetivo.png') }}" alt="PND" class="card-icon-img" style="filter: hue-rotate(180deg);" onerror="this.parentElement.innerHTML='🇪🇨'">
        </div> 
        <h3 style="color: #1e40af !important;">Plan Nacional (PND)</h3>
        <small style="color: #64748b;">Alineación Estratégica</small>
    </a>
    @endcan

    {{-- AVANCES / SEGUIMIENTO --}}
    @can('ver proyectos')
    <a href="{{ route('avances.index') }}" class="card-item">
        <div class="card-content-visual">
            <img src="{{ asset('img/iconos/avance.png') }}" alt="avance" class="card-icon-img" onerror="this.parentElement.innerHTML='📈'">
        </div>
        <h3>Avances</h3>
    </a>
    @endcan

    {{-- KARDEX --}}
    @can('ver proyectos')
    <a href="{{ route('kardex.index') }}" class="card-item">
        <div class="card-content-visual">
            <img src="{{ asset('img/iconos/avance.png') }}" alt="kardex" class="card-icon-img" onerror="this.parentElement.innerHTML='📇'">
        </div>
        <h3>Kardex</h3>
    </a>

    <a href="{{ route('alineacion.index') }}" class="card-item" style="border: 2px solid #10b981 !important; background: #f0fdf4 !important;">
        {{-- Podrías pasar un conteo de objetivos cumplidos aquí --}}
        <span class="badge-count" style="background: #10b981 !important;">Resumen</span>
        
        <div class="card-content-visual">
            {{-- Usamos un icono de red o cadena para representar la alineación --}}
            <img src="{{ asset('img/iconos/objetivo.png') }}" alt="Alineacion" class="card-icon-img" style="filter: hue-rotate(90deg);" onerror="this.parentElement.innerHTML='🔗'">
        </div> 
        <h3 style="color: #047857 !important;">Alineación Total</h3>
        <p style="color: #64748b; font-size: 0.9rem; margin-top: 5px; text-align: center;">Vínculo PND + Políticas + ODS</p>
    </a>
    @endcan

    @can('ver alertas')
    {{-- ALERTAS --}}
    <a href="{{ route('alertas.index') }}" class="card-item">
        @if(isset($totalAlertas) && $totalAlertas > 0)
            <span class="badge-count badge-danger">{{ $totalAlertas }}</span>
        @endif
        <div class="card-content-visual">
            <img src="{{ asset('img/iconos/avance.png') }}" alt="alertas" class="card-icon-img" onerror="this.parentElement.innerHTML='🔔'">
        </div>
        <h3>Alertas</h3>
    </a>
    @endcan
</div>
@endsection