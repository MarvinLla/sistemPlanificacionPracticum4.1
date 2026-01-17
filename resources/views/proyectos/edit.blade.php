@extends('layouts.app')

@section('content')
<div style="max-width: 900px; margin: auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
    <h2 style="font-size: 1.6rem; font-weight: bold; color: #1e293b; margin-bottom: 25px; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px;">
        Editar Proyecto: {{ $proyecto->nombre }}
    </h2>

    @if ($errors->any())
        <div style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <strong>¡Atención!</strong> Por favor corrige los siguientes errores:
            <ul style="margin-top: 5px; margin-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('proyectos.update', $proyecto->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Nombre del Proyecto</label>
                <input type="text" name="nombre" value="{{ old('nombre', $proyecto->nombre) }}" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
            </div>
            <div>
                <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Entidad Responsable</label>
                <select name="entidad_id" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; background: white;">
                    @foreach($entidades as $entidad)
                        <option value="{{ $entidad->id }}" {{ old('entidad_id', $proyecto->entidad_id) == $entidad->id ? 'selected' : '' }}>
                            {{ $entidad->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Descripción Detallada</label>
            <textarea name="descripcion" rows="3" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">{{ old('descripcion', $proyecto->descripcion) }}</textarea>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Objetivos Generales</label>
            <textarea name="objetivos" rows="2" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">{{ old('objetivos', $proyecto->objetivos) }}</textarea>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Objetivos ODS</label>
            <textarea name="objetivos_ods" rows="2" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">{{ old('objetivos_ods', $proyecto->objetivos_ods) }}</textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Presupuesto Estimado ($)</label>
                <input type="number" name="presupuesto_estimado" step="0.01" value="{{ old('presupuesto_estimado', $proyecto->presupuesto_estimado) }}" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
            </div>

            <div>
                <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Estado del Proyecto</label>
                @can('cambiar estados') 
                    <select name="estado" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; background: #f0fdf4; border-color: #bbf7d0;">
                        <option value="Pendiente" {{ old('estado', $proyecto->estado) == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="En Revisión" {{ old('estado', $proyecto->estado) == 'En Revisión' ? 'selected' : '' }}>En Revisión</option>
                        <option value="Aprobado" {{ old('estado', $proyecto->estado) == 'Aprobado' ? 'selected' : '' }}>Aprobado</option>
                    </select>
                @else
                    <div style="padding: 10px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; color: #64748b;">
                        Estado actual: <strong>{{ $proyecto->estado }}</strong>
                    </div>
                    <input type="hidden" name="estado" value="{{ $proyecto->estado }}">
                @endcan
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Nombre del Responsable</label>
                <input type="text" name="responsable" value="{{ old('responsable', $proyecto->responsable) }}" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
            </div>
            <div>
                <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Beneficio para la Ciudadanía</label>
                <input type="text" name="beneficio" value="{{ old('beneficio', $proyecto->beneficio) }}" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Fecha de Inicio</label>
                <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio', $proyecto->fecha_inicio) }}" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
            </div>
            <div>
                <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Fecha de Finalización</label>
                <input type="date" name="fecha_final" value="{{ old('fecha_final', $proyecto->fecha_final) }}" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
            <div>
                <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Correo de Contacto</label>
                <input type="email" name="correo_contacto" value="{{ old('correo_contacto', $proyecto->correo_contacto) }}" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
            </div>
            <div>
                <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Número de Teléfono</label>
                <input type="text" name="telefono_contacto" value="{{ old('telefono_contacto', $proyecto->telefono_contacto) }}" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
            </div>
        </div>

        <div style="display: flex; gap: 15px; border-top: 1px solid #f1f5f9; padding-top: 25px;">
            <button type="submit" style="flex: 2; background: #2563eb; color: white; padding: 12px; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 1rem;">
                💾 Actualizar Proyecto
            </button>
            <a href="{{ route('proyectos.index') }}" style="flex: 1; text-align: center; background: #f1f5f9; color: #475569; padding: 12px; border-radius: 8px; text-decoration: none; font-weight: bold; line-height: 1.5;">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection