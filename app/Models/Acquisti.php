<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Acquisti  extends Model
{
    protected $table = 'documenti';
    protected static function booted()
    {
        static::addGlobalScope('acquisti', function ($query) {
            $query->where('tipo_documento', 2);
        });
    }
    protected $fillable = [

    ];
}
