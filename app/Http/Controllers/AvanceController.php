<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proyecto; // IMPORTANTE
use App\Models\Avance;   // IMPORTANTE
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AvanceController extends Controller implements HasMiddleware
{
    /* public static function middleware(): array
{
    
}
*/
    
    public static function middleware(): array
    {
        return [
            new Middleware('can:ver proyectos', only: ['index']),
            new Middleware('can:crear proyectos', only: ['create', 'store']),
        ];
    }

    /**
     * Muestra el formulario para crear un avance
     */
    public function create()
    {
        // Necesitamos enviar los proyectos para que el usuario elija uno
        $proyectos = Proyecto::all();
        return view('avances.create', compact('proyectos'));
    }

    /**
     * Guarda el avance y valida el presupuesto
     */
    public function store(Request $request)
    {
        $proyecto = Proyecto::findOrFail($request->proyecto_id);
        $restante = $proyecto->presupuestoRestante();

        $request->validate([
            'proyecto_id' => 'required|exists:proyectos,id',
            'titulo' => 'required|string|max:255',
            'descripcion'   => 'required|string', 
            'monto_gastado' => 'required|numeric|min:0|max:' . $restante, 
            'archivo' => 'nullable|file|mimes:pdf,docx,zip|max:5120', // subí a 5MB
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'fecha_avance' => 'required|date',
        ], [
            'monto_gastado.max' => '¡Error! El monto supera el presupuesto disponible ($' . number_format($restante, 2) . ').'
        ]);

        $data = $request->all();

        // Manejo de Archivos (Se guardan en storage/app/public)
        if ($request->hasFile('archivo')) {
            $data['archivo'] = $request->file('archivo')->store('avances/documentos', 'public');
        }
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('avances/fotos', 'public');
        }

        Avance::create($data);

        return redirect()->route('proyectos.index')
            ->with('success', 'Avance guardado. Saldo restante del proyecto: $' . number_format($proyecto->presupuestoRestante(), 2));
    }

    public function index()
{
    
    // Cargamos 'avances' y 'entidad' para que los cálculos de la vista funcionen
    $proyectos = Proyecto::with(['avances', 'entidad'])->get();

    // 2. Pasamos la variable a la vista
    return view('avances.index', compact('proyectos'));
}
public function kardex()
{
    
    $movimientos = \App\Models\Avance::with('proyecto')
        ->orderBy('fecha_avance', 'desc') 
        ->get();

    return view('avances.kardex', compact('movimientos'));
}
}