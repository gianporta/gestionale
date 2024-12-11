<?php

namespace App\Helpers;

use App\Models\OAuthUser;
use App\Models\Repo;

class DataFormatter
{
    public static function formatColumnHeader(string $column): string
    {
        $headers = [
            'id' => 'ID',
            'user' => 'Utente',
            'psw' => 'Password',
            'is_active' => 'Attivo',
            'created_at' => 'Creato il',
            'updated_at' => 'Modificato il',
            'expiration' => 'Data di scadenza',
            'actions' => 'Azione',
        ];

        return $headers[$column] ?? ucfirst(str_replace('_', ' ', $column));
    }

    public static function formatColumnValue($key, $value)
    {
        switch ($key) {
            case 'is_active':
                return $value ? 'Attivo' : 'Non Attivo';
            case 'id_user':
                return OAuthUser::find($value)?->user ?? '-';
            case 'id_repo':
                return Repo::find($value)?->packages ?? '-';
            case 'expiration':
                return $value && strtotime($value) ? date('d/m/Y', strtotime($value)) : '-';
            default:
                return $value ?? '-';
        }
    }
}