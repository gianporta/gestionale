<?php

namespace App\Helpers;

use App\Models\Repo;
use App\Models\OauthUser;
class TableHelper
{
    public static function formatColumnValue(string $column, mixed $value): mixed
    {
        switch ($column) {
            case 'packages':
                return ($value == '*') ? 'Tutti' : $value;
            case 'is_active':
                return ($value == 0) ? 'No' : 'Sì';
            case 'id_user':
                return OauthUser::find($value)->user;
            case 'id_repo':
                return Repo::find($value)->packages;
            default:
                return $value;
        }
    }

    public static function getExcludedColumns(): array
    {
        return ['remember_token', 'psw', 'password', 'email_verified_at', 'two_factor_secret', 'two_factor_recovery_codes', 'two_factor_confirmed_at'];
    }
}
