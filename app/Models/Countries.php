<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Countries extends Authenticatable
{
    protected $fillable = [
        'country_code',
        'country_name',
    ];
}
