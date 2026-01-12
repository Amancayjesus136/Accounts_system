<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Notification extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'type',
        'notifiable_type',
        'notifiable_id',
        'data',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    protected static function booted()
    {
        static::creating(function ($notification) {
            if (!$notification->id) {
                $notification->id = (string) Str::uuid();
            }
        });
    }
}
