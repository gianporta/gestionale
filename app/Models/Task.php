<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Task extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'pacchetto_id',
        'task',
        'attivo'
    ];
}
