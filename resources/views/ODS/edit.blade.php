@extends('layouts.app')

@section('content')
<div style="max-width: 800px; margin: 0 auto; padding: 20px;">
    <div style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h2 style="font-weight: 800; color: #1e293b; margin-bottom: 20px; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px;">
            📝 Editar ODS y Metas
        </h2>

        <form action="{{ route('ODS.update', $objetivoODS->id) }}" method="POST" id="odsForm">
            @csrf
            @method('PUT')
            
            {{-- Nombre --}}
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; color: #64748b; margin-bottom: 5px;">Nombre del Objetivo ODS</label>
                <input type="text" name="nombreObjetivo" value="{{ old('nombreObjetivo', $objetivoODS->nombreObjetivo) }}" required 
                       style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
            </div>

            {{-- Descripción --}}
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; color: #64748b; margin-bottom: 5px;">Descripción General</label>
                <textarea name="descripcion" rows="3" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">{{ old('descripcion', $objetivoODS->descripcion) }}</textarea>
            </div>

            <hr style="border: 0; border-top: 1px solid #f1f5f9; margin: 30px 0;">

            {{-- SECCIÓN DE METAS DINÁMICAS --}}
            <div style="background: #f0fdf4; padding: 20px; border-radius: 12px; border: 1px dashed #22c55e;">
                <h4 style="margin-top: 0; color: #166534;">📋 Gestionar Metas</h4>
                
                <div style="display: flex; gap: 10px; margin-bottom: 15px;">
                    <input type="text" id="nuevaMetaInput" placeholder="Escriba una nueva meta..." 
                           style="flex-grow: 1; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
                    <button type="button" onclick="agregarMetaALista()" 
                            style="padding: 10px 20px; background: #166534; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold;">
                        + Añadir
                    </button>
                </div>

                <ul id="listaMetasVisual" style="list-style: none; padding: 0;">
                    {{-- Se llenará con JS --}}
                </ul>

                {{-- Campo oculto que contiene el string final para la DB --}}
                <input type="hidden" name="metasAsociadas" id="metasAsociadasHidden" value="{{ $objetivoODS->metasAsociadas }}">
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 30px;">
                <a href="{{ route('ODS.index') }}" style="padding: 12px 25px; background: #94a3b8; color: white; border-radius: 8px; text-decoration: none;">Volver</a>
                <button type="submit" style="padding: 12px 30px; background: #10b981; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">
                    Actualizar Todo
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Inicializamos el array con las metas que ya existen en la base de datos
    // Usamos split para convertir el string de la DB en un array de JS
    let metasArray = {!! json_encode(explode("\n", $objetivoODS->metasAsociadas)) !!}.filter(item => item.trim() !== "");

    // Renderizar al cargar la página
    document.addEventListener('DOMContentLoaded', renderizarLista);

    function agregarMetaALista() {
        const input = document.getElementById('nuevaMetaInput');
        const valor = input.value.trim();
        if (valor === "") return;

        metasArray.push(valor);
        actualizarCampoOculto();
        renderizarLista();
        input.value = "";
        input.focus();
    }

    function eliminarMeta(index) {
        metasArray.splice(index, 1);
        actualizarCampoOculto();
        renderizarLista();
    }

    function actualizarCampoOculto() {
        document.getElementById('metasAsociadasHidden').value = metasArray.join("\n");
    }

    function renderizarLista() {
        const lista = document.getElementById('listaMetasVisual');
        lista.innerHTML = "";
        
        if (metasArray.length === 0) {
            lista.innerHTML = '<p id="sinMetas" style="color: #94a3b8; font-style: italic;">No hay metas para este objetivo.</p>';
            return;
        }

        metasArray.forEach((meta, index) => {
            const li = document.createElement('li');
            li.style = "background: white; padding: 10px; border-radius: 8px; margin-bottom: 5px; border: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.02);";
            li.innerHTML = `
                <span style="color: #334155;"><strong>${index + 1}.</strong> ${meta}</span>
                <button type="button" onclick="eliminarMeta(${index})" style="color: #ef4444; background: none; border: none; cursor: pointer; font-weight: bold; padding: 5px 10px;">Eliminar</button>
            `;
            lista.appendChild(li);
        });
    }
</script>
@endsection