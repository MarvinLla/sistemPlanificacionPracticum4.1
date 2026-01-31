<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class odsMeta extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla corregido según tu captura de HeidiSQL.
     */
    protected $table = 'objetivos_ods';

    /**
     * Campos ajustados a las columnas reales que se ven en tu tabla.
     */
    protected $fillable = [
        'nombreObjetivo',
        'descripcion',
        'metasAsociadas'
    ];

    /**
     * RELACIONES
     */

    public function ods()
    {
        // Relación con el modelo ODS
        return $this->belongsTo(ODS::class, 'id');
    }

    public function indicadores()
    {
        // Se asume que en 'ods_indicadores' usas 'meta_id' para vincular con esta tabla
        return $this->hasMany(OdsIndicador::class, 'meta_id');
    }

    public function proyectos()
    {
        // Ajustado al nombre de la tabla pivote que se ve en tu captura
        return $this->belongsToMany(Proyecto::class, 'objetivo_ods_proyecto', 'objetivo_ods_id', 'proyecto_id');
    }

    /**
     * LÓGICA DE CÁLCULO
     */

    public function calcularAvanceMeta()
    {
        $indicadores = $this->indicadores;

        if (!$indicadores || $indicadores->isEmpty()) {
            return 0;
        }

        // Promediamos el cumplimiento de los indicadores vinculados
        $promedio = $indicadores->avg('porcentaje_cumplimiento');

        return round($promedio ?? 0, 1);
    }

    public function getColorMeta()
    {
        $avance = $this->calcularAvanceMeta();
        
        if ($avance >= 100) return '#059669'; // Verde
        if ($avance > 0)    return '#3b82f6'; // Azul
        return '#94a3b8'; // Gris
    }
}