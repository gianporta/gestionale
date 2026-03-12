<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hours  extends Model
{
    protected $fillable = [
        'data_lavorazione',
        'task_id',
        'ore_lavorate',
        'descrizione',
        'note',
        'stato',
        'user',
        'attivo',
    ];
}
