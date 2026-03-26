<?php
declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Helpers\TableHelper;
use Filament\Tables;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Hours;
use App\Models\StatoTask;

class RilasciOverview extends TableWidget
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
        return TableHelper::getNumberRecordTable();
    }

    protected function getTableQuery(): Builder
    {
        $stato = StatoTask::where('nome', 'rilascio')->value('id');

        return Hours::query()
            ->from('hours as h')
            ->join('tasks as t', 't.id', '=', 'h.task_id')
            ->join('packages as p', 'p.id', '=', 'h.packages_id')
            ->join('customers as c', 'c.id', '=', 'p.cliente_id')
            ->where('h.stato', $stato)
            ->select(
                'h.id',
                'h.data_lavorazione as data',
                'c.ragione_sociale as cliente',
                't.task',
                'h.descrizione as desc'
            )
            ->orderBy('h.id', 'desc');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('data')
                ->label('Data')
                ->date('d/m/Y')
                ->sortable(),

            Tables\Columns\TextColumn::make('cliente')
                ->label('Cliente')
                ->searchable(),

            Tables\Columns\TextColumn::make('task')
                ->label('Task')
                ->searchable(),

            Tables\Columns\TextColumn::make('desc')
                ->label('Desc')
                ->limit(40),
        ];
    }

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'h.id'; // 🔥 evita errore hours.id
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }
}
