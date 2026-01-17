@extends('layouts.app')

    @section('title', 'Programas')

    @section('content')

        <h2 class="text-2x1 font-bold mb-4">PROGRAMAS SINCRONIZADOS</h2>

        {{--Validacion mensaje--}}
            @if(session('success'))
                <div>
                    {{session('success')}} 
                </div>
            @endif
            {{--boton para crear nueva entidad--}}
            <a href="{{route('programas.create')}}">+ Nueva programa</a>
        {{--tabla para listar las entidades--}}
    <table style="background-color: #f8f8fa">

        <thead> 
            <tr>
                <th style="border: 1px solid #ccc; padding: 8px">ID</th>
                <th style="border: 1px solid #ccc; padding: 8px">NOMBRE</th>
                <th style="border: 1px solid #ccc; padding: 8px">TIPO</th>
                <th style="border: 1px solid #ccc; padding: 8px">VERSION</th>
                <th style="border: 1px solid #ccc; padding: 8px">RESPONSABLE</th>
            </tr>
</thead>

<tbody>
    @foreach($programas as $programa)
    <tr>
        <td style="border: 1px solid #ccc; padding: 8px">{{$programa->id}}</td>
        <td style="border: 1px solid #ccc; padding: 8px">{{$programa->nombrePrograma}}</td>
        <td style="border: 1px solid #ccc; padding: 8px">{{$programa->tipoPrograma}}</td>
        <td style="border: 1px solid #ccc; padding: 8px">{{$programa->version}}</td>
        <td style="border: 1px solid #ccc; padding: 8px">{{$programa->responsablePrograma}}</td>
        <td style="border: 1px solid #ccc; padding: 8px">
            <a href="{{route('programas.edit', $programa->id)}}">Editar</a>
            <form action="{{ route('programas.destroy', $programa->id)}}" method=POST onsubmit="return">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
            </form>
            </td>
    </tr>   
    @endforeach
</tbody>
</table>
    @endsection