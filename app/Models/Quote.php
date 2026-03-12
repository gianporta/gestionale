<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Quote extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [

    ];
}
