<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OdsIndicador extends Model
{
    use HasFactory;

    // Nombre de la tabla en la base de datos
    protected $table = 'ods_indicadores';

    // Campos que se pueden llenar masivamente
    protected $fillable = [
        'meta_id', 
        'nombre', 
        'unidad_medida'
    ];

   
    public function meta()
    {
        return $this->belongsTo(OdsMeta::class, 'meta_id');
    }

    
    public function ods()
    {
        return $this->hasOneThrough(
            ODS::class, 
            OdsMeta::class, 
            'id',      // Llave foránea en OdsMeta (id del meta)
            'id',      // Llave foránea en ODS (id del ods)
            'meta_id', // Llave local en OdsIndicador
            'ods_id'   // Llave local en OdsMeta
        );
    }
}