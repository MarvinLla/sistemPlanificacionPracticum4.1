@extends('layouts.app')

@section('content')
<div style="max-width: 900px; margin: auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
    <h2 style="font-size: 1.6rem; font-weight: bold; color: #1e293b; margin-bottom: 25px; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px;">
        Crear Nuevo Proyecto / Plan
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

    <form action="{{ route('proyectos.store') }}" method="POST">
        @csrf

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Nombre del Proyecto</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
            </div>
            <div>
                <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Entidad Responsable</label>
                <select name="entidad_id" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; background: white;">
                    <option value="">Seleccione una entidad...</option>
                    @foreach($entidades as $entidad)
                        <option value="{{ $entidad->id }}" {{ old('entidad_id') == $entidad->id ? 'selected' : '' }}>
                            {{ $entidad->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Programa Vinculado</label>
            <select name="programa_id" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; background: white;">
                <option value="">Seleccione un programa...</option>
                @foreach($programas as $programa)
                    <option value="{{ $programa->id }}" {{ old('programa_id') == $programa->id ? 'selected' : '' }}>
                        {{ $programa->nombrePrograma }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Descripción Detallada del Proyecto</label>
            <textarea name="descripcion" rows="3" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;" placeholder="Explique la naturaleza del proyecto...">{{ old('descripcion') }}</textarea>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Objetivos del Proyecto</label>
            <textarea name="objetivos" rows="2" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;" placeholder="¿Qué busca lograr este proyecto?">{{ old('objetivos') }}</textarea>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 10px;">Objetivos ODS y Metas Específicas</label>
            <div style="max-height: 300px; overflow-y: auto; border: 1px solid #cbd5e1; border-radius: 10px; padding: 15px; background: #f8fafc;">
                @foreach($ods as $item)
                    <div style="background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; margin-bottom: 15px;">
                        <div style="display: flex; align-items: center; gap: 10px; font-weight: bold; color: #1e293b; margin-bottom: 10px; border-bottom: 1px solid #f1f5f9; padding-bottom: 5px;">
                            <span style="background: #3b82f6; color: white; padding: 2px 8px; border-radius: 4px; font-size: 0.8rem;">ODS</span>
                            {{ $item->nombreObjetivo }}
                        </div>
                        <div style="margin-left: 10px;">
                            @php $metas = explode("\n", $item->metasAsociadas); @endphp
                            @foreach($metas as $index => $meta)
                                @if(trim($meta) != "")
                                    <label style="display: flex; align-items: flex-start; gap: 8px; padding: 5px; cursor: pointer; font-size: 0.85rem; color: #475569; border-radius: 4px; transition: background 0.2s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
                                        <input type="checkbox" name="metas_seleccionadas[]" value="{{ $item->id }}_{{ $index }}" style="width: 15px; height: 15px; margin-top: 3px;">
                                        <span>{{ trim($meta) }}</span>
                                    </label>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div style="margin-bottom: 25px; padding: 20px; background: #eff6ff; border: 1px dashed #3b82f6; border-radius: 12px;">
            <label style="display: block; color: #1e40af; font-weight: bold; margin-bottom: 10px; font-size: 1.1rem;">
                Alineación Estratégica: Plan Nacional de Desarrollo (PND)
            </label>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Objetivo Estratégico Nacional</label>
                <select name="pnd_objetivo_id" required style="width: 100%; padding: 12px; border: 1px solid #3b82f6; border-radius: 8px; background: white; font-size: 0.9rem;">
                    <option value="">-- Seleccione el Objetivo Nacional que impacta este proyecto --</option>
                    @foreach($objetivosPND as $pnd)
                        <option value="{{ $pnd->id }}" {{ old('pnd_objetivo_id') == $pnd->id ? 'selected' : '' }}>
                            [{{ $pnd->eje }}] - {{ $pnd->nombre_objetivo }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Justificación de la Alineación</label>
                <textarea name="justificacion_pnd" rows="3" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;" placeholder="Describa cómo este proyecto ayuda a cumplir el objetivo nacional seleccionado...">{{ old('justificacion_pnd') }}</textarea>
                <small style="color: #64748b;">Mencione la problemática nacional que este proyecto busca resolver (Requerido para Examen Complexivo).</small>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Presupuesto Estimado ($)</label>
                <input type="number" name="presupuesto_estimado" step="0.01" value="{{ old('presupuesto_estimado') }}" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
            </div>

            <div>
                <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Estado del Proyecto</label>
                @can('cambiar estados') 
                    <select name="estado" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; background: #f0fdf4; border-color: #bbf7d0;">
                        <option value="Pendiente" {{ old('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="En Revisión" {{ old('estado') == 'En Revisión' ? 'selected' : '' }}>En Revisión</option>
                        <option value="Aprobado" {{ old('estado') == 'Aprobado' ? 'selected' : '' }}>Aprobado</option>
                    </select>
                @else
                    <div style="padding: 10px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; color: #64748b;">
                        <span style="display: flex; align-items: center; gap: 5px;">
                            ⏳ Automaticamente: <strong>Pendiente</strong>
                        </span>
                    </div>
                    <input type="hidden" name="estado" value="Pendiente">
                @endcan
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Nombre del Responsable</label>
                <input type="text" name="responsable" value="{{ old('responsable') }}" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
            </div>
            <div>
                <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Beneficio para la Ciudadanía</label>
                <input type="text" name="beneficio" value="{{ old('beneficio') }}" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Fecha de Inicio</label>
                <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio') }}" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
            </div>
            <div>
                <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Fecha de Finalización</label>
                <input type="date" name="fecha_final" value="{{ old('fecha_final') }}" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
            <div>
                <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Correo de Contacto</label>
                <input type="email" name="correo_contacto" value="{{ old('correo_contacto') }}" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
            </div>
            <div>
                <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Número de Teléfono</label>
                <input type="text" name="telefono_contacto" value="{{ old('telefono_contacto') }}" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
            </div>
        </div>

        <div style="display: flex; gap: 15px; border-top: 1px solid #f1f5f9; padding-top: 25px;">
            <button type="submit" style="flex: 2; background: #2563eb; color: white; padding: 12px; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 1rem;">
                🚀 Registrar Proyecto para Revisión
            </button>
            <a href="{{ route('proyectos.index') }}" style="flex: 1; text-align: center; background: #f1f5f9; color: #475569; padding: 12px; border-radius: 8px; text-decoration: none; font-weight: bold; line-height: 1.5;">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection