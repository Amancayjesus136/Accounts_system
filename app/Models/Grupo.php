<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    protected $table = 'grupos';
    protected $primaryKey = 'id_grupo';
    protected $fillable = [
        'id_visibilidad',
        'id_user',
        'nombre_grupo',
        'estado_grupo',
    ];
}
