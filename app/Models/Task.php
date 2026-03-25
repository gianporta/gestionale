<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'pacchetto_id',
        'task',
        'stima',
        'task_ore',
        'attivo',
    ];
}
