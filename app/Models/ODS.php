<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ODS extends Model
{
    use HasFactory;

    // Si tu tabla no se llama 'o_d_s' (por el plural automático), 
    // especifica el nombre real aquí:
    protected $table = 'objetivos_ods'; 

    protected $fillable = [
        'nombreObjetivo',
        'descripcion',
        'metasAsociadas'
    ];

    /**
     * Relación inversa: Un ODS pertenece a muchos Proyectos.
     */
    public function proyectos()
    {
        return $this->belongsToMany(
            Proyecto::class, 
            'objetivo_ods_proyecto', // Tabla intermedia
            'objetivo_ods_id',       // Llave foránea de este modelo en la intermedia
            'proyecto_id'            // Llave foránea del otro modelo en la intermedia
        );
    }
}