<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Provinces extends Authenticatable
{
    protected $fillable = [
        'provincia',
        'sigla',
        'regione',
    ];
}
