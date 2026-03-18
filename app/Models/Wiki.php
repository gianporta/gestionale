<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Wiki  extends Model
{
    protected $fillable = [
        'categoria',
        'problema',
        'link',
        'comando',
        'sql',
        'note',
        'attivo',
    ];
    public function categoriaRel()
    {
        return $this->belongsTo(\App\Models\Categoria::class, 'categoria');
    }
}
