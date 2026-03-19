<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditMemo  extends Model
{
    const TYPE_DOC = 6;
    protected $table = 'documenti';
    protected static function booted()
    {
        static::addGlobalScope('credit_memo', function ($query) {
            $query->where('tipo_documento', self::TYPE_DOC);
        });
    }
    protected $fillable = [
    ];
}
