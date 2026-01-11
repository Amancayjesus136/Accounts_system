<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visibilidad extends Model
{
    protected $table = 'visibilidades';
    protected $primaryKey = 'id_visibilidad';
    protected $fillable = [
        'tipo_visibilidad',
        'estado_visibilidad',
    ];
}
