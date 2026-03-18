<?php

namespace App\Filament\Pages\Clienti;

use App\Models\Hours;
use App\Models\Packages;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class Pacchetti extends Page
{
    protected static ?string $slug = 'clienti/pacchetti';
    protected static string $view = 'filament.pages.clienti.pacchetti';
    protected static bool $isLazy = false;
    protected static bool $shouldRegisterNavigation = false;

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

    protected function getViewData(): array
    {
        return [
            'packages' => $this->getPackages(),
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
