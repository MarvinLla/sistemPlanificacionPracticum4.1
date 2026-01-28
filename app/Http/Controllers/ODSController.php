<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ODS;

class ODSController extends Controller
{
    /**
     * Muestra la lista de todos los ODS.
     */
    public function index()
    {
        $objetivosODS = ODS::all();
        return view('ODS.index', compact('objetivosODS'));
    }

    /**
     * Muestra el formulario para crear un nuevo ODS.
     */
    public function create()
    {
        return view('ODS.create');
    }

    /**
     * Guarda un nuevo ODS en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombreObjetivo' => 'required|string|max:255',
            'descripcion'    => 'required|string',
            'metasAsociadas' => 'required|string',
        ]);

        // Guardamos los datos
        ODS::create($request->all());

        return redirect()->route('ODS.index')
            ->with('success', 'Objetivo ODS creado correctamente.');
    }

    /**
     * Muestra el formulario para editar un ODS específico.
     */
    public function edit(string $id)
    {
        $objetivoODS = ODS::findOrFail($id);
        return view('ODS.edit', compact('objetivoODS'));
    }

    /**
     * Actualiza un ODS en la base de datos.
     */
    public function update(Request $request, string $id)
    {
        $objetivoODS = ODS::findOrFail($id);

        $request->validate([
            'nombreObjetivo' => 'required|string|max:255',
            'descripcion'    => 'required|string',
            'metasAsociadas' => 'required|string',
        ]);

        $objetivoODS->update($request->all());

        return redirect()->route('ODS.index')
            ->with('success', 'Objetivo ODS actualizado correctamente.');
    }

    /**
     * Elimina un ODS de la base de datos.
     */
    public function destroy(string $id)
    {
        $objetivoODS = ODS::findOrFail($id);
        $objetivoODS->delete();

        return redirect()->route('ODS.index')
            ->with('success', 'Objetivo eliminado con éxito.');
    }
}