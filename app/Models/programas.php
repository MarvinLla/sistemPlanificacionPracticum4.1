<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class programas extends Model
{
    use  HasFactory;

    protected $filelable = ['nombrePrograma', 'tipoPrograma', 'categoria'];
}
