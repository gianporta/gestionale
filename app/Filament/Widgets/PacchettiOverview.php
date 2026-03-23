<?php

namespace App\Filament\Widgets;

use App\Models\Packages;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\DB;

class PacchettiOverview extends BaseWidget
{
    protected int|string|array $columnSpan = 1;
    protected function getColumns(): int
    {
        return 4;
    }
    public function getDefaultTableRecordsPerPageSelectOption(): int
    {
        return 10;
    }

    public function getTableRecordsPerPageSelectOptions(): array
    {
        return [10, 25, 50];
    }
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Packages::query()
                    ->join('customers', 'customers.id', '=', 'packages.cliente_id')
                    ->leftJoin('tasks', 'tasks.pacchetto_id', '=', 'packages.id')
                    ->leftJoin('hours', 'hours.task_id', '=', 'tasks.id')
                    ->where('packages.attivo', 1)
                    ->select(
                        'packages.id',
                        'packages.nome',
                        'packages.ore',
                        'customers.ragione_sociale as cliente',
                        DB::raw('COALESCE(SUM(CAST(hours.ore_lavorate as DECIMAL(10,2))),0) as ore_usate'),
                        DB::raw('(packages.ore - COALESCE(SUM(CAST(hours.ore_lavorate as DECIMAL(10,2))),0)) as ore_rimaste')
                    )
                    ->groupBy(
                        'packages.id',
                        'packages.nome',
                        'packages.ore',
                        'customers.ragione_sociale'
                    )
            )
            ->columns([
                TextColumn::make('cliente')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nome')
                    ->label('Pacchetto')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('ore')
                    ->label('Ore totali')
                    ->sortable(),

                TextColumn::make('ore_usate')
                    ->label('Usate')
                    ->sortable(),

                TextColumn::make('ore_rimaste')
                    ->label('Rimaste')
                    ->sortable()
                    ->color(fn ($record) =>
                    $record->ore_rimaste <= 0 ? 'danger' :
                        ($record->ore_rimaste < ($record->ore * 0.3) ? 'warning' : 'success')
                    ),
            ])
            ->defaultSort('ore_rimaste', 'asc');
    }
}
