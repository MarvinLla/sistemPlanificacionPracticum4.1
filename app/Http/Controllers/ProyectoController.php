<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\Entidad;
use App\Models\Programa;
use App\Models\ODS;
use App\Models\PndObjetivo;
use App\Models\IndicadorProyecto;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ProyectoController extends Controller implements HasMiddleware
{
    /**
     * Configuración de Middleware (Permisos Spatie)
     */
    public static function middleware(): array
    {
        return [
            new Middleware('can:ver proyectos', only: ['index', 'show']),
            new Middleware('can:crear proyectos', only: ['create', 'store']),
            new Middleware('can:editar proyectos', only: ['edit', 'update']),
            new Middleware('can:eliminar proyectos', only: ['destroy']),
            new Middleware('can:ver alertas', only: ['alertas']),
        ];
    }

    public function index()
    {
        $proyectos = Proyecto::with(['entidad', 'pndObjetivo'])->get();
        return view('proyectos.index', compact('proyectos'));
    }

    public function create()
    {
        $entidades = Entidad::all();
        $programas = Programa::all();
        $objetivosODS = ODS::all();
        $objetivosPND = PndObjetivo::all();
        return view('proyectos.create', compact('entidades', 'programas', 'objetivosODS', 'objetivosPND'));
    }

    public function store(Request $request)
    {
        $validado = $request->validate([
            'nombre' => 'required|string|max:255',
            'entidad_id' => 'required|exists:entidades,id',
            'programa_id' => 'required|exists:programas,id',
            'pnd_objetivo_id' => 'required|exists:pnd_objetivos,id',
            'descripcion' => 'required|string',
            'objetivos' => 'required|string',
            'presupuesto_estimado' => 'required|numeric|min:0',
            'estado' => 'required|string',
            'responsable' => 'required|string|max:255',
            'beneficio' => 'required|string',
            'fecha_inicio' => 'required|date',
            'fecha_final' => 'required|date|after_or_equal:fecha_inicio',
            'correo_contacto' => 'required|email',
            'telefono_contacto' => 'required|string',
            'ods_id' => 'nullable|exists:objetivos_ods,id', 
            'justificacion_pnd' => 'nullable|string',
            'ubicacion_provincia' => 'nullable|string',
            'ubicacion_canton' => 'nullable|string',
            'ubicacion_parroquia' => 'nullable|string',
            'beneficiarios_directos' => 'nullable|integer',
        ]);

        if($request->has('metas_ods_array')) {
            $validado['metas_finales'] = implode(" || ", $request->metas_ods_array);
        }

        $proyecto = Proyecto::create($validado);

        if ($request->filled('ods_id')) {
            $proyecto->ods()->sync([$request->ods_id]);
        }

        // Procesar Indicadores
        if ($request->has('indicadores')) {
            foreach ($request->indicadores as $datos) {
                $proyecto->indicadores()->create([
                    'meta_ods_texto'   => $datos['meta_texto'] ?? '',
                    'nombre_indicador' => $datos['nombre'],
                    'descripcion'      => $datos['descripcion'] ?? '',
                    'valor_meta_fijo'  => $datos['valor_fijo'] ?? 0,
                ]);
            }
        }

        return redirect()->route('proyectos.index')->with('success', 'Proyecto registrado con éxito');
    }

    public function edit($id)
    {
        $proyecto = Proyecto::with(['ods', 'pndObjetivo', 'indicadores'])->findOrFail($id);
        $entidades = Entidad::all();
        $programas = Programa::all();
        $objetivosODS = ODS::all();
        $objetivosPND = PndObjetivo::all(); 

        $politicasDisponibles = $proyecto->pndObjetivo ? $proyecto->pndObjetivo->politicas : collect();

        return view('proyectos.edit', compact('proyecto', 'entidades', 'programas', 'objetivosODS', 'objetivosPND', 'politicasDisponibles'));
    }

    public function update(Request $request, $id)
    {
        $proyecto = Proyecto::findOrFail($id);

        $validado = $request->validate([
            'nombre' => 'required|string|max:255',
            'entidad_id' => 'required|exists:entidades,id',
            'programa_id' => 'required|exists:programas,id',
            'pnd_objetivo_id' => 'required|exists:pnd_objetivos,id',
            'presupuesto_estimado' => 'required|numeric|min:0',
            'fecha_inicio' => 'required|date',
            'fecha_final' => 'required|date|after_or_equal:fecha_inicio',
            'estado' => 'required|string',
        ]);

        if ($request->has('metas_ods_array')) {
            $proyecto->metas_finales = implode(" || ", $request->metas_ods_array);
        }

        $proyecto->update($request->all());

        if ($request->filled('ods_id')) {
            $proyecto->ods()->sync([$request->ods_id]);
        }

        // Update dinámico de indicadores
        if ($request->has('indicadores')) {
            $idsRecibidos = [];
            foreach ($request->indicadores as $key => $datos) {
                if (strpos($key, 'new_') === 0) {
                    $nuevo = $proyecto->indicadores()->create([
                        'meta_ods_texto'   => $datos['meta_texto'],
                        'nombre_indicador' => $datos['nombre'],
                        'valor_meta_fijo'  => $datos['valor_fijo'],
                    ]);
                    $idsRecibidos[] = $nuevo->id;
                } else {
                    $existente = IndicadorProyecto::find($key);
                    if ($existente) {
                        $existente->update([
                            'nombre_indicador' => $datos['nombre'],
                            'valor_meta_fijo'  => $datos['valor_fijo'],
                        ]);
                        $idsRecibidos[] = $existente->id;
                    }
                }
            }
            $proyecto->indicadores()->whereNotIn('id', $idsRecibidos)->delete();
        }

        return redirect()->route('proyectos.index')->with('success', 'Proyecto actualizado correctamente.');
    }

    public function show($id)
    {
        $proyecto = Proyecto::with(['ods', 'programa', 'entidad', 'pndObjetivo', 'indicadores'])->findOrFail($id);
        return view('proyectos.show', compact('proyecto'));
    }

    public function destroy($id)
    {
        Proyecto::findOrFail($id)->delete();
        return redirect()->route('proyectos.index')->with('success', 'Proyecto eliminado.');
    }

    public function alertas()
    {
        $hoy = Carbon::now();
        $proximosAVencer = $hoy->copy()->addDays(15);

        $proyectos = Proyecto::with('avances')->get()->filter(function($proyecto) use ($hoy, $proximosAVencer) {
            $presupuestoUsado = $proyecto->avances->sum('monto_gastado');
            $porcentajeUso = ($proyecto->presupuesto_estimado > 0) ? ($presupuestoUsado / $proyecto->presupuesto_estimado) * 100 : 0;
            $fechaFinal = Carbon::parse($proyecto->fecha_final);

            return $porcentajeUso >= 90 || $fechaFinal <= $proximosAVencer;
        });

        return view('proyectos.alertas', compact('proyectos'));
    }

    public function exportarFichaProyecto($id)
    {
        $proyecto = Proyecto::with(['entidad', 'programa', 'ods', 'pndObjetivo', 'indicadores'])->findOrFail($id);
        $pdf = Pdf::loadView('proyectos.pdf_ficha', compact('proyecto'));
        return $pdf->download('Ficha_Proyecto_' . $proyecto->id . '.pdf');
    }
}