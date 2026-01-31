@extends('layouts.app')

@section('content')
<div style="max-width: 600px; margin: auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
    <h2 style="font-size: 1.4rem; font-weight: bold; color: #1e293b; margin-bottom: 20px;">Configurar Nuevo Objetivo PND</h2>

    <form action="{{ route('pnd.store') }}" method="POST">
        @csrf
        <div style="margin-bottom: 15px;">
            <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Eje Estratégico</label>
            <input type="text" name="eje" placeholder="Ej: Eje Social, Eje Económico..." required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; color: #64748b; font-weight: 600; margin-bottom: 5px;">Nombre del Objetivo Nacional</label>
            <textarea name="nombre_objetivo" rows="3" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;" placeholder="Escriba el objetivo tal cual aparece en el Plan Nacional..."></textarea>
        </div>

        <div style="background: #f8fafc; padding: 20px; border-radius: 12px; border: 1px dashed #cbd5e1; margin-bottom: 20px;">
            <h4 style="margin-top: 0; color: #1e40af;">📋 Gestión de Políticas Públicas</h4>
            
            <div style="display: flex; gap: 10px; margin-bottom: 15px;">
                <input type="text" id="nuevaPoliticaInput" placeholder="Describa la política pública aquí..." 
                       style="flex-grow: 1; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
                <button type="button" onclick="agregarPoliticaALista()" 
                        style="padding: 10px 20px; background: #2563eb; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold;">
                    + Vincular Política
                </button>
            </div>

            {{-- Lista visual de políticas --}}
            <ul id="listaPoliticasVisual" style="list-style: none; padding: 0; margin: 0;">
                @if(isset($objetivo) && $objetivo->politicas->count() > 0)
                    @foreach($objetivo->politicas as $pol)
                        <li style="background: white; border: 1px solid #e2e8f0; padding: 8px 12px; border-radius: 6px; margin-bottom: 8px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                            <span class="politica-texto">{{ $pol->nombre }}</span>
                            <button type="button" onclick="eliminarPolitica(this)" style="color: #ef4444; background: none; border: none; cursor: pointer; font-weight: bold;">✕</button>
                        </li>
                    @endforeach
                @else
                    <p id="sinPoliticas" style="color: #94a3b8; font-style: italic;">No hay políticas vinculadas aún.</p>
                @endif
            </ul>

            {{-- Campo oculto que enviará los datos al controlador --}}
            <textarea name="politicas_texto" id="politicasHidden" style="display: none;">{{ isset($objetivo) ? $objetivo->politicas->pluck('nombre')->implode("\n") : '' }}</textarea>
        </div>

        <div style="display: flex; gap: 10px; margin-top: 20px;">
            <button type="submit" style="flex: 2; background: #059669; color: white; padding: 12px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">Guardar Objetivo</button>
            <a href="{{ route('pnd.index') }}" style="flex: 1; text-align: center; background: #f1f5f9; color: #475569; padding: 12px; border-radius: 8px; text-decoration: none;">Cancelar</a>
        </div>
    </form>
</div>

{{-- SCRIPT PARA LA LÓGICA DINÁMICA --}}
<script>
function agregarPoliticaALista() {
    const input = document.getElementById('nuevaPoliticaInput');
    const lista = document.getElementById('listaPoliticasVisual');
    const hidden = document.getElementById('politicasHidden');
    const sinMsg = document.getElementById('sinPoliticas');
    const valor = input.value.trim();

    if (valor === "") return;

    if (sinMsg) sinMsg.remove();

    const li = document.createElement('li');
    li.style = "background: white; border: 1px solid #e2e8f0; padding: 8px 12px; border-radius: 6px; margin-bottom: 8px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 1px 2px rgba(0,0,0,0.05);";
    li.innerHTML = `
        <span class="politica-texto">${valor}</span>
        <button type="button" onclick="eliminarPolitica(this)" style="color: #ef4444; background: none; border: none; cursor: pointer; font-weight: bold;">✕</button>
    `;

    lista.appendChild(li);
    input.value = ""; 
    actualizarHidden(); 
}

function eliminarPolitica(btn) {
    btn.parentElement.remove();
    actualizarHidden();
}

function actualizarHidden() {
    const textos = Array.from(document.querySelectorAll('.politica-texto'))
                        .map(el => el.innerText.trim());
    document.getElementById('politicasHidden').value = textos.join("\n");
}
</script>
@endsection