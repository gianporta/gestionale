<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proforma  extends Model
{
    const TYPE_DOC = 4;
    protected $table = 'documenti';
    protected static function booted()
    {
        static::addGlobalScope('invoice', function ($query) {
            $query->where('tipo_documento', self::TYPE_DOC);
        });
    }
    protected $fillable = [
    ];
}
