<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Models\Packages;
use App\Models\Hours;
use Illuminate\Support\Facades\DB;

class Dashboard extends BaseDashboard
{
    protected static string $view = 'filament.pages.dash';
    protected static bool $isLazy = false;

    public function getPackages()
    {
        return Packages::query()
            ->join('customers','customers.id','=','packages.cliente_id')
            ->leftJoin('tasks','tasks.pacchetto_id','=','packages.id')
            ->leftJoin('hours','hours.task_id','=','tasks.id')
            ->whereRaw('JSON_CONTAINS(packages.user_id, ?)', [json_encode((string) auth()->id())])
            ->where('packages.attivo',1)
            ->select(
                'packages.id',
                'packages.nome',
                'packages.ore',
                'customers.ragione_sociale as cliente',
                DB::raw('COALESCE(SUM(hours.ore_lavorate),0) as ore_usate'),
                DB::raw('(packages.ore - COALESCE(SUM(hours.ore_lavorate),0)) as ore_rimaste')
            )
            ->groupBy(
                'packages.id',
                'packages.nome',
                'packages.ore',
                'customers.ragione_sociale'
            )
            ->get();
    }

    public function getHours()
    {
        return Hours::query()
            ->join('tasks','tasks.id','=','hours.task_id')
            ->join('packages','packages.id','=','tasks.pacchetto_id')
            ->whereRaw('JSON_CONTAINS(packages.user_id, ?)', [json_encode((string) auth()->id())])
            ->where('packages.attivo',1)
            ->select(
                'hours.data_lavorazione',
                'hours.ore_lavorate',
                'hours.descrizione',
                'hours.stato',
                'tasks.task',
                DB::raw('YEAR(hours.data_lavorazione) as anno')
            )
            ->orderByDesc('hours.data_lavorazione')
            ->get()
            ->groupBy('anno');
    }

    protected function getViewData(): array
    {
        return [
            'packages' => $this->getPackages(),
            'hoursByYear' => $this->getHours(),
            'openTasks' => $this->getOpenTasks(),
        ];
    }

    public function getTitle(): string
    {
        return 'Dash ' . auth()->user()->roles->first()->name;
    }
    public function getOpenTasks()
    {
        return Hours::query()
            ->join('tasks','tasks.id','=','hours.task_id')
            ->join('packages','packages.id','=','tasks.pacchetto_id')
            ->whereRaw('JSON_CONTAINS(packages.user_id, ?)', [json_encode((string) auth()->id())])
            ->where('packages.attivo',1)
            ->where('hours.stato','!=',3)
            ->select(
                'tasks.id',
                'tasks.task',
                DB::raw('SUM(hours.ore_lavorate) as ore_lavorate')
            )
            ->groupBy('tasks.id','tasks.task')
            ->orderBy('tasks.task')
            ->get();
    }
}
