<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Site extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [

    ];
}
