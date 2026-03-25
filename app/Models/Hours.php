<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Hours  extends Model
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
    ];
    protected static function booted()
    {
        static::saved(function ($hours) {
            self::updateTaskOre($hours->task_id);
        });

        static::deleted(function ($hours) {
            self::updateTaskOre($hours->task_id);
        });

        static::updating(function ($hours) {
            if ($hours->isDirty('task_id')) {
                self::updateTaskOre($hours->getOriginal('task_id'));
            }
        });
    }

    private static function updateTaskOre($taskId): void
    {
        if (!$taskId) return;
        $totale = self::where('task_id', $taskId)
            ->sum('ore_lavorate');

        Task::where('id', $taskId)
            ->update([
                'totale_ore_lavorate' => number_format($totale, 2, '.', '')
            ]);
    }
}
