<?php

namespace App\Models;

use App\Models\Customer;
use App\Models\Suppliers;
use Illuminate\Database\Eloquent\Model;
class Job  extends Model
{
    protected $fillable = [
        'cliente',
        'attivo',
        'stato',
        'data_modifica',
        'costo_orario',
        'nome',
        'descrizione',
        'iva',
        'costo',
        'durata',
        'tipo_job'
    ];
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'cliente');
    }

    public function supplier()
    {
        return $this->belongsTo(Suppliers::class, 'cliente');
    }
}
