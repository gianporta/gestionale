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
            ->join('customers', 'customers.id', '=', 'packages.cliente_id')
            ->where('packages.cliente_id', $clienteId)
            ->where('packages.attivo', 1)
            ->whereRaw('COALESCE(packages.totale_ore_lavorate,0) < packages.ore')
            ->select(
                'packages.id',
                'packages.nome',
                'packages.ore',
                'customers.ragione_sociale as cliente',
                DB::raw('COALESCE(packages.totale_ore_lavorate,0) as ore_usate'),
                DB::raw('(packages.ore - COALESCE(packages.totale_ore_lavorate,0)) as ore_rimaste')
            )
            ->get();
    }

    public function getOpenTasks()
    {
        $clienteId = $this->getClienteId();

        return Hours::query()
            ->join('tasks', 'tasks.id', '=', 'hours.task_id')
            ->join('packages', 'packages.id', '=', 'hours.packages_id')
            ->leftJoin('stato_tasks', 'stato_tasks.id', '=', 'hours.stato')
            ->where('packages.cliente_id', $clienteId)
            ->where('packages.attivo', 1)
            ->where('hours.stato', '!=', 3)
            ->select(
                'tasks.id',
                'tasks.task',
                'stato_tasks.nome as stato_nome',
                'stato_tasks.style as stato_style',
                DB::raw('SUM(CAST(REPLACE(hours.ore_lavorate, ",", ".") AS DECIMAL(10,2))) as ore_lavorate')
            )
            ->groupBy(
                'tasks.id',
                'tasks.task',
                'stato_tasks.nome',
                'stato_tasks.style'
            )
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
