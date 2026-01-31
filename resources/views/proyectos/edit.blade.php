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

        {{-- SECCIÓN 1: DATOS GENERALES --}}
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

        {{-- VINCULACIÓN ODS --}}
        <div style="margin-bottom: 25px; padding: 20px; background: #fdfaf0; border-radius: 12px; border: 1px solid #fef3c7;">
            <h3 style="font-size: 1rem; color: #92400e; margin-bottom: 15px; font-weight: bold; display: flex; align-items: center; gap: 8px;">
                🌍 Alineación con ODS
            </h3>
            <div style="margin-bottom: 15px;">
                <label style="display: block; color: #64748b; font-size: 0.85rem; font-weight: 600;">Seleccionar Objetivo ODS</label>
                <select id="ods_selector" name="ods_id" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; background: white;">
                    <option value="">-- Seleccione un ODS --</option>
                    @foreach($objetivosODS as $ods)
                        @php 
                            $isSelected = old('ods_id', $proyecto->ods_id) == $ods->id;
                        @endphp
                        <option value="{{ $ods->id }}" data-metas="{{ $ods->metasAsociadas }}" {{ $isSelected ? 'selected' : '' }}>
                            {{ $ods->id }}. {{ $ods->nombreObjetivo }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display: block; color: #64748b; font-size: 0.85rem; font-weight: 600; margin-bottom: 10px;">Metas del ODS</label>
                <div id="metas_ods_container" 
                     data-guardadas="{{ old('metas_ods', $proyecto->metas_ods) }}"
                     style="width: 100%; min-height: 100px; max-height: 250px; overflow-y: auto; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; background: #ffffff; color: #475569; font-size: 0.9rem;">
                </div>
            </div>
        </div>

        {{-- SECCIÓN INDICADORES (NUEVA EN EDIT) --}}
        <div style="margin-bottom: 25px; padding: 20px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px;">
            <h3 style="font-size: 1rem; color: #166534; margin-bottom: 15px; font-weight: bold; display: flex; align-items: center; gap: 8px;">
                📊 Indicadores de Metas
            </h3>
            
            <div id="indicadores_dinamicos_container">
                @foreach($proyecto->indicadores ?? [] as $indicador)
                <div id="ind_old_{{ $indicador->id }}" style="background: white; border: 1px solid #e2e8f0; padding: 15px; border-radius: 8px; margin-bottom: 12px; position: relative; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                    <button type="button" onclick="this.parentElement.remove()" style="position: absolute; right: 10px; top: 10px; color: #ef4444; border: none; background: none; font-weight: bold; cursor: pointer;">✕</button>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 10px;">
                        <div>
                            <label style="display: block; font-size: 0.75rem; font-weight: bold; color: #64748b;">Meta Objetivo</label>
                            <select name="indicadores[{{ $indicador->id }}][meta_texto]" class="select-meta-indicador" required style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px;">
                                <option value="{{ $indicador->meta_ods_texto }}">{{ Str::limit($indicador->meta_ods_texto, 70) }}</option>
                            </select>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.75rem; font-weight: bold; color: #64748b;">Nombre del Indicador</label>
                            <input type="text" name="indicadores[{{ $indicador->id }}][nombre]" value="{{ $indicador->nombre_indicador }}" required style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px;">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 15px;">
                        <div>
                            <label style="display: block; font-size: 0.75rem; font-weight: bold; color: #64748b;">Descripción</label>
                            <input type="text" name="indicadores[{{ $indicador->id }}][descripcion]" value="{{ $indicador->descripcion }}" style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.75rem; font-weight: bold; color: #64748b;">Valor Meta (Fijo)</label>
                            <input type="number" name="indicadores[{{ $indicador->id }}][valor_fijo]" value="{{ $indicador->valor_meta_fijo }}" step="0.01" required style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px;">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <button type="button" onclick="agregarIndicadorMeta()" style="margin-top: 10px; background: #16a34a; color: white; padding: 8px 15px; border: none; border-radius: 6px; cursor: pointer; font-size: 0.85rem; font-weight: bold;">
                + Agregar Nuevo Indicador
            </button>
        </div>

        {{-- DESCRIPCIÓN Y OBJETIVOS --}}
        <div style="margin-bottom: 20px;">
            <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Descripción General</label>
            <textarea name="descripcion" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;" rows="3">{{ old('descripcion', $proyecto->descripcion) }}</textarea>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Objetivos del Proyecto</label>
            <textarea name="objetivos" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;" rows="3">{{ old('objetivos', $proyecto->objetivos) }}</textarea>
        </div>

        {{-- TERRITORIALIZACIÓN --}}
        <div style="margin-bottom: 25px; padding: 20px; background: #f0f9ff; border-radius: 12px; border: 1px solid #bae6fd;">
            <h3 style="font-size: 1rem; color: #0369a1; margin-bottom: 15px; font-weight: bold;">📍 Clasificación Geográfica</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                <div>
                    <label style="display: block; color: #64748b; font-size: 0.8rem; font-weight: 600;">Provincia</label>
                    <input type="text" name="ubicacion_provincia" value="{{ old('ubicacion_provincia', $proyecto->ubicacion_provincia) }}" style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #cbd5e1;">
                </div>
                <div>
                    <label style="display: block; color: #64748b; font-size: 0.8rem; font-weight: 600;">Cantón</label>
                    <input type="text" name="ubicacion_canton" value="{{ old('ubicacion_canton', $proyecto->ubicacion_canton) }}" style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #cbd5e1;">
                </div>
                <div>
                    <label style="display: block; color: #64748b; font-size: 0.8rem; font-weight: 600;">Parroquia</label>
                    <input type="text" name="ubicacion_parroquia" value="{{ old('ubicacion_parroquia', $proyecto->ubicacion_parroquia) }}" style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #cbd5e1;">
                </div>
            </div>
        </div>

        {{-- PROGRAMA Y PND --}}
        <div style="margin-bottom: 20px;">
            <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Programa Vinculado</label>
            <select name="programa_id" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; background: white;">
                @foreach($programas as $programa)
                    <option value="{{ $programa->id }}" {{ old('programa_id', $proyecto->programa_id) == $programa->id ? 'selected' : '' }}>
                        {{ $programa->nombrePrograma }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 25px; padding: 20px; background: #eff6ff; border: 1px dashed #3b82f6; border-radius: 12px;">
            <label style="display: block; color: #1e40af; font-weight: bold; margin-bottom: 10px;">Alineación Estratégica: PND</label>
            <select name="pnd_objetivo_id" required style="width: 100%; padding: 10px; border: 1px solid #3b82f6; border-radius: 8px; margin-bottom: 10px; background: white;">
                @foreach($objetivosPND as $pnd)
                    <option value="{{ $pnd->id }}" {{ old('pnd_objetivo_id', $proyecto->pnd_objetivo_id) == $pnd->id ? 'selected' : '' }}>
                        [{{ $pnd->eje }}] - {{ $pnd->nombre_objetivo }}
                    </option>
                @endforeach
            </select>
            <textarea name="justificacion_pnd" rows="2" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">{{ old('justificacion_pnd', $proyecto->justificacion_pnd) }}</textarea>
        </div>

        {{-- RESPONSABLE Y CONTACTO --}}
        <div style="margin-bottom: 25px; padding: 20px; background: #fffbeb; border: 1px solid #fde68a; border-radius: 12px;">
            <h3 style="font-size: 1rem; color: #92400e; margin-bottom: 15px; font-weight: bold;">👤 Datos del Responsable</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 15px;">
                <div>
                    <label style="display: block; color: #64748b; font-weight: 600;">Nombre del Responsable</label>
                    <input type="text" name="responsable" value="{{ old('responsable', $proyecto->responsable) }}" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
                </div>
                <div>
                    <label style="display: block; color: #64748b; font-weight: 600;">Beneficio Social</label>
                    <input type="text" name="beneficio" value="{{ old('beneficio', $proyecto->beneficio) }}" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label style="display: block; color: #64748b; font-weight: 600;">Correo Electrónico</label>
                    <input type="email" name="correo_contacto" value="{{ old('correo_contacto', $proyecto->correo_contacto) }}" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
                </div>
                <div>
                    <label style="display: block; color: #64748b; font-weight: 600;">Teléfono</label>
                    <input type="text" name="telefono_contacto" value="{{ old('telefono_contacto', $proyecto->telefono_contacto) }}" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
                </div>
            </div>
        </div>

        {{-- PRESUPUESTO Y ESTADO --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Presupuesto Estimado ($)</label>
                <input type="number" name="presupuesto_estimado" step="0.01" value="{{ old('presupuesto_estimado', $proyecto->presupuesto_estimado) }}" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
            </div>
            <div>
                <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Estado</label>
                <select name="estado" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; background: white;">
                    <option value="Pendiente" {{ $proyecto->estado == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="En Revisión" {{ $proyecto->estado == 'En Revisión' ? 'selected' : '' }}>En Revisión</option>
                    <option value="Aprobado" {{ $proyecto->estado == 'Aprobado' ? 'selected' : '' }}>Aprobado</option>
                </select>
            </div>
        </div>

        {{-- FECHAS --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
            <div>
                <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Fecha Inicio</label>
                <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio', $proyecto->fecha_inicio) }}" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
            </div>
            <div>
                <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Fecha Final</label>
                <input type="date" name="fecha_final" value="{{ old('fecha_final', $proyecto->fecha_final) }}" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
            </div>
        </div>

        {{-- BOTONES --}}
        <div style="display: flex; gap: 15px; border-top: 1px solid #f1f5f9; padding-top: 25px;">
            <button type="submit" style="flex: 2; background: #2563eb; color: white; padding: 12px; border: none; border-radius: 8px; cursor: pointer; font-weight: bold;">
                💾 Actualizar Proyecto
            </button>
            <a href="{{ route('proyectos.index') }}" style="flex: 1; text-align: center; background: #f1f5f9; color: #475569; padding: 12px; border-radius: 8px; text-decoration: none; font-weight: bold;">
                Cancelar
            </a>
        </div>
    </form>
</div>

<script>
    // --- Lógica de Metas ODS ---
    function renderizarMetas() {
        const selector = document.getElementById('ods_selector');
        const container = document.getElementById('metas_ods_container');
        const selectedOption = selector.options[selector.selectedIndex];
        const metasRaw = selectedOption.getAttribute('data-metas');
        const guardadasRaw = container.getAttribute('data-guardadas') || "";

        container.innerHTML = ""; 

        if (metasRaw && metasRaw.trim() !== "") {
            const metasArray = metasRaw.split('\n');

            metasArray.forEach((meta, index) => {
                const textoMeta = meta.trim();
                if (textoMeta !== "") {
                    const div = document.createElement('div');
                    div.style.cssText = "display:flex; align-items:flex-start; gap:10px; margin-bottom:8px; padding:5px; border-bottom:1px solid #f1f5f9;";

                    const checkbox = document.createElement('input');
                    checkbox.type = "checkbox";
                    checkbox.name = "metas_ods_array[]"; 
                    checkbox.className = "meta-checkbox";
                    checkbox.value = textoMeta;
                    checkbox.id = "meta_" + index;

                    if (guardadasRaw.includes(textoMeta)) {
                        checkbox.checked = true;
                    }

                    const label = document.createElement('label');
                    label.htmlFor = "meta_" + index;
                    label.textContent = textoMeta;
                    label.style.cssText = "font-size:0.85rem; cursor:pointer;";

                    div.appendChild(checkbox);
                    div.appendChild(label);
                    container.appendChild(div);
                }
            });
        } else {
            container.innerHTML = '<p style="color: #94a3b8; font-style: italic;">Seleccione un ODS para ver sus metas vinculadas.</p>';
        }
        actualizarSelectsDeIndicadores();
    }

    // --- Lógica Indicadores Dinámicos ---
    function obtenerMetasSeleccionadas() {
        const checkboxes = document.querySelectorAll('.meta-checkbox:checked');
        return Array.from(checkboxes).map(cb => cb.value);
    }

    function actualizarSelectsDeIndicadores() {
        const metas = obtenerMetasSeleccionadas();
        const selects = document.querySelectorAll('.select-meta-indicador');
        
        selects.forEach(select => {
            const valorPrevio = select.value;
            select.innerHTML = '<option value="">-- Escoger Meta --</option>';
            metas.forEach(meta => {
                const opt = document.createElement('option');
                opt.value = meta;
                opt.textContent = meta.length > 70 ? meta.substring(0, 70) + '...' : meta;
                if(meta === valorPrevio) opt.selected = true;
                select.appendChild(opt);
            });
        });
    }

    function agregarIndicadorMeta() {
        const metas = obtenerMetasSeleccionadas();
        if (metas.length === 0) {
            alert("⚠️ Seleccione al menos una meta ODS arriba.");
            return;
        }

        const container = document.getElementById('indicadores_dinamicos_container');
        const idUnico = Date.now();

        const template = `
            <div id="ind_${idUnico}" style="background: white; border: 1px solid #e2e8f0; padding: 15px; border-radius: 8px; margin-bottom: 12px; position: relative;">
                <button type="button" onclick="this.parentElement.remove()" style="position: absolute; right: 10px; top: 10px; color: #ef4444; border: none; background: none; cursor: pointer;">✕</button>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 10px;">
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: bold; color: #64748b;">Meta Objetivo</label>
                        <select name="indicadores[new_${idUnico}][meta_texto]" class="select-meta-indicador" required style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px;">
                            ${metas.map(m => `<option value="${m}">${m.substring(0, 70)}...</option>`).join('')}
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: bold; color: #64748b;">Nombre del Indicador</label>
                        <input type="text" name="indicadores[new_${idUnico}][nombre]" required style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px;">
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 15px;">
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: bold; color: #64748b;">Descripción</label>
                        <input type="text" name="indicadores[new_${idUnico}][descripcion]" style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: bold; color: #64748b;">Valor Meta (Fijo)</label>
                        <input type="number" name="indicadores[new_${idUnico}][valor_fijo]" step="0.01" required style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px;">
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', template);
    }

    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('meta-checkbox')) {
            actualizarSelectsDeIndicadores();
        }
    });

    document.getElementById('ods_selector').addEventListener('change', renderizarMetas);
    window.addEventListener('DOMContentLoaded', renderizarMetas);
</script>
@endsection