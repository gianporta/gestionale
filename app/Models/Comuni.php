<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Comuni extends Authenticatable
{
    protected $fillable = [
        'istat',
        'comune',
        'pronvicia',
        'prefisso',
        'cap',
        'codfisco',
    ];
}
