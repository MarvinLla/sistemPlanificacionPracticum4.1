<?php

namespace App\Http\Controllers;

use App\Models\PndObjetivo;
use App\Models\Politica; 
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PndObjetivoController extends Controller
{
    public function index()
    {
        $objetivos = PndObjetivo::with('politicas')->get();
        return view('pnd.index', compact('objetivos'));
    }

    public function alineacion()
    {
        /**
         * EXPLICACIÓN:
         * Cargamos los objetivos y sus proyectos relacionados.
         * De cada proyecto traemos su entidad, indicadores y avances para calcular el progreso real.
         */
        $objetivos = PndObjetivo::with([
            'politicas', 
            'proyectos' => function($query) {
                $query->with(['entidad', 'indicadores', 'avances']);
            }
        ])->get();

        return view('alineacionPND.index', compact('objetivos'));
    }

    public function create()
    {
        return view('pnd.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'eje' => 'required|string|max:255',
            'nombre_objetivo' => 'required|string',
            'descripcion' => 'nullable|string',
            'politicas_texto' => 'nullable|string',
        ]);

        $objetivo = PndObjetivo::create($request->only(['eje', 'nombre_objetivo', 'descripcion']));

        if ($request->filled('politicas_texto')) {
            $lineas = explode("\n", str_replace("\r", "", $request->politicas_texto));
            
            foreach ($lineas as $linea) {
                $nombrePolitica = trim($linea);
                if ($nombrePolitica !== '') {
                    $objetivo->politicas()->create([
                        'nombre' => $nombrePolitica,
                    ]);
                }
            }
        }

        return redirect()->route('pnd.index')->with('success', 'Objetivo del PND y sus políticas creados correctamente.');
    }

    public function edit($id)
    {
        $objetivo = PndObjetivo::with('politicas')->findOrFail($id);
        return view('pnd.edit', compact('objetivo'));
    }

    public function update(Request $request, $id)
    {
        $objetivo = PndObjetivo::findOrFail($id);

        $request->validate([
            'eje' => 'required|string|max:255',
            'nombre_objetivo' => 'required|string',
            'descripcion' => 'nullable|string',
            'politicas_texto' => 'nullable|string',
        ]);

        $objetivo->update($request->only(['eje', 'nombre_objetivo', 'descripcion']));

        if ($request->has('politicas_texto')) {
            $objetivo->politicas()->delete(); 

            $lineas = explode("\n", str_replace("\r", "", $request->politicas_texto));
            foreach ($lineas as $linea) {
                $nombrePolitica = trim($linea);
                if ($nombrePolitica !== '') {
                    $objetivo->politicas()->create([
                        'nombre' => $nombrePolitica,
                    ]);
                }
            }
        }

        return redirect()->route('pnd.index')->with('success', 'Objetivo y políticas actualizados correctamente.');
    }

    public function destroy($id)
    {
        $objetivo = PndObjetivo::findOrFail($id);
        $objetivo->politicas()->delete();
        $objetivo->delete();

        return redirect()->route('pnd.index')->with('success', 'Objetivo y sus políticas eliminados.');
    }

    public function exportarPDF()
    {
        $objetivos = PndObjetivo::with('politicas')->get();
        $pdf = Pdf::loadView('pnd.pdf', compact('objetivos'));
        return $pdf->download('Plan_Nacional_Desarrollo_Ecuador.pdf');
    }
}