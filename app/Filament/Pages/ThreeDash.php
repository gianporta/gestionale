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
        return Packages::query()
            ->join('customers', 'customers.id', '=', 'packages.cliente_id')
            ->where('packages.attivo', 1)
            ->where(function ($q) {
                $q->whereColumn('packages.ore', '!=', 'packages.totale_ore_lavorate')
                    ->orWhere('packages.saldato', 0);
            })
            ->select(
                'packages.id',
                'packages.nome',
                'packages.ore',
                'customers.ragione_sociale as cliente',
                DB::raw('COALESCE(packages.totale_ore_lavorate, 0) as ore_usate'),
                DB::raw('(packages.ore - COALESCE(packages.totale_ore_lavorate, 0)) as ore_rimaste')
            )
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
                    ->joinSub(
                        DB::table('hours')
                            ->selectRaw('MAX(id) as id, task_id')
                            ->where('stato', '!=', 3)
                            ->groupBy('task_id'),
                        'hmax',
                        'hmax.task_id',
                        '=',
                        'tasks.id'
                    )
                    ->join('hours', 'hours.id', '=', 'hmax.id')
                    ->join('packages', 'packages.id', '=', 'hours.packages_id')
                    ->join('customers', 'customers.id', '=', 'tasks.cliente_id')
                    ->leftJoin('users', 'users.id', '=', 'hours.user')
                    ->leftJoin('stato_tasks', 'stato_tasks.id', '=', 'hours.stato')
                    ->where('tasks.attivo', 1)
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
                    ->label('Data')
                    ->date()
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
