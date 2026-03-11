<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Invoice extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [

    ];
}
