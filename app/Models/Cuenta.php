<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuenta extends Model
{
    protected $table = 'cuentas';
    protected $primaryKey = 'id_cuenta';

    protected $fillable = [
        'id_usuario',
        'id_plataforma',
        'id_visibilidad',
        'verificacion',
        'descripcion',
        'estado_cuenta',
    ];

    public function cuentaUsuario()
    {
        return $this->hasOne(CuentaUsuario::class, 'id_cuenta', 'id_cuenta');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
