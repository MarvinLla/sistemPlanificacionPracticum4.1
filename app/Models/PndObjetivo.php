<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PndObjetivo extends Model
{
    use HasFactory;

    protected $table = 'pnd_objetivos';
    protected $fillable = ['eje', 'nombre_objetivo', 'descripcion'];

    // Relación que pide el controlador
    public function politicas() 
    {
        return $this->hasMany(Politica::class, 'pnd_objetivo_id');
    }

    public function programas()
    {
        return $this->hasMany(Programa::class, 'pnd_objetivo_id');
    }

    public function proyectos()
    {
        return $this->hasManyThrough(
            Proyecto::class,
            Programa::class,
            'pnd_objetivo_id', 
            'programa_id',     
            'id',              
            'id'               
        );
    }

    /**
     * LÓGICA DINÁMICA ACTUALIZADA: 
     * El cumplimiento nacional ahora es el promedio de los avances 
     * reales de cada proyecto vinculado.
     */
    public function calcularCumplimiento() 
    {
        // Obtenemos los proyectos vinculados a través de los programas
        $proyectos = $this->proyectos;

        if ($proyectos->isEmpty()) {
            return 0;
        }

        // Promediamos el porcentaje de presupuesto/avance de cada proyecto
        // Esto utiliza la función dinámica que acabamos de arreglar en Proyecto.php
        $promedio = $proyectos->avg(function($proyecto) {
            return $proyecto->porcentajePresupuesto();
        });

        return round($promedio ?? 0, 2);
    }

    public function getEstadoAtributo()
    {
        $porcentaje = $this->calcularCumplimiento();
        if ($porcentaje >= 100) return 'Cumplido';
        if ($porcentaje > 0) return 'En Ejecución';
        return 'Pendiente';
    }
}