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

    public function getTitle(): string
    {
        return 'Pacchetti | Task da lavorare';
    }

    protected function getClienteId()
    {
        return auth()->user()->cliente_id;
    }

    public function getPackages()
    {
        $clienteId = $this->getClienteId();
        return Packages::query()
            ->join('customers','customers.id','=','packages.cliente_id')
            ->leftJoin('tasks','tasks.pacchetto_id','=','packages.id')
            ->leftJoin('hours','hours.task_id','=','tasks.id')
            ->where('packages.cliente_id', $clienteId)
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

    public function getOpenTasks()
    {
        $clienteId = $this->getClienteId();
        return Hours::query()
            ->join('tasks','tasks.id','=','hours.task_id')
            ->join('packages','packages.id','=','tasks.pacchetto_id')
            ->where('packages.cliente_id', $clienteId)
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

    protected function getViewData(): array
    {
        return [
            'packages' => $this->getPackages(),
            'openTasks' => $this->getOpenTasks(),
        ];
    }
}
