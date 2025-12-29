<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ODS extends Model
{
    use  HasFactory;

    protected $filelable = ['nombreODS', 'proyectoRelacionado'];
}
