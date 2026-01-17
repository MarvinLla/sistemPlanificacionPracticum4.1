@extends('layouts.app')
@section('title','Crear')

@section('content')

    @if($errors->any())
        <div>
            <ul>
                @foreach($errors->all() as $erros)

                <li> - {{errors}} </li>
                @endforeach

            </ul>
        </div>
    @endif

        {{--formulario para la creacion de entidades--}}
    <form action="{{route ('entidades.store')}}" method=POST class="space-y-4">
        @csrf  
        <div>
            <label class="block">ID</label>
            <input type="number" name="id" require value="{{old('id')}}">
        </div>
        <div>
            <label class="block">NOMBRE</label>
            <input type="text" name="nombre" require value="{{old('nombre')}}">
        </div>
        <div>
            <label class="block">TIPO</label>
            <input type="text" name="tipo" require value="{{old('tipo')}}">
        </div>
        <div>
            <label class="block">RESPONSABLE</label>
            <input type="text" name="responsable" require value="{{old('responsable')}}">
        </div>
        <div>
            <label class="block">FECHA CREACION</label>
            <input type="date" name="created_at" require value="{{old('created_at')}}">
        </div>
        <div>
            <label class="block">FECHA DE ACTUALIZACION</label>
            <input type="date" name="update_at" require value="{{old('update_at')}}">
        </div>

        <button type="submit">Guardar</button>

</form>

@endsection