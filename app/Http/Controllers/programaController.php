<?php

namespace App\Http\Controllers;

use App\Models\Programa;
use Illuminate\Http\Request;
// Importaciones para seguridad
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ProgramaController extends Controller implements HasMiddleware
{
    /**
     * Configuración de permisos para ProgramaController
     */
    public static function middleware(): array
    {
        return [
            // Permiso para ver la lista
            new Middleware('can:ver programas', only: ['index']),
            // Permiso para crear, editar y eliminar
            new Middleware('can:crear programas', except: ['index']),
        ];
    }

    public function index()
    {
        $programas = Programa::all();
        return view('programas.index', compact('programas'));
    }

    public function create()
    {
        return view('programas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|unique:programas,id', // Corregido: antes decía entidades
            'nombrePrograma' => 'required|string',
            'tipoPrograma' => 'required|string',
            'version' => 'required|string',
            'responsablePrograma' => 'required|string'
        ]);

        Programa::create($request->all());

        return redirect()->route('programas.index')->with('success', 'Programa creado correctamente.');
    }

    public function edit(string $id)
    {
        $programa = Programa::findOrFail($id);
        // Corregido: compact('programa') en singular para que coincida con la variable
        return view('programas.edit', compact('programa'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'id' => 'required|unique:programas,id,' . $id, // Corregido: antes decía entidades
            'nombrePrograma' => 'required|string',
            'tipoPrograma' => 'required|string',
            'version' => 'required|string',
            'responsablePrograma' => 'required|string'
        ]);

        $programa = Programa::findOrFail($id);
        $programa->update($request->all());

        return redirect()->route('programas.index')->with('success', 'Programa actualizado correctamente.');
    }

    public function destroy(string $id)
    {
        $programa = Programa::findOrFail($id);
        $programa->delete();

        return redirect()->route('programas.index')->with('success', 'Programa eliminado.');
    }
}