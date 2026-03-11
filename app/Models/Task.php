<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Task extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'pacchetto_id',
        'task',
        'attivo'
    ];
}
