<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Avance extends Model
{
    use HasFactory;

    protected $fillable = [
        'proyecto_id', 
        'titulo', 
        'descripcion', 
        'monto_gastado', 
        'archivo', 
        'foto', 
        'fecha_avance'
    ];

    // Un avance pertenece a un solo proyecto
    public function proyecto() 
    {
        return $this->belongsTo(Proyecto::class);
    }
}