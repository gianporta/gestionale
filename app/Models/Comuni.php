<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comuni  extends Model
{
    protected $fillable = [
        'istat',
        'comune',
        'pronvicia',
        'prefisso',
        'cap',
        'codfisco',
    ];
}
