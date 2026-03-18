<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoDocumenti extends Model
{
    protected $table = 'tipo_documenti';
    protected $fillable = [
        'nome',
    ];
}
