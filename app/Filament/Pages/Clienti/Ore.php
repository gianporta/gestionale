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

    protected function getClienteId()
    {
        return auth()->user()->cliente_id;
    }

    public function getTitle(): string
    {
        return 'Ore';
    }

    public function getHours()
    {
        $clienteId = $this->getClienteId();
        return Hours::query()
            ->join('tasks', 'tasks.id', '=', 'hours.task_id')
            ->join('packages', 'packages.id', '=', 'tasks.pacchetto_id')
            ->where('packages.cliente_id', auth()->user()->cliente_id)
            ->where('packages.attivo', 1)
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
            'hoursByYear' => $this->getHours(),
        ];
    }
}
