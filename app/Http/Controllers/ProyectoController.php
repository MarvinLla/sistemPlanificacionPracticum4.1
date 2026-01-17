<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ProyectoController extends Controller implements HasMiddleware
{
    /**
     * Definimos los permisos para el controlador
     */
    public static function middleware(): array
    {
        return [
            // Ver
        new Middleware('can:ver proyectos', only: ['index', 'show']),
        
        // Crear
        new Middleware('can:crear proyectos', only: ['create', 'store']),
        
        // Editar (Esto permitirá al admin editar aunque no tenga activado 'crear')
        new Middleware('can:editar proyectos', only: ['edit', 'update']),
        
        // Borrar
        new Middleware('can:eliminar proyectos', only: ['destroy']),

        ];
    }

    public function index()
    {
        $proyectos = Proyecto::all();
        return view('proyectos.index', compact('proyectos'));
    }

    public function create()
    {
        // 1. Obtenemos todas las entidades de la base de datos
    $entidades = \App\Models\Entidad::all();

    // 2. Pasamos la variable a la vista 'proyectos.create'
    return view('proyectos.create', compact('entidades'));
    }

    public function store(Request $request)
{
    $request->validate([
        'nombre' => 'required|string|max:255',
        'entidad_id' => 'required|exists:entidades,id',
        'descripcion' => 'required|string', 
        'estado' => 'required|string',      
        // ... los demás campos
    ]);

    Proyecto::create($request->all());

    return redirect()->route('proyectos.index')->with('success', 'Proyecto guardado');
}

    public function edit(string $id)
    {
        $proyecto = Proyecto::findOrFail($id);
        $entidades = \App\Models\Entidad::all();
        return view('proyectos.edit', compact('proyecto', 'entidades'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'fecha_inicio' => 'required|date',
            'estado' => 'required|string'
        ]);

        $proyecto = Proyecto::findOrFail($id);
        $proyecto->update($request->all());

        return redirect()->route('proyectos.index')->with('success', 'Proyecto actualizado correctamente.');
    }

    public function destroy(string $id)
    {
        $proyecto = Proyecto::findOrFail($id);
        $proyecto->delete();

        return redirect()->route('proyectos.index')->with('success', 'Proyecto eliminado.');
    }
    public function aprobar(string $id)
{
    $proyecto = Proyecto::findOrFail($id);
    $proyecto->update(['estado' => 'aprobado']);

    return redirect()->route('proyectos.index')->with('success', 'El proyecto ha sido aprobado y el estado se ha actualizado a verde.');
}
public function show(string $id)
{
    // Buscamos el proyecto con su entidad para mostrar el nombre de la entidad
    $proyecto = Proyecto::with('entidad')->findOrFail($id);
    
    return view('proyectos.show', compact('proyecto'));
}
public function alertas()
{
    $hoy = \Carbon\Carbon::now();
    $proximosAVencer = $hoy->copy()->addDays(15); // Alerta si vence en menos de 15 días

    $proyectos = Proyecto::all()->filter(function($proyecto) use ($hoy, $proximosAVencer) {
        $presupuestoUsado = $proyecto->avances->sum('monto_gastado');
        $porcentajeUso = ($proyecto->presupuesto > 0) ? ($presupuestoUsado / $proyecto->presupuesto) * 100 : 0;
        $fechaFinal = \Carbon\Carbon::parse($proyecto->fecha_final);

        // CONDICIONES DE ALERTA:
        // 1. Gasto mayor al 90% del presupuesto
        // 2. O la fecha vence en menos de 15 días
        // 3. O la fecha ya venció
        return $porcentajeUso >= 90 || $fechaFinal <= $proximosAVencer;
    });

    return view('proyectos.alertas', compact('proyectos'));
}
}