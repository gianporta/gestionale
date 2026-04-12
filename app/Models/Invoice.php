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
        'cliente_nazione',
        'cliente_provincia',
        'cliente_citta',
        'cliente_cap',
        'cliente_indirizzo',
        'cliente_ragione_sociale',
        'cliente_partita_iva',
        'cliente_codice_fiscale',
        'cliente_company_id',
        'cliente_ragione_sociale',
        'banca',
        'iban',
        'intestatario_conto',
        'imponibile',
        'contributo_inps',
        'iva',
        'ritenuta_di_acconto',
        'netto_a_pagare',
        'condizioni_pagamento',
        'modalita_pagamento',
        'document_to_state',
        'stato_documento',
        'pagato',
        'data_pagamento',
        'data_scadenza',
        'mostra_inps',
        'somma_inps',
        'mostra_ritenuta',
        'descrizione',
        'frase_in_calce',
        'content',
    ];

    protected $casts = [
        'data_documento' => 'date',
        'data_pagamento' => 'date',
        'data_scadenza' => 'date',
        'imponibile' => 'float',
        'contributo_inps' => 'float',
        'iva' => 'float',
        'ritenuta_di_acconto' => 'float',
        'netto_a_pagare' => 'float',
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
    public function getTipoDocumento(): string
    {
        return \DB::table('type_documents')
            ->where('id', $this->tipo_doc_fatt_el)
            ->value('codice') ?? 'TD01';
    }
}
