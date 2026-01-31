<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

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

    public function getCreatedAgoAttribute()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }

    public function getUpdatedAgoAttribute()
    {
        return Carbon::parse($this->updated_at)->diffForHumans();
    }
}
