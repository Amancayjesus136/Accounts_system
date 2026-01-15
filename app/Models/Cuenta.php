<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuenta extends Model
{
    protected $table = 'cuentas';
    protected $primaryKey = 'id_cuenta';

    protected $fillable = [
        'id_usuario',
        'id_grupo',
        'id_plataforma',
        'verificacion',
        'descripcion',
        'estado_cuenta',
    ];
}
