<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Proyecto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 'entidad_id', 'programa_id', 'pnd_objetivo_id', 'metas_ods',         
        'descripcion', 'objetivos', 'presupuesto_estimado', 'responsable', 
        'beneficio', 'fecha_inicio', 'fecha_final', 'correo_contacto', 
        'telefono_contacto', 'estado', 'metas_finales', 'justificacion_pnd',
        'indicador_nombre', 'indicador_linea_base', 'indicador_meta', 
        'indicador_frecuencia', 'ubicacion_provincia', 'ubicacion_canton', 
        'ubicacion_parroquia', 'beneficiarios_directos'
    ];

    // --- RELACIONES ---

    public function ods() {
        return $this->belongsToMany(ODS::class, 'objetivo_ods_proyecto', 'proyecto_id', 'objetivo_ods_id');
    }

    public function entidad() { return $this->belongsTo(Entidad::class, 'entidad_id'); }
    public function programa() { return $this->belongsTo(Programa::class, 'programa_id'); }
    public function pndObjetivo() { return $this->belongsTo(PndObjetivo::class, 'pnd_objetivo_id'); }
    public function avances() { return $this->hasMany(Avance::class, 'proyecto_id'); }
    public function politicas() { return $this->belongsToMany(Politica::class, 'politica_proyecto'); }
    
    public function indicadores() {
        return $this->hasMany(IndicadorProyecto::class, 'proyecto_id');
    }

    // --- LÓGICA DE NEGOCIO DINÁMICA ---

    /**
     * CORRECCIÓN CLAVE: El porcentaje del proyecto ahora es el promedio
     * real de sus indicadores vinculados con avances.
     */
    public function porcentajePresupuesto() {
        // Solo tomamos indicadores de este proyecto que tengan avances registrados
        $indicadoresValidos = $this->indicadores()->whereHas('avances')->get();

        if ($indicadoresValidos->isEmpty()) {
            return 0; // Si no hay indicadores con avances, el avance es 0%
        }

        $promedioCumplimiento = $indicadoresValidos->avg(function($ind) {
            $gastado = \App\Models\Avance::where('indicador_proyecto_id', $ind->id)
                                         ->where('proyecto_id', $this->id)
                                         ->sum('monto_gastado');
            
            // Calculamos el porcentaje respecto a la meta fija del indicador
            return $ind->valor_meta_fijo > 0 ? min(($gastado / $ind->valor_meta_fijo) * 100, 100) : 0;
        });

        return round($promedioCumplimiento, 2);
    }

    /**
     * Mantiene la compatibilidad con el resto de tu código de presupuesto
     */
    public function presupuestoConsumido() {
        return $this->avances()->sum('monto_gastado') ?? 0;
    }

    public function presupuestoRestante() {
        $consumido = $this->presupuestoConsumido();
        return max(0, ($this->presupuesto_estimado ?? 0) - $consumido);
    }

    public function calcularAvanceFisico() {
        return $this->porcentajePresupuesto();
    }

    // --- ACCESORES ---

    public function getDiasRestantesAttribute() {
        if (!$this->fecha_final) return 0;
        $fechaFin = Carbon::parse($this->fecha_final);
        $hoy = Carbon::now();
        return (int) $hoy->diffInDays($fechaFin, false);
    }

    public function getPorcentajeAvanceAttribute() {
        return $this->porcentajePresupuesto();
    }

    /**
     * Promedia el avance basándose en los ODS vinculados
     */
    public function calcularCumplimientoReal() {
        $metasOds = $this->ods; 
        
        if ($metasOds->isEmpty()) return 0;

        $promedio = $metasOds->avg(function($ods) {
            // Llama al método que filtramos por ID de proyecto para ser exactos
            return $ods->calcularAvanceMetaPorProyecto($this->id); 
        });

        return round($promedio ?? 0, 2);
    }
}