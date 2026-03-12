<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Provinces  extends Model
{
    protected $fillable = [
        'prov',
        'sigla',
        'regione',
        'country_code',
    ];
}
