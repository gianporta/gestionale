<?php

namespace App\Helpers;

class TableHelper
{
    public static function formatColumnValue(string $column, mixed $value): mixed
    {
        switch ($column) {
            case 'packages':
                return ($value == '*') ? 'Tutti' : $value;
            case 'is_active':
                return ($value == 0) ? 'No' : 'Sì';
            default:
                return $value;
        }
    }
    public static function getExcludedColumns(): array
    {
        return ['remember_token','psw','password'];
    }
}
