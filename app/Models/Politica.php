<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Politica extends Model
{
    protected $fillable = ['objetivo_pnd_id', 'nombre', 'descripcion'];

    public function objetivo()
    {
    return $this->belongsTo(PndObjetivo::class, 'objetivo_pnd_id');
    }
}
