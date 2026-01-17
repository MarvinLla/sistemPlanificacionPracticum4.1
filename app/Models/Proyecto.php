<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'entidad_id',
        'descripcion', // Asegúrate de tenerlo aquí si lo usas en el formulario
        'objetivos',
        'objetivos_ods',
        'presupuesto_estimado',
        'responsable',
        'beneficio',
        'fecha_inicio',
        'fecha_final',
        'correo_contacto',
        'telefono_contacto',
        'estado'
    ];

    /**
     * Relación: Un Proyecto pertenece a una Entidad.
     */
    public function entidad()
    {
        return $this->belongsTo(Entidad::class, 'entidad_id');
    }

    /**
     * Relación: Un Proyecto tiene muchos Avances.
     */
    public function avances()
    {
        return $this->hasMany(Avance::class);
    }

    /**
     * Cálculo dinámico del presupuesto disponible.
     * Resta la suma de 'monto_gastado' en avances al 'presupuesto_estimado'.
     */
    public function presupuestoRestante()
    {
        // Sumamos el gasto de todos los avances relacionados
        $gastado = $this->avances()->sum('monto_gastado');
        
        // Retornamos la diferencia
        return $this->presupuesto_estimado - $gastado;
    }
    public function getDiasRestantesAttribute()
{
    $fechaFin = \Carbon\Carbon::parse($this->fecha_final);
    $dias = \Carbon\Carbon::now()->diffInDays($fechaFin, false);
    
    // Si la fecha ya pasó, devolvemos 0, si no, el número redondeado
    return $dias > 0 ? floor($dias) : 0;
}
}