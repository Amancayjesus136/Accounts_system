<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Plataforma extends Model
{
    protected $table = 'plataformas';
    protected $primaryKey = 'id_plataforma';

    protected $fillable = [
        'grupo_plataforma',
        'entidad_plataforma',
        'nombre_plataforma',
        'estado_plataforma',
    ];

    public function getCreatedAgoAttribute()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }

    public function getUpdatedAgoAttribute()
    {
        return Carbon::parse($this->updated_at)->diffForHumans();
    }
}
