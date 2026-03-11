<?php

namespace App\Helpers;

use App\Models\Customer;
use App\Models\Packages;
use App\Models\Task;
use App\Models\User;

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

    public static function getSstatusOptions(): array
    {
        return [
            '1' => 'In lavorazione',
            '2' => 'Da Testare',
            '3' => 'Finito',
        ];
    }

    private static function getUserOptions(): array
    {
        return User::query()
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    private static function getClienteOptions(): array
    {
        return Customer::query()
            ->where('tipo_cliente', 2)
            ->pluck('ragione_sociale', 'id')
            ->toArray();
    }

    private static function getTaskOptions(): array
    {
        return Task::query()
            ->where('attivo', 1)
            ->pluck('task', 'id')
            ->toArray();
    }

    private static function getPackagesActive(): array
    {
        return Packages::query()
            ->join('customers', 'customers.id', '=', 'packages.cliente_id')
            ->where('packages.attivo', 1)
            ->selectRaw("packages.id, CONCAT(customers.ragione_sociale, ' - ', packages.nome) as label")
            ->pluck('label', 'packages.id')
            ->toArray();
    }

    private static function getOreOptions(): array
    {
        return [
            10 => '10',
            20 => '20',
            30 => '30',
            50 => '50',
            100 => '100',
        ];
    }

    public static function getFormFieldConfig(string $column): array
    {
        switch ($column) {
            case 'data_lavorazione':
                return [
                    'type' => 'date',
                    'default' => now()->toDateString(),
                ];
            case 'guard_name':
                return [
                    'type' => 'text',
                    'default' => 'web'
                ];
            case 'attivo':
            case 'is_active':
                return [
                    'type' => 'select',
                    'options' => [
                        0 => 'No',
                        1 => 'Sì',
                    ],
                    'default' => 1,
                ];
            case 'pacchetto_id':
                return [
                    'type' => 'select',
                    'options' => self::getPackagesActive(),
                ];
            case 'ore':
                return [
                    'type' => 'select',
                    'options' => self::getOreOptions(),
                ];
            case 'stato':
                return [
                    'type' => 'select',
                    'options' => self::getSstatusOptions(),
                ];
            case 'cliente_id':
                return [
                    'type' => 'select',
                    'options' => self::getClienteOptions(),
                ];
            case 'task_id':
                return [
                    'type' => 'select',
                    'options' => self::getTaskOptions(),
                ];
            case 'expiration':
                return [
                    'type' => 'datetime',
                ];
            case 'psw':
                return [
                    'type' => 'password',
                ];
            case 'email':
                return [
                    'type' => 'email',
                ];
            case 'user':
                return [
                    'type' => 'select',
                    'options' => self::getUserOptions(),
                    'default' => auth()->id(),
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
}
