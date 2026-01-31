<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Avance extends Model
{
    use HasFactory;

    protected $table = 'avances';

    protected $fillable = [
        'proyecto_id', 
        'indicador_proyecto_id', // Relación clave para evitar datos de prueba
        'titulo', 
        'descripcion', 
        'monto_gastado', 
        'archivo', 
        'foto', 
        'fecha_avance'
    ];

    /**
     * Un avance pertenece a un solo proyecto.
     */
    public function proyecto() 
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }

    /**
     * Un avance pertenece a un indicador específico.
     * Esta relación es la que permite que PndObjetivo sume solo los 
     * montos que corresponden al indicador seleccionado en el formulario.
     */
    public function indicador()
    {
        return $this->belongsTo(IndicadorProyecto::class, 'indicador_proyecto_id');
    }
}