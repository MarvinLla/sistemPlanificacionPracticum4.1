<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PndObjetivo extends Model
{
    use HasFactory;

    protected $table = 'pnd_objetivos';

    protected $fillable = [
        'eje',
        'nombre_objetivo',
        'descripcion', 
    ];
}