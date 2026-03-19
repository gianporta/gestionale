<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalInvoice  extends Model
{
    const TYPE_DOC = 5;
    protected $table = 'documenti';
    protected static function booted()
    {
        static::addGlobalScope('external_invoice', function ($query) {
            $query->where('tipo_documento', self::TYPE_DOC);
        });
    }
    protected $fillable = [
    ];
}
