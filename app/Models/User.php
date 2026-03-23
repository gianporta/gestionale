<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
    use HasFactory, Notifiable;
    protected $fillable = [
        'name',
        'email',
        'cliente_id',
        'password',
        'nome_banca',
        'iban_bonifici',
        'intestatario_conto_corrente',
        'percentuale_ritenuta_di_acconto',
        'percentuale_inps',
        'percentuale_iva',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
