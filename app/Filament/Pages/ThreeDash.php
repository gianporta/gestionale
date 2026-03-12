<?php

namespace App\Filament\Pages;

use App\Models\Packages;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Models\Task;
use App\Helpers\TableHelper;
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

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Task::query()
                    ->where('stato', '!=', 3)
            )
            ->columns([
                TextColumn::make('cliente.ragione_sociale')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('task')
                    ->label('Task')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Utente')
                    ->sortable(),

                TextColumn::make('data_lavorazione')
                    ->label('Data')
                    ->date()
                    ->sortable(),

                TextColumn::make('ore_lavorate')
                    ->label('Ore')
                    ->sortable(),

                TextColumn::make('stato')
                    ->badge()
                    ->formatStateUsing(fn ($state) => TableHelper::getSstatusOptions()[$state] ?? $state)
            ])
            ->filters([
                SelectFilter::make('stato')
                    ->options([
                        1 => 'In lavorazione',
                        2 => 'Da testare',
                    ]),
            ])
            ->defaultSort('data_lavorazione', 'desc')
            ->paginated([10, 25, 50]);
    }
}
