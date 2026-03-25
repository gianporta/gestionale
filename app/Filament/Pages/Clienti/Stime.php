<?php

namespace App\Filament\Pages\Clienti;

use App\Models\Hours;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class Stime extends Page
{
    protected static ?string $slug = 'clienti/stime';
    protected static string $view = 'filament.pages.clienti.stime';
    protected static bool $isLazy = false;
    protected static bool $shouldRegisterNavigation = false;

    public function getTitle(): string
    {
        return 'Stime';
    }

    protected function getClienteId()
    {
        return auth()->user()->cliente_id;
    }

    public function getStimeTasks()
    {
        $clienteId = $this->getClienteId();

        $lastHoursSubquery = DB::table('hours as h1')
            ->select('h1.task_id', 'h1.stato')
            ->join(
                DB::raw('(SELECT task_id, MAX(id) as max_id FROM hours GROUP BY task_id) as h2'),
                function ($join) {
                    $join->on('h1.task_id', '=', 'h2.task_id')
                        ->on('h1.id', '=', 'h2.max_id');
                }
            );

        return Hours::query()
            ->join('tasks', 'tasks.id', '=', 'hours.task_id')
            ->join('packages', 'packages.id', '=', 'tasks.pacchetto_id')
            ->leftJoin('stimes', 'stimes.id', '=', 'tasks.stima')
            ->joinSub($lastHoursSubquery, 'last_hours', function ($join) {
                $join->on('last_hours.task_id', '=', 'tasks.id');
            })
            ->leftJoin('stato_tasks', 'stato_tasks.id', '=', 'last_hours.stato')
            ->where('packages.cliente_id', $clienteId)
            ->where('packages.attivo', 1)
            ->where('hours.stato', '!=', 3)
            ->select(
                'tasks.id',
                'tasks.task',
                'stimes.nome as stima',
                'stato_tasks.nome as stato_nome',
                'stato_tasks.style as stato_style',
                'totale_ore_lavorate'
            )
            ->groupBy(
                'tasks.id',
                'tasks.task',
                'stimes.nome',
                'stato_tasks.nome',
                'stato_tasks.style'
            )
            ->orderBy('tasks.task')
            ->get();
    }

    protected function getViewData(): array
    {
        return [
            'stimeTasks' => $this->getStimeTasks(),
        ];
    }
}
