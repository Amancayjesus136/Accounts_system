<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $table = 'tokens';
    protected $primaryKey = 'id_token';

    protected $fillable = [
        'number_token',
        'time_token',
        'id_user',
        'estado_token',
    ];
}
