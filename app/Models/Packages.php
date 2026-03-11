<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Packages extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'cliente_id',
        'nome_pacchetto',
        'ore',
        'attivo',
    ];
}
