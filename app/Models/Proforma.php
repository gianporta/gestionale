<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proforma  extends Model
{
    protected $table = 'documenti';
    protected static function booted()
    {
        static::addGlobalScope('invoice', function ($query) {
            $query->where('tipo_documento', 1);
        });
    }
    protected $fillable = [
    ];
}
