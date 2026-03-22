<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotaSpese  extends Model
{
    protected $fillable = [
        'nome',
        'costo',
        'data_pagamento',
        'user',
    ];
}
