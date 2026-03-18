<?php

namespace App\Filament\Resources\SuppliersResource\RelationManagers;

use App\Helpers\DBHelper;
use App\Helpers\FormHelper;
use App\Helpers\TableHelper;
use App\Models\Job;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\CreateAction;
use Illuminate\Support\Collection;

class JobsRelationManager extends RelationManager
{
    protected static string $relationship = 'jobs';

    public function form(Form $form): Form
    {
        $columns = DBHelper::getTableColumns((new Job())->getTable());
        $formSchema = FormHelper::getFieldForm($columns,'job_suppliers');

        return $form->schema([
            Section::make('Job')
                ->schema(array_filter($formSchema, fn($k) => in_array($k, [
                    'nome',
                    'descrizione',
                    'stato_job',
                    'attivo'
                ]), ARRAY_FILTER_USE_KEY))
                ->columns(2),

            Section::make('Costi')
                ->schema(array_filter($formSchema, fn($k) => in_array($k, [
                    'costo',
                    'iva',
                ]), ARRAY_FILTER_USE_KEY))
                ->columns(2),
        ]);
    }

    public function table(Table $table): Table
    {
        $columns = DBHelper::getTableColumns((new Job())->getTable());
        $tableColumns = TableHelper::getColumns($columns,'job_suppliers');

        return $table
            ->columns($tableColumns)
            ->defaultSort('id', 'desc')
            ->filters([])
            ->headerActions([
                CreateAction::make()
                    ->label('Nuovo')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['cliente'] = $this->getOwnerRecord()->id;
                        return $data;
                    }),
            ])
            ->actions(TableHelper::getTableActions())
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('duplicate')
                        ->label('Duplica selezionati')
                        ->icon('heroicon-o-document-duplicate')
                        ->action(function (Collection $records) {

                            foreach ($records as $record) {
                                $new = $record->replicate();
                                $new->save();
                            }
                        }),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
