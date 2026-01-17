<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuentaUsuario extends Model
{
    protected $table = 'cuenta_usuarios';
    protected $primaryKey = 'id_cuenta_usuario';

    protected $fillable = [
        'username_cuenta',
        'number_cuenta',
        'email_cuenta',
        'password_cuenta',
        'id_cuenta',
        'estado_cuenta',
    ];

    public function cuenta()
    {
        return $this->belongsTo(Cuenta::class, 'id_cuenta', 'id_cuenta');
    }

}
