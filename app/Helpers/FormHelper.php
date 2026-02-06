<?php

namespace App\Helpers;

use App\Models\OauthUser;
use App\Models\Repo;

class FormHelper
{
    public static function getFormFieldConfig(string $column): array
    {
        switch ($column) {
            case 'is_active':
                return [
                    'type' => 'select',
                    'options' => [
                        0 => 'No',
                        1 => 'Sì',
                    ],
                ];
            case 'expiration':
                return [
                    'type' => 'datetime',
                ];
            case 'psw':
                return [
                    'type' => 'password',
                ];
            case 'id_user':
                return [
                    'type' => 'select',
                    'options' => self::getOauthUserOptions(),
                ];
            case 'id_repo':
                return [
                    'type' => 'select',
                    'options' => self::getRepoOptions(),
                ];
            case 'email':
                return [
                    'type' => 'email',
                ];
            default:
                return [
                    'type' => 'text',
                ];
        }
    }

    public static function getExcludedColumns(): array
    {
        return ['id', 'created_at', 'updated_at', 'remember_token', 'email_verified_at','two_factor_secret','two_factor_recovery_codes','two_factor_confirmed_at'];
    }

    private static function getOauthUserOptions(): array
    {
        return OauthUser::query()
            ->pluck('user', 'id')
            ->toArray();
    }

    private static function getRepoOptions(): array
    {
        return Repo::query()
            ->pluck('packages', 'id')
            ->toArray();
    }
}
