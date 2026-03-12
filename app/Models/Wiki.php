<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Wiki extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [

    ];
}
