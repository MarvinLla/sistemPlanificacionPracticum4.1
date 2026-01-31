@extends('layouts.app')

@section('content')
<div style="max-width: 900px; margin: auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
    <h2 style="font-size: 1.6rem; font-weight: bold; color: #1e293b; margin-bottom: 25px; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px;">
        Crear Nuevo Proyecto
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

        {{-- SECCIÓN 1: DATOS GENERALES --}}
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
                        <option value="{{ $ods->id }}" data-metas="{{ $ods->metasAsociadas }}" {{ old('ods_id') == $ods->id ? 'selected' : '' }}>
                            {{ $ods->id }}. {{ $ods->nombreObjetivo }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display: block; color: #64748b; font-size: 0.85rem; font-weight: 600; margin-bottom: 10px;">Metas del ODS (Seleccione las que apliquen)</label>
                <div id="metas_ods_container" 
                     data-guardadas="{{ old('metas_finales') }}"
                     style="width: 100%; min-height: 100px; max-height: 250px; overflow-y: auto; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; background: #ffffff; color: #475569; font-size: 0.9rem;">
                    <p style="color: #94a3b8; font-style: italic;">Seleccione un ODS para ver sus metas...</p>
                </div>
            </div>
        </div>

        {{-- NUEVA SECCIÓN: INDICADORES DINÁMICOS POR META --}}
        <div style="margin-bottom: 25px; padding: 20px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px;">
            <h3 style="font-size: 1rem; color: #166534; margin-bottom: 15px; font-weight: bold; display: flex; align-items: center; gap: 8px;">
                📊 Indicadores de Metas Seleccionadas
            </h3>
            
            <div id="indicadores_dinamicos_container">
                {{-- Aquí se insertarán los bloques de indicadores --}}
            </div>

            <button type="button" onclick="agregarIndicadorMeta()" style="margin-top: 10px; background: #16a34a; color: white; padding: 8px 15px; border: none; border-radius: 6px; cursor: pointer; font-size: 0.85rem; font-weight: bold;">
                + Agregar Indicador a Meta
            </button>
        </div>

        {{-- DESCRIPCIÓN Y OBJETIVOS --}}
        <div style="margin-bottom: 20px;">
            <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Descripción General</label>
            <textarea name="descripcion" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;" rows="3">{{ old('descripcion') }}</textarea>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Objetivos del Proyecto</label>
            <textarea name="objetivos" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;" rows="3">{{ old('objetivos') }}</textarea>
        </div>

        {{-- TERRITORIALIZACIÓN --}}
        <div style="margin-bottom: 25px; padding: 20px; background: #f0f9ff; border-radius: 12px; border: 1px solid #bae6fd;">
            <h3 style="font-size: 1rem; color: #0369a1; margin-bottom: 15px; font-weight: bold;">📍 Clasificación Geográfica</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                <div>
                    <label style="display: block; color: #64748b; font-size: 0.8rem; font-weight: 600;">Provincia</label>
                    <input type="text" name="ubicacion_provincia" value="{{ old('ubicacion_provincia') }}" style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #cbd5e1;">
                </div>
                <div>
                    <label style="display: block; color: #64748b; font-size: 0.8rem; font-weight: 600;">Cantón</label>
                    <input type="text" name="ubicacion_canton" value="{{ old('ubicacion_canton') }}" style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #cbd5e1;">
                </div>
                <div>
                    <label style="display: block; color: #64748b; font-size: 0.8rem; font-weight: 600;">Parroquia</label>
                    <input type="text" name="ubicacion_parroquia" value="{{ old('ubicacion_parroquia') }}" style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #cbd5e1;">
                </div>
            </div>
        </div>

        {{-- PROGRAMA Y PND --}}
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

        <div style="margin-bottom: 25px; padding: 20px; background: #eff6ff; border: 1px dashed #3b82f6; border-radius: 12px;">
            <label style="display: block; color: #1e40af; font-weight: bold; margin-bottom: 10px;">Alineación Estratégica: PND</label>
            <select name="pnd_objetivo_id" required style="width: 100%; padding: 10px; border: 1px solid #3b82f6; border-radius: 8px; margin-bottom: 10px; background: white;">
                <option value="">-- Seleccione el Objetivo Nacional --</option>
                @foreach($objetivosPND as $pnd)
                    <option value="{{ $pnd->id }}" {{ old('pnd_objetivo_id') == $pnd->id ? 'selected' : '' }}>
                        [{{ $pnd->eje }}] - {{ $pnd->nombre_objetivo }}
                    </option>
                @endforeach
            </select>
            <textarea name="justificacion_pnd" rows="2" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;" placeholder="Justificación...">{{ old('justificacion_pnd') }}</textarea>
        </div>

        {{-- DATOS DEL RESPONSABLE --}}
        <div style="margin-bottom: 25px; padding: 20px; background: #fffbeb; border: 1px solid #fde68a; border-radius: 12px;">
            <h3 style="font-size: 1rem; color: #92400e; margin-bottom: 15px; font-weight: bold; display: flex; align-items: center; gap: 8px;">
                👤 Datos del Responsable
            </h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 15px;">
                <div>
                    <label style="display: block; color: #64748b; font-weight: 600;">Nombre del Responsable</label>
                    <input type="text" name="responsable" value="{{ old('responsable') }}" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
                </div>
                <div>
                    <label style="display: block; color: #64748b; font-weight: 600;">Beneficio Social</label>
                    <input type="text" name="beneficio" value="{{ old('beneficio') }}" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label style="display: block; color: #64748b; font-weight: 600;">Correo Electrónico</label>
                    <input type="email" name="correo_contacto" value="{{ old('correo_contacto') }}" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
                </div>
                <div>
                    <label style="display: block; color: #64748b; font-weight: 600;">Teléfono</label>
                    <input type="text" name="telefono_contacto" value="{{ old('telefono_contacto') }}" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
                </div>
            </div>
        </div>

        {{-- PRESUPUESTO Y ESTADO --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Presupuesto Estimado ($)</label>
                <input type="number" name="presupuesto_estimado" step="0.01" value="{{ old('presupuesto_estimado') }}" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
            </div>
            <div>
                <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Estado</label>
                <select name="estado" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; background: white;">
                    <option value="Pendiente" {{ old('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="En Revisión" {{ old('estado') == 'En Revisión' ? 'selected' : '' }}>En Revisión</option>
                    <option value="Aprobado" {{ old('estado') == 'Aprobado' ? 'selected' : '' }}>Aprobado</option>
                </select>
            </div>
        </div>

        {{-- FECHAS --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
            <div>
                <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Fecha Inicio</label>
                <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio') }}" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
            </div>
            <div>
                <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Fecha Final</label>
                <input type="date" name="fecha_final" value="{{ old('fecha_final') }}" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
            </div>
        </div>

        <div style="display: flex; gap: 15px; border-top: 1px solid #f1f5f9; padding-top: 25px;">
            <button type="submit" style="flex: 2; background: #2563eb; color: white; padding: 12px; border: none; border-radius: 8px; cursor: pointer; font-weight: bold;">
                🚀 Registrar Proyecto
            </button>
            <a href="{{ route('proyectos.index') }}" style="flex: 1; text-align: center; background: #f1f5f9; color: #475569; padding: 12px; border-radius: 8px; text-decoration: none; font-weight: bold;">
                Cancelar
            </a>
        </div>
    </form>
</div>

<script>
    // --- Lógica de Metas ODS original ---
    function renderizarMetas() {
        const selector = document.getElementById('ods_selector');
        const container = document.getElementById('metas_ods_container');
        const selectedOption = selector.options[selector.selectedIndex];
        const metasRaw = selectedOption.getAttribute('data-metas');
        
        container.innerHTML = ""; 

        if (metasRaw && metasRaw.trim() !== "") {
            const metasArray = metasRaw.split('\n');

            metasArray.forEach((meta, index) => {
                const textoMeta = meta.trim();
                if (textoMeta !== "") {
                    const div = document.createElement('div');
                    div.style.display = "flex";
                    div.style.alignItems = "flex-start";
                    div.style.gap = "10px";
                    div.style.marginBottom = "8px";
                    div.style.padding = "5px";
                    div.style.borderBottom = "1px solid #f1f5f9";

                    const checkbox = document.createElement('input');
                    checkbox.type = "checkbox";
                    checkbox.name = "metas_ods_array[]"; 
                    checkbox.className = "meta-checkbox"; // Clase para listener
                    checkbox.value = textoMeta;
                    checkbox.id = "meta_" + index;

                    const label = document.createElement('label');
                    label.htmlFor = "meta_" + index;
                    label.textContent = textoMeta;
                    label.style.fontSize = "0.85rem";
                    label.style.cursor = "pointer";

                    div.appendChild(checkbox);
                    div.appendChild(label);
                    container.appendChild(div);
                }
            });
        } else {
            container.innerHTML = '<p style="color: #94a3b8; font-style: italic;">Seleccione un ODS para ver sus metas vinculadas.</p>';
        }
        // Al cambiar de ODS, limpiamos indicadores o actualizamos según prefieras
        actualizarSelectsDeIndicadores();
    }

    // --- NUEVA LÓGICA: INDICADORES DINÁMICOS ---

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
            alert("⚠️ Primero debe seleccionar al menos una meta del ODS en la sección de arriba.");
            return;
        }

        const container = document.getElementById('indicadores_dinamicos_container');
        const idUnico = Date.now();

        const template = `
            <div id="ind_${idUnico}" style="background: white; border: 1px solid #e2e8f0; padding: 15px; border-radius: 8px; margin-bottom: 12px; position: relative; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                <button type="button" onclick="this.parentElement.remove()" style="position: absolute; right: 10px; top: 10px; color: #ef4444; border: none; background: none; font-weight: bold; cursor: pointer;">✕</button>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 10px;">
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: bold; color: #64748b;">Meta Objetivo</label>
                        <select name="indicadores[${idUnico}][meta_texto]" class="select-meta-indicador" required style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px;">
                            <option value="">-- Escoger Meta --</option>
                            ${metas.map(m => `<option value="${m}">${m.substring(0, 70)}...</option>`).join('')}
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: bold; color: #64748b;">Nombre del Indicador</label>
                        <input type="text" name="indicadores[${idUnico}][nombre]" placeholder="Ej: % de población con acceso..." required style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 15px;">
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: bold; color: #64748b;">Descripción del Indicador</label>
                        <input type="text" name="indicadores[${idUnico}][descripcion]" placeholder="Breve detalle..." style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: bold; color: #64748b;">Valor Meta (Fijo)</label>
                        <input type="number" name="indicadores[${idUnico}][valor_fijo]" step="0.01" required placeholder="Ej: 100" style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px;">
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', template);
    }

    // Listener para actualizar selects de indicadores cuando se marquen metas
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('meta-checkbox')) {
            actualizarSelectsDeIndicadores();
        }
    });

    document.getElementById('ods_selector').addEventListener('change', renderizarMetas);
</script>
@endsection