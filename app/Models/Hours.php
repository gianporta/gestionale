<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hours extends Model
{
    protected $fillable = [
        'data_lavorazione',
        'task_id',
        'ore_lavorate',
        'descrizione',
        'note',
        'stato',
        'user',
        'attivo',
        'packages_id'
    ];

    protected static function booted()
    {
        static::saved(function ($hours) {
            self::updateTaskOre($hours->task_id);
            self::updatePackageOre($hours->packages_id);
        });

        static::deleted(function ($hours) {
            self::updateTaskOre($hours->task_id);
            self::updatePackageOre($hours->packages_id);
        });

        static::updating(function ($hours) {
            if ($hours->isDirty('task_id'))
                self::updateTaskOre($hours->getOriginal('task_id'));

            if ($hours->isDirty('packages_id'))
                self::updatePackageOre($hours->getOriginal('packages_id'));
        });
    }

    private static function updateTaskOre($taskId): void
    {
        if (!$taskId) return;

        $totale = self::where('task_id', $taskId)
            ->selectRaw("SUM(CAST(REPLACE(ore_lavorate, ',', '.') AS DECIMAL(10,2))) as totale")
            ->value('totale') ?? 0;

        Task::where('id', $taskId)
            ->update(['totale_ore_lavorate' => number_format($totale, 2, '.', '')]);
    }

    private static function updatePackageOre($packageId): void
    {
        if (!$packageId) return;

        $totale = self::where('packages_id', $packageId)
            ->selectRaw("SUM(CAST(REPLACE(ore_lavorate, ',', '.') AS DECIMAL(10,2))) as totale")
            ->value('totale') ?? 0;

        Packages::where('id', $packageId)
            ->update(['totale_ore_lavorate' => number_format($totale, 2, '.', '')]);
    }
}
