<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asignado extends Model
{
    protected $table = 'asignados';
    protected $primaryKey = 'id_asignado';
    protected $fillable = [
        'id_grupo',
        'id_usuario',
        'estado_asignado',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }

    public function grupo()
{
    return $this->belongsTo(Grupo::class, 'id_grupo', 'id_grupo');
}
}
