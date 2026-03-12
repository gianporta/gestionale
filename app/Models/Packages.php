<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
class Packages extends Authenticatable
{
    protected $fillable = [
        'cliente_id',
        'user_id',
        'nome',
        'costo_orario',
        'ore',
        'attivo',
    ];
}
