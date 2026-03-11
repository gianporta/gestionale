<?php

namespace App\Helpers;

use App\Models\Customer;
use App\Models\OauthUser;
use App\Models\Repo;

class FormHelper
{
    public static function getHashTypes(): array
    {
        return [
            'md5' => 'MD5',
            'bcrypt' => 'Bcrypt',
        ];
    }

    public static function getYesNoOptions(): array
    {
        return [
            0 => 'No',
            1 => 'Sì',
        ];
    }

    public static function getCmsOptions(): array
    {
        return [
            'm2' => 'Magento 2',
            'm1' => 'Magento 1',
            'wp' => 'Wordpress',
        ];
    }

    public static function getPhpVersions(): array
    {
        return [
            'php@7.2' => 'PHP 7.2',
            'php@7.4' => 'PHP 7.4',
            'php@8.0' => 'PHP 8.0',
            'php@8.1' => 'PHP 8.1',
            'php@8.3' => 'PHP 8.3',
            'php@8.4' => 'PHP 8.4',
        ];
    }

    private static function getClienteOptions(): array
    {
        return Customer::query()
            ->where('tipo_cliente', 2)
            ->pluck('ragione_sociale', 'id')
            ->toArray();
    }
    private static function getOreOptions(): array
    {
        return [
            10 => '10',
            30 => '30',
            50 => '50',
            100 => '100',
        ];
    }

    public static function getFormFieldConfig(string $column): array
    {
        switch ($column) {
            case 'attivo':
            case 'is_active':
                return [
                    'type' => 'select',
                    'options' => [
                        0 => 'No',
                        1 => 'Sì',
                    ],
                ];
            case 'ore':
                return [
                    'type' => 'select',
                    'options' => self::getOreOptions(),
                ];
            case 'cliente_id':
                return [
                    'type' => 'select',
                    'options' => self::getClienteOptions(),
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
        return ['id', 'created_at', 'updated_at', 'remember_token', 'email_verified_at', 'two_factor_secret', 'two_factor_recovery_codes', 'two_factor_confirmed_at'];
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
