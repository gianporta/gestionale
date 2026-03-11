<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Role extends Authenticatable
{
    protected $fillable = [
        'name',
        'guard_name'
    ];
    protected $hidden = [
    ];
}
