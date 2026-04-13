<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Job;
class Customer  extends Model
{
    const TYPE_CUSTOMER_CUSTOMER = 2;
    protected $fillable = [
        'ragione_sociale',
        'company_id',
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
        'label_colore',
    ];
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->tipo_cliente = self::TYPE_CUSTOMER_CUSTOMER;
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
    public static function getTypeCustomer(){
        return [1 => 'Fornitore', 2 => 'Cliente'];
    }
}
