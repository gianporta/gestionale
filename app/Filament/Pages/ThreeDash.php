<?php

namespace App\Filament\Pages;

use App\Models\Packages;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Models\Task;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class ThreeDash extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $view = 'filament.pages.three-dash';
    protected static bool $shouldRegisterNavigation = false;

    public static function canAccess(): bool
    {
        return auth()->user()->hasAnyRole(['admin', 'threecommerce']);
    }
    public function getOpenPackages()
    {
        $hoursPerUser = DB::table('hours')
            ->join('users', 'users.id', '=', 'hours.user')
            ->select(
                'hours.packages_id',
                DB::raw("CONCAT(users.name, ': ', ROUND(SUM(hours.ore_lavorate),2)) as label")
            )
            ->groupBy('hours.packages_id', 'hours.user');

        return Packages::query()
            ->join('customers', 'customers.id', '=', 'packages.cliente_id')
            ->leftJoinSub(
                DB::table(DB::raw("({$hoursPerUser->toSql()}) as hpu"))
                    ->mergeBindings($hoursPerUser),
                'hpu',
                'hpu.packages_id',
                '=',
                'packages.id'
            )
            ->where('packages.attivo', 1)
            ->where(function ($q) {
                $q->whereColumn('packages.ore', '!=', 'packages.totale_ore_lavorate')
                    ->orWhere('packages.saldato', 0);
            })
            ->select(
                'packages.id',
                'packages.nome',
                'packages.ore',
                'packages.costo_orario',
                'packages.proforma',
                'packages.fatturato',
                'packages.saldato',
                'customers.ragione_sociale as cliente',
                DB::raw('COALESCE(packages.totale_ore_lavorate, 0) as ore_usate'),
                DB::raw('(packages.ore - COALESCE(packages.totale_ore_lavorate, 0)) as ore_rimaste'),
                DB::raw('GROUP_CONCAT(hpu.label SEPARATOR " | ") as ore_per_user')
            )
            ->groupBy('packages.id')
            ->orderBy('customers.ragione_sociale')
            ->get();
    }

    protected function getViewData(): array
    {
        return [
            'packages' => $this->getOpenPackages()
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Task::query()
                    ->join('customers', 'customers.id', '=', 'tasks.cliente_id')
                    ->leftJoin('hours', 'hours.task_id', '=', 'tasks.id')
                    ->leftJoin('packages', 'packages.id', '=', 'hours.packages_id')
                    ->leftJoin('users', 'users.id', '=', 'hours.user')
                    ->leftJoin('stato_tasks', 'stato_tasks.id', '=', 'hours.stato')
                    ->where('tasks.attivo', 1)
                    ->whereNotExists(function ($q) {
                        $q->select(DB::raw(1))
                            ->from('hours as h2')
                            ->join('stato_tasks as st2', 'st2.id', '=', 'h2.stato')
                            ->whereColumn('h2.task_id', 'tasks.id')
                            ->whereIn('st2.nome', ['Finito', 'Rilascio']);
                    })
                    ->select(
                        'tasks.id',
                        'tasks.task',
                        'customers.ragione_sociale as cliente',
                        'users.name as utente',
                        'hours.data_lavorazione',
                        'hours.ore_lavorate',
                        'stato_tasks.nome as stato_nome',
                        'stato_tasks.style as stato_style'
                    )
            )
            ->columns([
                TextColumn::make('cliente')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('task')
                    ->label('Task')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('utente')
                    ->label('Utente')
                    ->sortable(),

                TextColumn::make('data_lavorazione')
                    ->label('Data lavorazione')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('ore_lavorate')
                    ->label('Ore')
                    ->sortable(),

                TextColumn::make('stato_nome')
                    ->label('Stato')
                    ->badge()
                    ->color(fn ($record) => $record->stato_style ?? 'gray')
            ])
            ->filters([
                SelectFilter::make('stato')
                    ->options(
                        DB::table('stato_tasks')
                            ->pluck('nome', 'id')
                            ->toArray()
                    )
                    ->query(function ($query, $data) {
                        if (empty($data['value']))
                            return $query;

                        return $query->where('hours.stato', $data['value']);
                    }),
            ])
            ->defaultSort('data_lavorazione', 'desc')
            ->paginated([10, 25, 50]);
    }
}
