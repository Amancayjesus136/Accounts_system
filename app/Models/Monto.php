<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Monto extends Model
{
    protected $table = 'montos';
    protected $primaryKey = 'id_monto';
    protected $fillable = [
        'id_tarjeta',
        'monto_tarjeta',
        'estado_monto',
    ];
}
