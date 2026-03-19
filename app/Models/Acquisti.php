<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Acquisti  extends Model
{
    const TYPE_DOC = 3;
    protected $table = 'documenti';
    protected static function booted()
    {
        static::addGlobalScope('acquisti', function ($query) {
            $query->where('tipo_documento', self::TYPE_DOC);
        });
    }
    protected $fillable = [

    ];
}
