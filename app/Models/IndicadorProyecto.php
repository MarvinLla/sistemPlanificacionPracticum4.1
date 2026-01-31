<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndicadorProyecto extends Model
{
    use HasFactory;

    protected $table = 'indicador_proyectos';

    // Añade esto para permitir el guardado masivo
    protected $fillable = [
        'proyecto_id',
        'meta_ods_texto',
        'nombre_indicador',
        'descripcion',
        'valor_meta_fijo'
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }
    public function avances() {
    return $this->hasMany(Avance::class, 'indicador_proyecto_id');
}
}