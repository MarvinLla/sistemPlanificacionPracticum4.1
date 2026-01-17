<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EntidadController;
use App\Http\Controllers\ProgramaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\AvanceController; // 1. IMPORTAR EL NUEVO CONTROLADOR
use App\Models\Entidad;
use App\Models\Proyecto;

// Redirección inicial
Route::get('/', function () {
    return redirect()->route('inicio');
})->middleware('auth');

// TODA LA APLICACIÓN PROTEGIDA POR LOGIN
Route::middleware(['auth'])->group(function () {

    // DASHBOARD PRINCIPAL
    Route::get('/inicio', function () {
        $totalEntidades = Entidad::count();
        $proyectosRevision = Proyecto::where('estado', 'en revisión')->count();
        $proyectosAprobados = Proyecto::where('estado', 'aprobado')->count();
        return view('inicio', compact('totalEntidades', 'proyectosRevision', 'proyectosAprobados'));
    })->name('inicio');

    // PERFIL DE USUARIO
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // GESTIÓN DE USUARIOS
    Route::middleware(['can:gestionar usuarios'])->group(function () {
        Route::resource('usuarios', UserController::class);
    });

    // ENTIDADES
    Route::middleware(['can:ver entidades'])->group(function () {
        Route::resource('entidades', EntidadController::class);
    });

    // PROGRAMAS
    Route::middleware(['can:ver programas'])->group(function () {
        Route::resource('programas', ProgramaController::class);
    });

    // PROYECTOS Y AVANCES
    Route::middleware(['can:ver proyectos'])->group(function () {
        // Rutas de Proyectos
        Route::resource('proyectos', ProyectoController::class);
        
        // Ruta para aprobar (Cambiado a permiso 'cambiar estados' para consistencia)
        Route::patch('/proyectos/{proyecto}/aprobar', [ProyectoController::class, 'aprobar'])
            ->name('proyectos.aprobar')
            ->middleware('can:cambiar estados');

        // 2. RUTAS DE AVANCES (Dentro de ver proyectos)
        Route::resource('avances', AvanceController::class);
    });
    Route::get('/kardex', [App\Http\Controllers\AvanceController::class, 'kardex'])->name('kardex.index');
    Route::get('/alertas', [App\Http\Controllers\ProyectoController::class, 'alertas'])->name('alertas.index');
});

require __DIR__.'/auth.php';