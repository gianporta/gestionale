<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer  extends Model
{
    protected $fillable = [
        'ragione_sociale',
        'company_id',
        'tipo_cliente',
        'email',

        'indirizzo',
        'cap',
        'citta',
        'provincia',
        'nazione',

        'partita_iva',
        'codice_fiscale',
        'sdi',
        'pec',

        'iban',
        'banca',
        'intestatario_conto',

        'telefono',
        'cellulare',
        'fax',
        'sito_web',

        'attivo',
    ];
    public function nazione()
    {
        return $this->belongsTo(Countries::class, 'nazione');
    }
}
