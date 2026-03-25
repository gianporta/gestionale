<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'cliente_id',
        'task',
        'descrizione',
        'stima',
        'task_ore',
        'attivo',
    ];
}
