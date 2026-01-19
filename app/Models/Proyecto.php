<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Proyecto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'entidad_id',
        'programa_id',   
        'descripcion',
        'objetivos',
        'presupuesto_estimado',
        'responsable',
        'beneficio',
        'fecha_inicio',
        'fecha_final',
        'correo_contacto',
        'telefono_contacto',
        'estado',
        'metas_finales',
        'pnd_objetivo_id',
        'justificacion_pnd',  
    ];

    // ELIMINAMOS EL CAST DE 'ods' => 'array' 
    // Solo dejamos casts para fechas si fuera necesario, o lo dejamos vacío.
    protected $casts = [];

    /**
     * Relación Many-to-Many con ODS
     * Verifica que en tu DB la columna sea 'objetivo_ods_id' o 'ods_id'
     */
    public function ods()
    {
        return $this->belongsToMany(ODS::class, 'objetivo_ods_proyecto', 'proyecto_id', 'objetivo_ods_id');
    }

    public function programa()
    {
        return $this->belongsTo(Programa::class, 'programa_id');
    }

    public function entidad()
    {
        return $this->belongsTo(Entidad::class, 'entidad_id');
    }

    public function avances()
    {
        return $this->hasMany(Avance::class, 'proyecto_id');
    }

    public function presupuestoRestante()
    {
        $gastado = $this->avances()->sum('monto_gastado');
        return $this->presupuesto_estimado - $gastado;
    }

    public function getDiasRestantesAttribute()
    {
        $fechaFin = Carbon::parse($this->fecha_final);
        $dias = Carbon::now()->diffInDays($fechaFin, false);
        return $dias > 0 ? floor($dias) : 0;
    }
    public function pndObjetivo()
{
    // Verifica que la llave foránea en tu tabla proyectos sea 'pnd_objetivo_id'
    return $this->belongsTo(PndObjetivo::class, 'pnd_objetivo_id');
}

public function presupuestoConsumido()
{
    // Cambiamos 'monto' por 'monto_gastado' que es el nombre real en tu tabla
    return $this->avances()->sum('monto_gastado');
}

    public function porcentajePresupuesto()
{
    if ($this->presupuesto_estimado > 0) {
        $porcentaje = ($this->presupuestoConsumido() / $this->presupuesto_estimado) * 100;
        return round($porcentaje, 1);
    }
    return 0;
}


}