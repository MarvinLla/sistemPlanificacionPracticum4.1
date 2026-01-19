<?php

namespace App\Http\Controllers;

use App\Models\PndObjetivo;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PndObjetivoController extends Controller
{
    // Listado de objetivos del PND
    public function index()
    {
        $objetivos = PndObjetivo::all();
        return view('pnd.index', compact('objetivos'));
    }

    // Formulario de creación
    public function create()
    {
        return view('pnd.create');
    }

    // Guardar en la base de datos
    public function store(Request $request)
{
    // 1. Validamos los datos
    $data = $request->validate([
        'eje' => 'required|string|max:255',
        'nombre_objetivo' => 'required|string',
        'descripcion' => 'nullable|string',
    ]);

    // 2. Creamos el registro usando solo los datos validados
    // Esto ignora automáticamente el _token
    PndObjetivo::create($data);

    return redirect()->route('pnd.index')->with('success', 'Objetivo del PND creado correctamente.');
}

    // Formulario de edición
    public function edit($id)
    {
        $objetivo = PndObjetivo::findOrFail($id);
        return view('pnd.edit', compact('objetivo'));
    }

    // Actualizar
    public function update(Request $request, $id)
    {
        $objetivo = PndObjetivo::findOrFail($id);
        $objetivo->update($request->all());

        return redirect()->route('pnd.index')->with('success', 'Objetivo actualizado.');
    }

    // Eliminar
    public function destroy($id)
    {
        PndObjetivo::destroy($id);
        return redirect()->route('pnd.index')->with('success', 'Objetivo eliminado.');
    }
    public function exportarPDF()
{
    $objetivos = \App\Models\PndObjetivo::all();
    
    // Cargar una vista específica para el PDF
    $pdf = Pdf::loadView('pnd.pdf', compact('objetivos'));
    
    // Descargar el archivo
    return $pdf->download('Plan_Nacional_Desarrollo_Ecuador.pdf');
}
}