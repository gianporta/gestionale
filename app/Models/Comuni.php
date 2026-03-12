<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
