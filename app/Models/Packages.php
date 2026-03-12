<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Packages  extends Model
{
    protected $fillable = [
        'cliente_id',
        'user_id',
        'nome',
        'costo_orario',
        'ore',
        'attivo',
    ];
}
