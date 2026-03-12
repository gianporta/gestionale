<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Hours extends Authenticatable
{
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
