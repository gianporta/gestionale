<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Countries extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'country_code',
        'country_name',
    ];
}
