<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Hours extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'data_lavorazione',
        'task_id',
        'ore_lavorate',
        'descrizione',
        'note',
        'stato',
        'user',
        'attivo',
    ];
}
