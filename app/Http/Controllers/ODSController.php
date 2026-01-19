<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ODS; // Usamos solo este, que es el nombre de tu archivo ODS.php

class ODSController extends Controller
{
    public function index()
    {
        
        $objetivosODS = ODS::all(); 
        return view('ODS.index', compact('objetivosODS'));
    }   

    public function create()
    {
        return view('ODS.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            // Cambiado a la tabla correcta 'objetivosODS' que pusiste en tu migración
            'nombreObjetivo'=> 'required|string',
            'descripcion' => 'required|string',
            'metasAsociadas' => 'required|string'
        ]);

        // Usamos ODS en lugar de ObjetivoODS
        ODS::create($request->all());

        return redirect()->route('ODS.index')->with('success', 'Objetivo ODS creado correctamente.');
    }

    public function edit(string $id)
    {
        // Usamos ODS
        $objetivoODS = ODS::findOrFail($id);
        return view('ODS.edit', compact('objetivoODS'));
    }

    public function update(Request $request, string $id)
    {
        $objetivoODS = ODS::findOrFail($id);

        $request->validate([
            'nombreObjetivo' => 'required|string',
        'descripcion' => 'required|string',
        'metasAsociadas' => 'required|string'
        ]);
        
        $objetivoODS->update($request->all());

        return redirect()->route('ODS.index')->with('success', 'Objetivo ODS y metas actualizado.');
    }

    public function destroy(string $id)
    {
        $objetivoODS = ODS::findOrFail($id);
        $objetivoODS->delete();

        return redirect()->route('ODS.index')->with('success', 'Objetivo eliminado.');
    }
}