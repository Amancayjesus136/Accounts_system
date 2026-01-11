<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Grupo extends Model
{
    protected $table = 'grupos';
    protected $primaryKey = 'id_grupo';
    protected $fillable = [
        'id_visibilidad',
        'id_user',
        'nombre_grupo',
        'estado_grupo',
    ];

    public function visibilidad()
    {
        return $this->belongsTo(Visibilidad::class, 'id_visibilidad', 'id_visibilidad');
    }

    public function asignados()
    {
        return $this->hasMany(Asignado::class, 'id_grupo', 'id_grupo');
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
