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
    <form action="{{route ('programas.store')}}" method=POST class="space-y-4">
        @csrf  
        <div>
            <label class="block">ID</label>
            <input type="number" name="id" require value="{{old('id')}}">
        </div>
        <div>
            <label class="block">NOMBRE</label>
            <input type="text" name="nombrePrograma" require value="{{old('nombrePrograma')}}">
        </div>
        <div>
            <label class="block">TIPO</label>
            <input type="text" name="tipoPrograma" require value="{{old('tipoPrograma')}}">
        </div>
        <div>
            <label class="block">VERSION</label>
            <input type="text" name="version" require value="{{old('version')}}">
        </div>
        <div>
            <label class="block">RESPONSABLE</label>
            <input type="text" name="responsablePrograma" require value="{{old('responsablePrograma')}}">
        </div>

        <button type="submit">Guardar</button>

</form>

@endsection