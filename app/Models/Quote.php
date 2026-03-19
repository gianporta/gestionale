<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quote  extends Model
{
    const TYPE_DOC = 1;
    protected $table = 'documenti';
    protected static function booted()
    {
        static::addGlobalScope('quote', function ($query) {
            $query->where('tipo_documento', 0);
        });
    }
    protected $fillable = [

    ];
}
