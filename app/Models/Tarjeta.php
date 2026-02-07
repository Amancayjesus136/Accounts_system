<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Tarjeta extends Model
{
    protected $table = 'tarjetas';
    protected $primaryKey = 'id_tarjeta';
    protected $fillable = [
        'tipo_tarjeta',
        'nombre_tarjeta',
        'id_usuario',
        'estado_tarjeta',
    ];

    public function monto()
    {
        return $this->hasOne(Monto::class, 'id_tarjeta');
    }

    public function getCreatedAgoAttribute()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }

    public function getUpdatedAgoAttribute()
    {
        return Carbon::parse($this->updated_at)->diffForHumans();
    }
}
