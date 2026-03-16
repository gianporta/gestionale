<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditMemo  extends Model
{
    protected $table = 'documenti';
    protected static function booted()
    {
        static::addGlobalScope('credit_memo', function ($query) {
            $query->where('tipo_documento', 5);
        });
    }
    protected $fillable = [
    ];
}
