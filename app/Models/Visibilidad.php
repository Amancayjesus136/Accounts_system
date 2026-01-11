<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Visibilidad extends Model
{
    protected $table = 'visibilidades';
    protected $primaryKey = 'id_visibilidad';
    protected $fillable = [
        'tipo_visibilidad',
        'estado_visibilidad',
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
