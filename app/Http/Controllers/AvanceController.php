<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proyecto; 
use App\Models\Avance;   
use App\Models\IndicadorProyecto; // Agregado para validar indicadores
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;

class AvanceController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:ver proyectos', only: ['index']),
            new Middleware('can:crear proyectos', only: ['create', 'store']),
        ];
    }

    public function create()
    {
        // Cargamos indicadores para que el JS del formulario funcione
        $proyectos = Proyecto::with('indicadores')->get();
        return view('avances.create', compact('proyectos'));
    }

    public function store(Request $request)
    {
        // 1. Validar que el indicador pertenezca al proyecto
        $indicador = IndicadorProyecto::findOrFail($request->indicador_proyecto_id);
        
        // Calcular cuánto le queda a este indicador específico
        $gastadoEnIndicador = Avance::where('indicador_proyecto_id', $indicador->id)->sum('monto_gastado');
        $restanteIndicador = $indicador->valor_meta_fijo - $gastadoEnIndicador;

        // 2. Validación estricta
        $request->validate([
            'proyecto_id' => 'required|exists:proyectos,id',
            'indicador_proyecto_id' => 'required|exists:indicador_proyectos,id',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string', 
            'monto_gastado' => 'required|numeric|min:0.01|max:' . $restanteIndicador, 
            'archivo' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx,zip|max:5120', 
            'fecha_avance' => 'required|date',
        ], [
            'monto_gastado.max' => '¡Error! El monto excede el saldo de esta meta específica ($' . number_format($restanteIndicador, 2) . ').'
        ]);

        // 3. Preparar datos
        $data = $request->only([
            'proyecto_id', 
            'indicador_proyecto_id', 
            'titulo', 
            'descripcion', 
            'monto_gastado', 
            'fecha_avance'
        ]);

        // 4. Manejo de Archivo (Evidencia)
        if ($request->hasFile('archivo')) {
            // Guardamos el archivo y obtenemos la ruta
            $rutaArchivo = $request->file('archivo')->store('avances/documentos', 'public');
            $data['archivo_ruta'] = $rutaArchivo; // Asegúrate que tu migración tenga este nombre o cámbialo
        }

        // 5. Crear el registro
        Avance::create($data);

        // 6. Redirigir al INDEX de avances para ver los cambios aplicados
        return redirect()->route('avances.index')
            ->with('success', '¡Avance registrado! La meta se ha actualizado correctamente.');
    }

    public function index()
    {
        // Cargamos relaciones para evitar consultas lentas (Eager Loading)
        $proyectos = Proyecto::with(['avances', 'indicadores.avances', 'entidad'])->get();
        return view('avances.index', compact('proyectos'));
    }

    public function kardex()
    {
        $movimientos = Avance::with(['proyecto', 'indicador']) // 'indicador' es la relación en el modelo Avance
            ->orderBy('fecha_avance', 'desc') 
            ->get();

        return view('avances.kardex', compact('movimientos'));
    }
}