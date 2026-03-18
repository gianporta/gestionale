<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Job  extends Model
{
    protected $fillable = [
        'cliente',
        'attivo',
        'stato_job',
        'costo_orario',
        'nome',
        'descrizione',
        'iva',
        'costo',
    ];
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'cliente');
    }

    public function supplier()
    {
        return $this->belongsTo(Suppliers::class, 'cliente');
    }
    public static function getStatoJob()
    {
        return ['Chiuso', 'Aperto'];
    }
}
