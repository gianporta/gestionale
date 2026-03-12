<?php

namespace App\Filament\Pages;

use App\Models\Packages;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class ThreeDash extends Page
{

    protected static string $view = 'filament.pages.three-dash';
    protected static bool $shouldRegisterNavigation = false;
    public static function canAccess(): bool
    {
        return auth()->user()->hasAnyRole(['admin', 'threecommerce']);
    }

    public function getOpenPackages()
    {
        return Packages::query()
            ->join('customers', 'customers.id', '=', 'packages.cliente_id')
            ->leftJoin('tasks', 'tasks.pacchetto_id', '=', 'packages.id')
            ->leftJoin('hours', 'hours.task_id', '=', 'tasks.id')
            ->where('packages.attivo', 1)
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
            ->orderByRaw('(packages.ore - COALESCE(SUM(hours.ore_lavorate),0)) ASC')
            ->get();
    }
    protected function getViewData(): array
    {
        return [
            'packages' => $this->getOpenPackages()
        ];
    }
}
