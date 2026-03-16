<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatePayment  extends Model
{
    protected $fillable = [
        'nome',
        'attivo',
    ];
}
