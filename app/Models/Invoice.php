<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Invoice extends Model
{
    const INVOICE_TYPE_DOC = 2;
    protected $table = 'documenti';

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->tipo_documento = self::INVOICE_TYPE_DOC;
            $model->numero_documento = self::getNextNumeroDocumento();
            $model->progressivo_sdi = self::getNextProgressivoSdi();
        });
    }
    public static function getNextNumeroDocumento(): int
    {
        $year = now()->year;

        $lastNumber = DB::table('documenti')
            ->where('tipo_documento', self::INVOICE_TYPE_DOC)
            ->whereYear('data_documento', $year)
            ->max('numero_documento');

        return ($lastNumber ?? 0) + 1;
    }
    public static function getNextProgressivoSdi(): int
    {
        $year = now()->year;

        $lastNumber = DB::table('documenti')
            ->max('progressivo_sdi');

        return ($lastNumber ?? 0) + 1;
    }
    protected $fillable = [
    ];
}
