<?php
declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ProformaResource\Pages;
use App\Helpers\DBHelper;
use App\Helpers\FormHelper;
use App\Helpers\TableHelper;
use App\Models\Proforma;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
class ProformaResource extends Resource
{
    protected static ?string $model = Proforma::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationGroup = 'Documenti';

    public static function getModelLabel(): string
    {
        return 'Proforma';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Proforma';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('tipo_documento', Proforma::TYPE_DOC);
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public static function table(Table $table): Table
    {
        $columns = DBHelper::getTableColumns((new Proforma())->getTable());
        $tableColumns = TableHelper::getColumns($columns, 'proforma');

        return $table
->modifyQueryUsing(function (Builder $query, $livewire) {
                TableHelper::setFullSearch($livewire->tableSearch ?? null);
                return $query;
            })
            ->columns($tableColumns)
            ->filters(TableHelper::getTableFilter())
            ->defaultSort('id', 'desc')
            ->actions(TableHelper::getTableActions())
            ->headerActions(TableHelper::getHeaderActions())
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('duplicate')
                        ->label('Duplica selezionati')
                        ->icon('heroicon-o-document-duplicate')
                        ->action(function (Collection $records) {
                            foreach ($records as $record) {
                                $new = $record->replicate();
                                if (isset($new->email))
                                    $new->email = $record->email . '.copy';
                                $new->save();
                            }
                        }),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function form(Form $form): Form
    {
        $columns = DBHelper::getTableColumns((new Proforma())->getTable());
        $formSchema = FormHelper::getFieldForm($columns);
        return $form->schema([
            Grid::make(2)->schema([
                Section::make('Documento')
                    ->schema(array_filter($formSchema, fn($k) => in_array($k, [
                        'data_documento',
                        'numero_documento',
                    ]), ARRAY_FILTER_USE_KEY))
                    ->columns(2)
                    ->columnSpan(1),
                Section::make('Dati Banca')
                    ->schema(array_filter($formSchema, fn($k) => in_array($k, [
                        'banca',
                        'iban',
                        'intestatario_conto',
                    ]), ARRAY_FILTER_USE_KEY))
                    ->columns(3)
                    ->columnSpan(1),
            ]),
            Section::make('Cliente')
                ->schema(array_filter($formSchema, fn($k) => in_array($k, [
                    'cliente',
                ]), ARRAY_FILTER_USE_KEY))
                ->columns(1),
            Section::make('Attività')
                ->schema(array_filter($formSchema, fn($k) => in_array($k, [
                    'content',
                ]), ARRAY_FILTER_USE_KEY))
                ->columns(1),
            Grid::make(2)->schema([
                Grid::make(1)->schema([
                    Section::make('Pagamento')
                        ->schema(array_filter($formSchema, fn($k) => in_array($k, [
                            'stato_documento',
                            'document_to_state',
                            'condizioni_pagamento',
                            'modalita_pagamento',
                            'pagato',
                            'data_pagamento',
                            'data_scadenza',
                        ]), ARRAY_FILTER_USE_KEY))
                        ->columns(2),
                    Section::make('Condizioni di vendita')
                        ->schema(array_filter($formSchema, fn($k) => in_array($k, [
                            'mostra_inps',
                            'mostra_ritenuta',
                            'somma_inps',
                        ]), ARRAY_FILTER_USE_KEY))
                        ->columns(3),
                    Section::make('Note')
                        ->schema(array_filter($formSchema, fn($k) => in_array($k, [
                            'frase_in_calce',
                        ]), ARRAY_FILTER_USE_KEY))
                        ->columns(1),

                ])->columnSpan(1),
                Grid::make(1)->schema([
                    Section::make('Importi')
                        ->schema(array_filter($formSchema, fn($k) => in_array($k, [
                            'imponibile',
                            'contributo_inps',
                            'ritenuta_di_acconto',
                            'iva',
                            'netto_a_pagare',
                        ]), ARRAY_FILTER_USE_KEY))
                        ->columns(1),
                ])->columnSpan(1),
            ]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\Listing::route('/'),
            'create' => Pages\Create::route('/create'),
            'edit' => Pages\Edit::route('/{record}/edit'),
        ];
    }
}
