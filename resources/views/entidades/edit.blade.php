@extends('layouts.app')
@section('title','Editar')

@section('content')
    <h2 class="text-2x1 font-bold mb-4">Editar las Entidades</h2>
    {{--formulario para la creacion de entidades--}}

        <form action="{{route('entidades.update', $entidad->id)}}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block">Nombre</label>
                <input type="text" name="nombre" require value="{{old('nombre', $entidad->nombre)}}">
            </div> 
            <div>
                <label class="block">Tipo</label>
                <input type="text" name="tipo" require value="{{old('tipo', $entidad->tipo)}}">
            </div>
             <div>
                <label class="block">Responsable</label>
                <input type="text" name="responsable" require value="{{old('tipo', $entidad->responsable)}}">
            </div>
            
            <button type="submit">Actualizar</button>

            <a href="{{route('entidades.index')}}">Volver</a>

</form>
@endsection

    