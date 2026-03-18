<?php

namespace App\Filament\Pages\Clienti;

use App\Models\Hours;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class Ore extends Page
{
    protected static ?string $slug = 'clienti/ore';
    protected static string $view = 'filament.pages.clienti.ore';
    protected static bool $isLazy = false;
    protected static bool $shouldRegisterNavigation = false;

    public function getTitle(): string
    {
        return 'Ore';
    }

    protected function getClienteId()
    {
        return auth()->user()->cliente_id;
    }

    public function getHours()
    {
        $clienteId = $this->getClienteId();

        return Hours::query()
            ->join('tasks', 'tasks.id', '=', 'hours.task_id')
            ->join('packages', 'packages.id', '=', 'tasks.pacchetto_id')
            ->leftJoin('stato_tasks', 'stato_tasks.id', '=', 'hours.stato')
            ->where('packages.cliente_id', $clienteId)
            ->where('packages.attivo', 1)
            ->select(
                'hours.data_lavorazione',
                'hours.ore_lavorate',
                'hours.descrizione',
                'hours.stato',
                'tasks.task',
                'stato_tasks.nome as stato_nome',
                'stato_tasks.style as stato_style',
                DB::raw('YEAR(hours.data_lavorazione) as anno')
            )
            ->orderByDesc('hours.data_lavorazione')
            ->get()
            ->groupBy('anno');
    }

    protected function getViewData(): array
    {
        return [
            'hoursByYear' => $this->getHours(),
        ];
    }
}
