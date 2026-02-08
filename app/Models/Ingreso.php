<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    protected $table = 'ingresos';
    protected $primaryKey = 'id_ingreso';

    protected $fillable = [
        'descripcion',
        'monto',
        'id_usuario',
        'id_tarjeta',
        'id_categoria',
        'estado_ingreso',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }

    public function tarjeta()
    {
        return $this->belongsTo(Tarjeta::class, 'id_tarjeta', 'id_tarjeta');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }
}
