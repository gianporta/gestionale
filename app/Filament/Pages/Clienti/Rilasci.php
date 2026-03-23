<?php

namespace App\Filament\Pages\Clienti;

use App\Models\Hours;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use App\Models\StatoTask;
class Rilasci extends Page
{
    protected static ?string $slug = 'clienti/rilasci';
    protected static string $view = 'filament.pages.clienti.rilasci';
    protected static bool $isLazy = false;
    protected static bool $shouldRegisterNavigation = false;

    public function getTitle(): string
    {
        return 'Rilasci';
    }

    protected function getClienteId()
    {
        return auth()->user()->cliente_id;
    }

    public function getRilasci()
    {
        $clienteId = $this->getClienteId();

        $rilascioId = StatoTask::where('nome', 'rilascio')->value('id');

        return Hours::query()
            ->join('tasks', 'tasks.id', '=', 'hours.task_id')
            ->join('packages', 'packages.id', '=', 'tasks.pacchetto_id')
            ->where('packages.cliente_id', $clienteId)
            ->where('packages.attivo', 1)
            ->where('hours.stato', $rilascioId)
            ->select(
                'hours.data_lavorazione',
                'hours.descrizione',
                DB::raw('YEAR(hours.data_lavorazione) as anno')
            )
            ->orderByDesc('hours.data_lavorazione')
            ->get()
            ->groupBy('anno');
    }

    protected function getViewData(): array
    {
        return [
            'rilasci' => $this->getRilasci(),
        ];
    }
}
