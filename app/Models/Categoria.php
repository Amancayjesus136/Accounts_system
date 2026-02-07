<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';
    protected $primaryKey = 'id_categoria';
    protected $fillable = [
        'nombre_categoria',
        'descripcion_categoria',
        'icono_categoria',
        'tipo_categoria',
        'estado_categoria',
    ];
}
