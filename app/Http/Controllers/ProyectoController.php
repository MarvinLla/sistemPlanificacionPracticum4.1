<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Models\Entidad;
use App\Models\Programa;
use App\Models\ODS;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PndObjetivo;

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
        new Middleware('can:ver alertas', only: ['alertas']),

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
    $programas = \App\Models\Programa::all();
    $ods = \App\Models\ODS::all();
    $objetivosPND = PndObjetivo::all(); 

    return view('proyectos.edit', compact('proyecto', 'entidades', 'programas', 'ods', 'objetivosPND'));
    }

    public function store(Request $request)
{
    // 1. Validamos todos los campos
    $request->validate([
        'nombre' => 'required|string|max:255',
        'entidad_id' => 'required|exists:entidades,id',
        'programa_id' => 'required|exists:programas,id',
        'descripcion' => 'required|string', // Campo obligatorio del proyecto
        'objetivos' => 'required|string',    // Campo obligatorio del proyecto
        'presupuesto_estimado' => 'required|numeric|min:0',
        'estado' => 'required|string',
        'responsable' => 'required|string|max:255',
        'beneficio' => 'required|string',
        'fecha_inicio' => 'required|date',
        'fecha_final' => 'required|date|after_or_equal:fecha_inicio',
        'correo_contacto' => 'required|email',
        'telefono_contacto' => 'required|string',
        'ods' => 'nullable|array', 
        'metas_seleccionadas' => 'nullable|array',
        
    ]);

    // 2. Preparamos los datos del Proyecto (Campos directos de la tabla)
    $data = $request->only([
        'nombre', 'entidad_id', 'programa_id', 'descripcion', 
        'objetivos', 'presupuesto_estimado', 'estado', 'responsable', 
        'beneficio', 'fecha_inicio', 'fecha_final', 'correo_contacto', 
        'telefono_contacto'
    ]);
    
    // 3. Procesamos las metas seleccionadas aparte para guardarlas en su columna
    if($request->has('metas_seleccionadas')) {
        $data['metas_finales'] = implode(" || ", $request->metas_seleccionadas);
    }

    // 4. CREAMOS EL PROYECTO
    $proyecto = Proyecto::create($data);

    // 5. Relacionamos con los ODS (Tabla Intermedia)
    if ($request->has('ods')) {
        $proyecto->ods()->sync($request->ods);
    }

    return redirect()->route('proyectos.index')->with('success', 'Proyecto registrado con éxito');
}

    public function edit($id)
{
    // Usamos 'with' para garantizar que 'ods' sea una colección, aunque esté vacía
    $proyecto = Proyecto::with('ods')->findOrFail($id);
    
    $entidades = Entidad::all();
    $programas = Programa::all();
    $ods = ODS::all();
    
    // Nueva línea necesaria para la sección de Plan Institucional
    $objetivosPND = \App\Models\PndObjetivo::all(); 

    return view('proyectos.edit', compact('proyecto', 'entidades', 'programas', 'ods', 'objetivosPND'));
}

    public function update(Request $request, string $id)
{
    $proyecto = Proyecto::findOrFail($id);

    // 1. Validar todos los campos necesarios
    $request->validate([
        'nombre' => 'required|string|max:255',
        'programa_id' => 'required|exists:programas,id',
        'entidad_id' => 'required|exists:entidades,id',
        'presupuesto_estimado' => 'required|numeric',
        'fecha_inicio' => 'required|date',
        'fecha_final' => 'required|date|after_or_equal:fecha_inicio',
        'pnd_objetivo_id' => 'nullable|exists:pnd_objetivos,id', // Validación añadida
    ]);

    // 2. Asignación manual de datos básicos
    $proyecto->nombre = $request->nombre;
    $proyecto->descripcion = $request->descripcion;
    $proyecto->objetivos = $request->objetivos;
    $proyecto->entidad_id = $request->entidad_id;
    $proyecto->programa_id = $request->programa_id;
    $proyecto->presupuesto_estimado = $request->presupuesto_estimado;
    $proyecto->responsable = $request->responsable;
    $proyecto->beneficio = $request->beneficio;
    $proyecto->fecha_inicio = $request->fecha_inicio;
    $proyecto->fecha_final = $request->fecha_final;
    $proyecto->correo_contacto = $request->correo_contacto;
    $proyecto->telefono_contacto = $request->telefono_contacto;
    $proyecto->estado = $request->estado;

    // --- NUEVOS CAMPOS PND AÑADIDOS CON CUIDADO ---
    $proyecto->pnd_objetivo_id = $request->pnd_objetivo_id;
    $proyecto->justificacion_pnd = $request->justificacion_pnd;
    // ----------------------------------------------

    // 3. Procesar Metas (Convertir array de la vista a string para la BD)
    if($request->has('metas_seleccionadas')) {
        $proyecto->metas_finales = implode(" || ", $request->metas_seleccionadas);
    } else {
        $proyecto->metas_finales = null;
    }

    // 4. GUARDAR CAMBIOS EN TABLA PROYECTOS
    $proyecto->save();

    // 5. ACTUALIZAR RELACIÓN ODS (Tabla Intermedia)
    if ($request->has('ods')) {
        $proyecto->ods()->sync($request->ods);
    } else {
        $proyecto->ods()->detach();
    }

    return redirect()->route('proyectos.index')->with('success', 'Proyecto actualizado correctamente y caché refrescada.');
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
public function show($id)
{
    // Añadimos 'pndObjetivo' a la carga para que la ficha técnica pueda mostrar el Eje y el Objetivo Nacional
    $proyecto = Proyecto::with(['ods', 'programa', 'entidad', 'pndObjetivo'])->findOrFail($id);
    
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

public function exportarFichaProyecto($id)
{
    // Cargamos el proyecto con todas sus relaciones para que no falte info
    $proyecto = Proyecto::with(['entidad', 'programa', 'ods', 'pndObjetivo'])->findOrFail($id);

    $pdf = Pdf::loadView('proyectos.pdf_ficha', compact('proyecto'));
    
    // Nombre del archivo dinámico basado en el nombre del proyecto
    return $pdf->download('Ficha_Proyecto_' . $proyecto->id . '.pdf');
}
}