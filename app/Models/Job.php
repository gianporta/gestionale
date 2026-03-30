<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    const STATO_CHIUSO = 0;
    const STATO_APERTO = 1;
    protected $fillable = [
        'cliente',
        'attivo',
        'stato_job',
        'costo_orario',
        'nome',
        'descrizione',
        'iva',
        'costo',
        'num_ore',
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
        return [0 => 'Chiuso', 1 => 'Aperto'];
    }
}
