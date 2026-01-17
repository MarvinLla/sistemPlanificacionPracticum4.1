    @extends('layouts.app')

    @section('title', 'Entidades')

    @section('content')

        <h2 class="text-2x1 font-bold mb-4">LISTADO ENTIDADES:</h2>

        {{--Validacion mensaje--}}
            @if(session('success'))
                <div>
                    {{session('success')}} 
                </div>
            @endif
            {{--boton para crear nueva entidad--}}
            <a href="{{route('entidades.create')}}">+ Nueva entidad</a>
        {{--tabla para listar las entidades--}}
    <table style="background-color: #f8f8fa">

        <thead> 
            <tr>
                <th style="border: 1px solid #ccc; padding: 8px">ID</th>
                <th style="border: 1px solid #ccc; padding: 8px">NOMBRE</th>
                <th style="border: 1px solid #ccc; padding: 8px">TIPO</th>
                <th style="border: 1px solid #ccc; padding: 8px">RESPONSABLE</th>
                <th style="border: 1px solid #ccc; padding: 8px">FECHA DE CREACION</th>
                <th style="border: 1px solid #ccc; padding: 8px">FECHA DE ACTUALIZACION</th>
                <th style="border: 1px solid #ccc; padding: 8px">ACCIONES</th>
            </tr>
</thead>

<tbody>
    @foreach($entidades as $entidad)
    <tr>
        <td style="border: 1px solid #ccc; padding: 8px">{{$entidad->id}}</td>
        <td style="border: 1px solid #ccc; padding: 8px">{{$entidad->nombre}}</td>
        <td style="border: 1px solid #ccc; padding: 8px">{{$entidad->tipo}}</td>
        <td style="border: 1px solid #ccc; padding: 8px">{{$entidad->responsable}}</td>
        <td style="border: 1px solid #ccc; padding: 8px">{{$entidad->created_at}}</td>
        <td style="border: 1px solid #ccc; padding: 8px">{{$entidad->update_at}}</td>
        <td style="border: 1px solid #ccc; padding: 8px">
            <a href="{{route('entidades.edit', $entidad->id)}}">Editar</a>
            <form action="{{ route('entidades.destroy', $entidad->id)}}" method=POST onsubmit="return">
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