<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Packages extends Model
{
    protected $fillable = [
        'cliente_id',
        'nome',
        'costo_orario',
        'ore',
        'attivo',
        'proforma',
        'fatturato',
        'pagato',
    ];
}
