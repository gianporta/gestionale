<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CondizioniPagamento  extends Model
{
    protected $fillable = [
        'codice',
        'nome',
        'attivo',
    ];
}
