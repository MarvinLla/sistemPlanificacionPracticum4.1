<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ODS extends Model
{
    use HasFactory;

   
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
            'objetivo_ods_proyecto', 
            'objetivo_ods_id',       
            'proyecto_id'            
        );
    }
}