<?php
declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class DBHelper
{
    /**
     * Ottiene le colonne di una tabella dal database.
     *
     * @param string $tableName
     * @return array
     */
    public static function getTableColumns(string $tableName): array
    {
        return DB::getSchemaBuilder()->getColumnListing($tableName);
    }
}
