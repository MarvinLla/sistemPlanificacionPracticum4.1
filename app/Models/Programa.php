<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programa extends Model
{
    use HasFactory;

    protected $table = 'programas';

    protected $fillable = [
        'pnd_objetivo_id', 
        'nombrePrograma', 
        'tipoPrograma', 
        'version',
        'responsablePrograma'
    ]; 

    /**
     * El programa pertenece a un Objetivo del Plan Nacional.
     */
    public function objetivoPnd()
    {
        return $this->belongsTo(PndObjetivo::class, 'pnd_objetivo_id');
    }

    /**
     * Un programa agrupa uno o varios proyectos institucionales.
     */
    public function proyectos() {
    return $this->hasMany(Proyecto::class, 'programa_id');
}   
    public function getAvanceTotalAttribute() {
    $proyectos = $this->proyectos;
    
    if ($proyectos->isEmpty()) {
        return 0;
    }

    // Sumamos el porcentaje de cada proyecto usando el método que ya creamos
    $sumaPorcentajes = $proyectos->sum(function($proyecto) {
        return $proyecto->porcentajePresupuesto();
    });

    return round($sumaPorcentajes / $proyectos->count(), 2);
}   
public function calcularAvancePrograma() {
    $proyectos = $this->proyectos; // Relación hasMany con Proyecto
    if ($proyectos->isEmpty()) return 0;

    return $proyectos->avg(function($proyecto) {
        return $proyecto->calcularCumplimientoReal();
    });
}
}