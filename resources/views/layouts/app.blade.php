<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"> <title>{{ config('app.name', 'Planificación') }}</title>

    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    
    @include('layouts.navigation')

    <div style="display: flex; min-height: 100vh;">
        <aside style="width: 250px; background: #fff; border-right: 1px solid #ddd; display: flex; flex-direction: column;">
            
            <div style="padding: 20px; font-weight: bold; border-bottom: 1px solid #f1f5f9; color: #1e293b;">
                📊 Módulo Planificación
            </div>
            
            <nav style="flex: 1; padding-top: 10px;">
                <a href="{{ route('inicio') }}" style="display: block; padding: 12px 20px; color: #475569; text-decoration: none;">🏠 Inicio</a>
                
                @can('ver entidades')
                <a href="{{ route('entidades.index') }}" style="display: block; padding: 12px 20px; color: #475569; text-decoration: none;">🏢 Entidades</a>
                @endcan

                @can('ver proyectos')
                <a href="{{ route('proyectos.index') }}" style="display: block; padding: 12px 20px; color: #475569; text-decoration: none;">📁 Proyectos</a>
                
                <a href="{{ route('avances.index') }}" style="display: block; padding: 12px 20px; color: #2563eb; font-weight: bold; text-decoration: none; background: #eff6ff;">
                    🚀 Seguimiento
                </a>
                @endcan

                <hr style="margin: 10px 20px; border: 0; border-top: 1px solid #f1f5f9;">
                
                <a href="{{ route('programas.index') }}" style="display: block; padding: 12px 20px; color: #475569; text-decoration: none;">📈 Reportes</a>
            </nav>

            <div style="padding: 20px; font-size: 0.8rem; color: #94a3b8; border-top: 1px solid #f1f5f9;">
                v1.0 - 2026
            </div>
        </aside>

        <main style="flex: 1; background: #f8fafc; padding: 40px;">
            @yield('content')
        </main>
    </div>

    @stack('scripts') </body>
</html>