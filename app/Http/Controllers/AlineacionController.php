<?php

namespace App\Http\Controllers;

use App\Models\PndObjetivo;
use Illuminate\Http\Request;

class AlineacionController extends Controller
{
    public function index()
{
    $objetivos = PndObjetivo::with(['politicas', 'proyectos.programa', 'proyectos.ods'])->get();
    return view('alineacionPND.index', compact('objetivos'));
}
}