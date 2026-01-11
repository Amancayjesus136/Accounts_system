<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asignado extends Model
{
    protected $table = 'asignados';
    protected $primaryKey = 'id_asignado';
    protected $fillable = [
        'id_grupo',
        'id_usuario',
        'estado_asignado',
    ];
}
