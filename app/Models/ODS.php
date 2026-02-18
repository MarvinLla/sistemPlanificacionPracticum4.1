<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ODS extends Model
{
    use HasFactory;
    protected $table = 'objetivos_ods';
    protected $fillable = ['nombreObjetivo', 'descripcion', 'metasAsociadas'];

    public function getMetasAttribute() {
        return collect([$this]);
    }

    // Filtra indicadores estrictamente por proyecto para evitar datos cruzados
    public function indicadoresPorProyecto($proyectoId) {
        return \App\Models\IndicadorProyecto::where('proyecto_id', $proyectoId)
            ->whereHas('avances') 
            ->get();
    }

    public function proyectos() {
        return $this->belongsToMany(Proyecto::class, 'objetivo_ods_proyecto', 'objetivo_ods_id', 'proyecto_id');
    }

    // Calcula el avance usando solo los indicadores de ESTE proyecto
    public function calcularAvanceMetaPorProyecto($proyectoId) {
        $indicadores = $this->indicadoresPorProyecto($proyectoId);
        if ($indicadores->isEmpty()) return 0;

        $cumplimiento = $indicadores->avg(function($ind) use ($proyectoId) {
            $gastado = \App\Models\Avance::where('indicador_proyecto_id', $ind->id)
                                         ->where('proyecto_id', $proyectoId)
                                         ->sum('monto_gastado');
            return $ind->valor_meta_fijo > 0 ? min(($gastado / $ind->valor_meta_fijo) * 100, 100) : 0;
        });

        return round($cumplimiento, 2);
    }

    // ESTE ES EL MÉTODO QUE DA EL ERROR. Ahora recibe el valor.
    public function getColorMeta($valor = null) {
        // Si no pasas valor, intenta calcularlo, pero mejor pasarlo desde la vista
        $p = $valor ?? 0; 
        if ($p >= 100) return '#10b981'; // Verde
        if ($p > 0) return '#3b82f6';   // Azul
        return '#94a3b8';                // Gris
    }
}