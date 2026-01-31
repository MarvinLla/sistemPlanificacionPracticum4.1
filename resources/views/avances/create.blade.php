@extends('layouts.app')

@section('content')
<div style="max-width: 700px; margin: auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
    <h2 style="margin-bottom: 20px; color: #1e293b; font-weight: bold;">Registrar Avance de Proyecto</h2>

    {{-- Bloque para mostrar errores de validación de Laravel --}}
    @if ($errors->any())
        <div style="background: #fee2e2; color: #b91c1c; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem;">
            <strong>⚠️ Por favor corrige los siguientes errores:</strong>
            <ul style="margin-top: 5px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('avances.store') }}" method="POST" id="formAvance" enctype="multipart/form-data">
        @csrf
        
        {{-- Selección de Proyecto --}}
        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Seleccionar Proyecto</label>
            <select name="proyecto_id" id="proyecto_id" required onchange="filtrarIndicadores()" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;">
                <option value="">Elija un proyecto...</option>
                @foreach($proyectos as $proy)
                    <option value="{{ $proy->id }}" {{ old('proyecto_id') == $proy->id ? 'selected' : '' }}>{{ $proy->nombre }}</option>
                @endforeach
            </select>
        </div>

        {{-- Selección de Indicador --}}
        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Indicador de Meta Relacionado</label>
            <select name="indicador_proyecto_id" id="indicador_proyecto_id" required onchange="actualizarMetaDisponible()" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;">
                <option value="">-- Primero elija un proyecto --</option>
                @foreach($proyectos as $proy)
                    @foreach($proy->indicadores as $ind)
                        @php
                            $yaGastado = $ind->avances ? $ind->avances->sum('monto_gastado') : 0;
                            $disponibleInd = $ind->valor_meta_fijo - $yaGastado;
                        @endphp
                        <option value="{{ $ind->id }}" 
                                data-proyecto="{{ $proy->id }}" 
                                data-disponible="{{ $disponibleInd }}" 
                                {{ old('indicador_proyecto_id') == $ind->id ? 'selected' : '' }}
                                style="display: none;">
                            {{ $ind->nombre_indicador }} (Saldo: ${{ number_format($disponibleInd, 2) }})
                        </option>
                    @endforeach
                @endforeach
            </select>
            
            <div id="info_meta" style="margin-top: 8px; padding: 10px; background: #eff6ff; border-radius: 8px; border: 1px dashed #3b82f6; display: none;">
                <span style="color: #1e40af; font-size: 0.9rem; font-weight: bold;">💰 Saldo disponible en esta meta: $<span id="valor_disponible">0.00</span></span>
            </div>
        </div>

        {{-- Título y Fecha --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
            <div>
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Título del Avance</label>
                <input type="text" name="titulo" value="{{ old('titulo') }}" required placeholder="Ej: Compra de insumos" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;">
            </div>
            <div>
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Fecha del Avance</label>
                <input type="date" name="fecha_avance" value="{{ old('fecha_avance', date('Y-m-d')) }}" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;">
            </div>
        </div>

        {{-- Monto --}}
        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Monto a Ejecutar ($)</label>
            <input type="number" name="monto_gastado" id="monto_gastado" value="{{ old('monto_gastado') }}" step="0.01" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;">
        </div>

        {{-- EVIDENCIA (Archivo) --}}
        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Subir Evidencia (Opcional)</label>
            <input type="file" name="archivo" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; background: #f8fafc;">
            <small style="color: #64748b;">Formatos aceptados: PDF, JPG, PNG (Máx. 5MB)</small>
        </div>

        {{-- Mensaje de Error Dinámico JS --}}
        <div id="msg_error" style="display: none; background: #fee2e2; color: #b91c1c; padding: 10px; border-radius: 8px; margin-bottom: 15px; font-size: 0.85rem; font-weight: bold;">
            ⚠️ El monto ingresado excede el saldo de la meta seleccionada.
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Descripción / Justificación</label>
            <textarea name="descripcion" rows="3" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;">{{ old('descripcion') }}</textarea>
        </div>

        <button type="submit" id="btnGuardar" style="width: 100%; background: #059669; color: white; padding: 14px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; transition: 0.3s;">
            ✅ Guardar Avance y Actualizar Porcentaje
        </button>
    </form>
</div>

<script>
// Al cargar la página, si hay un proyecto seleccionado (por error de validación), filtrar
document.addEventListener('DOMContentLoaded', function() {
    if(document.getElementById('proyecto_id').value) {
        filtrarIndicadores(true);
    }
});

function filtrarIndicadores(isReload = false) {
    const proyectoId = document.getElementById('proyecto_id').value;
    const selectInd = document.getElementById('indicador_proyecto_id');
    const options = selectInd.options;

    for (let i = 0; i < options.length; i++) {
        const opt = options[i];
        if (opt.value === "") continue;
        opt.style.display = (opt.getAttribute('data-proyecto') == proyectoId) ? 'block' : 'none';
    }
    
    if(!isReload) selectInd.value = "";
    actualizarMetaDisponible();
}

function actualizarMetaDisponible() {
    const select = document.getElementById('indicador_proyecto_id');
    const selectedOption = select.options[select.selectedIndex];
    const infoMeta = document.getElementById('info_meta');
    const valorDisp = document.getElementById('valor_disponible');

    if (selectedOption && selectedOption.value !== "") {
        const disponible = parseFloat(selectedOption.getAttribute('data-disponible'));
        valorDisp.innerText = disponible.toLocaleString('en-US', {minimumFractionDigits: 2});
        infoMeta.style.display = 'block';
    } else {
        infoMeta.style.display = 'none';
    }
}

document.getElementById('monto_gastado').addEventListener('input', function() {
    const select = document.getElementById('indicador_proyecto_id');
    const selectedOption = select.options[select.selectedIndex];
    const btn = document.getElementById('btnGuardar');
    const msg = document.getElementById('msg_error');

    if (selectedOption && selectedOption.value !== "") {
        const disponible = parseFloat(selectedOption.getAttribute('data-disponible'));
        const ingresado = parseFloat(this.value);

        if (ingresado > disponible) {
            this.style.border = "2px solid #ef4444";
            msg.style.display = "block";
            btn.disabled = true;
            btn.style.opacity = "0.5";
            btn.style.cursor = "not-allowed";
        } else {
            this.style.border = "1px solid #cbd5e1";
            msg.style.display = "none";
            btn.disabled = false;
            btn.style.opacity = "1";
            btn.style.cursor = "pointer";
        }
    }
});
</script>
@endsection