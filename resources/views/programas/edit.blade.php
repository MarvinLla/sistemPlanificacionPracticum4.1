@extends('layouts.app')
@section('title','Editar')

@section('content')
    <h2 class="text-2x1 font-bold mb-4">Editar los programas</h2>
    {{--formulario para la creacion de entidades--}}

        <form action="{{route('programas.update', $programa->id)}}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block">Nombre</label>
                <input type="text" name="nombrePrograma" require value="{{old('nombrePrograma', $programa->nombrePrograma)}}">
            </div> 
            <div>
                <label class="block">Tipo</label>
                <input type="text" name="tipoPrograma" require value="{{old('tipoPrograma', $programa->tipoPrograma)}}">
            </div>
            <div>
                <label class="block">Version</label>
                <input type="text" name="version" require value="{{old('version', $programa->version)}}">
            </div>
             <div>
                <label class="block">Responsable</label>
                <input type="text" name="responsablePrograma" require value="{{old('responsablePrograma', $programa->responsablePrograma)}}">
            </div>
            
            <button type="submit">Actualizar</button>

            <a href="{{route('programas.index')}}">Volver</a>

</form>
@endsection