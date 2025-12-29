<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EntidadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //mostrar todas las entidades
        $entidades = Entidad::all();
        return view('entidades.index', compact('entidades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //que permita llamar al formulario para crear entidades
        return view('entidades.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //sirve para almacenar entidades que primero han sido validadas
        //1.-valido peticiones
        $request->validate([
            'id'=>'required|Unique:entidades,id',
            'nombre'=>'required|string',
            'tipo'=>'required|string',
            'responsable'=>'requerid|string'
        ]);

        //2.-creo la entidad en la base de datos
        Entidad::create($request->all());
        return redirect()->route('entidades.index')->with('success', "Entidad creada");
    }   

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //llama al formulario para editar la informacion
        $entidades = Entidad::findOrFail($id);
        return view('entidades.edit', compact('entidad'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'id'=>'required|Unique:entidades,id',
            'nombre'=>'required|string',
            'tipo'=>'required|string',
            'responsable'=>'requerid|string'
        ]);

        //2.-creo la entidad en la base de datos
        $entidades = Entidad::findOrFail($id);
        $entidades = update($request->all());

        return redirect()->route('entidades.index')->with('success', "Entidad actualizada");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //Elimina un registro de la entidad
        $entidades = Entidad::findOrFail($id);
        $entidades->delete();
        return redirect()->route('entidades.index')->with('success', "Entidad Eliminada");
    }
}
