@extends('layouts.app')

@section('content')
<div style="max-width: 800px; margin: 0 auto; padding: 20px;">
    <div style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h2 style="font-weight: 800; color: #1e293b; margin-bottom: 20px; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px;">
            🎯 Configurar Nuevo ODS y Metas
        </h2>

        <form action="{{ route('ODS.store') }}" method="POST" id="odsForm">
            @csrf
            
            {{-- Nombre del Objetivo --}}
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; color: #64748b; margin-bottom: 5px;">Nombre del Objetivo ODS</label>
                <input type="text" name="nombreObjetivo" placeholder="Ej: ODS 1: Fin de la Pobreza" required 
                       style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
            </div>

            {{-- Descripción --}}
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; color: #64748b; margin-bottom: 5px;">Descripción General</label>
                <textarea name="descripcion" rows="3" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;"></textarea>
            </div>

            <hr style="border: 0; border-top: 1px solid #f1f5f9; margin: 30px 0;">

            {{-- SECCIÓN DE METAS DINÁMICAS --}}
            <div style="background: #f8fafc; padding: 20px; border-radius: 12px; border: 1px dashed #cbd5e1;">
                <h4 style="margin-top: 0; color: #334155;">📋 Agregar Metas Específicas</h4>
                
                <div style="display: flex; gap: 10px; margin-bottom: 15px;">
                    <input type="text" id="nuevaMetaInput" placeholder="Escriba una meta aquí..." 
                           style="flex-grow: 1; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
                    <button type="button" onclick="agregarMetaALista()" 
                            style="padding: 10px 20px; background: #0f172a; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold;">
                        + Agregar Meta
                    </button>
                </div>

                {{-- Lista visual de metas --}}
                <ul id="listaMetasVisual" style="list-style: none; padding: 0;">
                    <p id="sinMetas" style="color: #94a3b8; font-style: italic;">No hay metas agregadas aún.</p>
                </ul>

                {{-- Campo oculto que guardará todas las metas para el controlador --}}
                <input type="hidden" name="metasAsociadas" id="metasAsociadasHidden">
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 30px;">
                <a href="{{ route('ODS.index') }}" style="padding: 12px 25px; background: #94a3b8; color: white; border-radius: 8px; text-decoration: none;">Cancelar</a>
                <button type="submit" style="padding: 12px 30px; background: #2563eb; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">
                    Guardar ODS Completo
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let metasArray = [];

    function agregarMetaALista() {
        const input = document.getElementById('nuevaMetaInput');
        const valor = input.value.trim();

        if (valor === "") return;

        // 1. Agregar al array
        metasArray.push(valor);

        // 2. Actualizar el campo oculto
        document.getElementById('metasAsociadasHidden').value = metasArray.join("\n");

        // 3. Actualizar la lista visual
        renderizarLista();

        // 4. Limpiar input
        input.value = "";
        input.focus();
    }

    function eliminarMeta(index) {
        metasArray.splice(index, 1);
        document.getElementById('metasAsociadasHidden').value = metasArray.join("\n");
        renderizarLista();
    }

    function renderizarLista() {
        const lista = document.getElementById('listaMetasVisual');
        const placeholder = document.getElementById('sinMetas');
        
        lista.innerHTML = "";
        
        if (metasArray.length === 0) {
            lista.appendChild(placeholder);
            return;
        }

        metasArray.forEach((meta, index) => {
            const li = document.createElement('li');
            li.style = "background: white; padding: 10px; border-radius: 8px; margin-bottom: 5px; border: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;";
            li.innerHTML = `
                <span><strong>${index + 1}.</strong> ${meta}</span>
                <button type="button" onclick="eliminarMeta(${index})" style="color: #ef4444; background: none; border: none; cursor: pointer; font-weight: bold;">Eliminar</button>
            `;
            lista.appendChild(li);
        });
    }
</script>
@endsection