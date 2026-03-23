<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Invoice extends Model
{
    const TYPE_DOC = 2;

    protected $table = 'documenti';

    protected $fillable = [
        'numero_documento',
        'progressivo_sdi',
        'data_documento',

        'cliente',
        'cliente_ragione_sociale',
        'cliente_company_id',
        'cliente_partita_iva',
        'cliente_codice_fiscale',
        'cliente_indirizzo',
        'cliente_cap',
        'cliente_citta',
        'cliente_provincia',
        'cliente_nazione',

        'banca',
        'iban',
        'intestatario_conto',

        'imponibile',
        'iva',
        'netto_a_pagare',

        'pagato',
        'data_pagamento',
        'data_scadenza',

        'descrizione',
        'frase_in_calce',

        'stato_documento',

        'content',
    ];

    protected $casts = [
        'data_documento' => 'date',
        'data_pagamento' => 'date',
        'data_scadenza' => 'date',

        'imponibile' => 'decimal:2',
        'iva' => 'decimal:2',
        'netto_a_pagare' => 'decimal:2',
        'pagato' => 'decimal:2',

        'content' => 'array',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->tipo_documento = self::TYPE_DOC;

            if (empty($model->numero_documento))
                $model->numero_documento = self::getNextNumeroDocumento();

            if (empty($model->progressivo_sdi))
                $model->progressivo_sdi = self::getNextProgressivoSdi();
        });
    }

    public static function getNextNumeroDocumento(): int
    {
        $year = now()->year;

        $lastNumber = DB::table('documenti')
            ->where('tipo_documento', self::TYPE_DOC)
            ->whereYear('data_documento', $year)
            ->max('numero_documento');

        return ($lastNumber ?? 0) + 1;
    }

    public static function getNextProgressivoSdi(): int
    {
        $lastNumber = DB::table('documenti')
            ->max('progressivo_sdi');

        return ($lastNumber ?? 0) + 1;
    }
}
