<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModalitaPagamento  extends Model
{
    protected $fillable = [
        'codice',
        'nome',
        'attivo',
    ];
}
