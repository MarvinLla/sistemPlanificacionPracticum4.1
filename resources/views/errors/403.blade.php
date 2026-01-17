@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: center; align-items: center; min-height: 70vh;">
    <div style="text-align: center; background: white; padding: 50px; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); max-width: 500px;">
        <div style="font-size: 80px; color: #dc2626; margin-bottom: 20px;">
            🔒
        </div>
        
        <h1 style="font-size: 2.5rem; font-weight: bold; color: #1e293b; margin-bottom: 10px;">Acceso Restringido</h1>
        
        <p style="color: #64748b; font-size: 1.1rem; line-height: 1.6; margin-bottom: 30px;">
            Lo sentimos, no tienes los permisos necesarios para realizar esta acción o entrar a esta sección.
        </p>

        <div style="display: flex; flex-direction: column; gap: 10px;">
            <a href="{{ url()->previous() }}" style="background: #2563eb; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: bold; transition: background 0.3s;">
                Volver Atrás
            </a>
            
            <a href="{{ route('inicio') }}" style="color: #64748b; text-decoration: none; font-size: 0.9rem; font-weight: 500;">
                Ir al Panel de Inicio
            </a>
        </div>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #f1f5f9; color: #94a3b8; font-size: 0.8rem;">
            Si crees que esto es un error, contacta al administrador del sistema.
        </div>
    </div>
</div>
@endsection