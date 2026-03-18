<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suppliers  extends Model
{
    const TYPE_CUSTOMER_SUPPLIERS = 1;
    protected $table = 'customers';
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
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->tipo_cliente = self::TYPE_CUSTOMER_SUPPLIERS;
        });
    }
    public function jobs()
    {
        return $this->hasMany(Job::class, 'cliente');
    }
    public function nazione()
    {
        return $this->belongsTo(Countries::class, 'nazione');
    }
}
