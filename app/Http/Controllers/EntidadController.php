<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entidad;
// Importaciones necesarias para la seguridad en Laravel 11
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class EntidadController extends Controller implements HasMiddleware
{
    /**
     * Definimos los permisos para este controlador
     */
    public static function middleware(): array
    {
        return [
            // Ver listado y detalles
            new Middleware('can:ver entidades', only: ['index', 'show']),
            
            // Crear nuevas entidades
            new Middleware('can:crear entidades', only: ['create', 'store']),

            // Editar entidades existentes
            new Middleware('can:editar entidades', only: ['edit', 'update']),

            // Eliminar entidades
            new Middleware('can:eliminar entidades', only: ['destroy']),
        ];
    }

    public function index()
    {
        $entidades = Entidad::all();
        return view('entidades.index', compact('entidades'));
    }

    public function create()
    {
        return view('entidades.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|unique:entidades,id',
            'nombre' => 'required|string',
            'tipo' => 'required|string',
            'responsable' => 'required|string'
        ]);

        Entidad::create($request->all());

        return redirect()->route('entidades.index')->with('success', 'Entidad creada correctamente.');
    }

    public function edit(string $id)
    {
        $entidad = Entidad::findOrFail($id);
        return view('entidades.edit', compact('entidad'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'id' => 'required|unique:entidades,id,' . $id,
            'nombre' => 'required|string',
            'tipo' => 'required|string',
            'responsable' => 'required|string'
        ]);

        $entidad = Entidad::findOrFail($id);
        $entidad->update($request->all());

        return redirect()->route('entidades.index')->with('success', 'Entidad actualizada.');
    }

    public function destroy(string $id)
    {
        $entidad = Entidad::findOrFail($id);
        $entidad->delete();

        return redirect()->route('entidades.index')->with('success', 'Entidad eliminada.');
    }
}