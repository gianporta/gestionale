<?php
declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\SuppliersResource\Pages;
use App\Helpers\DBHelper;
use App\Helpers\FormHelper;
use App\Helpers\TableHelper;
use App\Models\Suppliers;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\SuppliersResource\RelationManagers\JobsRelationManager;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class SuppliersResource extends Resource
{
    protected static ?string $model = Suppliers::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'Anagrafiche';

    public static function getModelLabel(): string
    {
        return 'Fornitore';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Fornitori';
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->hasAnyRole(['admin', 'threecommerce']);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasAnyRole(['admin', 'threecommerce']);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('tipo_cliente', Suppliers::TYPE_CUSTOMER_SUPPLIERS);
    }
    public static function getRelations(): array
    {
        return [
            JobsRelationManager::class,
        ];
    }
    public static function table(Table $table): Table
    {
        $columns = DBHelper::getTableColumns((new Suppliers())->getTable());
        $tableColumns = TableHelper::getColumns($columns);

        return $table
            ->columns($tableColumns)
            ->filters([])
            ->actions(TableHelper::getTableActions())
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
        $columns = DBHelper::getTableColumns((new Suppliers())->getTable());
        $formSchema = FormHelper::getFieldForm($columns);
        return $form->schema([
            Section::make('Anagrafica')
                ->schema(array_filter($formSchema, fn($k) => in_array($k, [
                    'ragione_sociale',
                    'tipo_cliente',
                    'email'
                ]), ARRAY_FILTER_USE_KEY))
                ->columns(2),
            Section::make('Indirizzo')
                ->schema(array_filter($formSchema, fn($k) => in_array($k, [
                    'indirizzo',
                    'cap',
                    'citta',
                    'provincia',
                    'nazione'
                ]), ARRAY_FILTER_USE_KEY))
                ->columns(2),
            Section::make('Fatturazione')
                ->schema(array_filter($formSchema, fn($k) => in_array($k, [
                    'company_id',
                    'partita_iva',
                    'codice_fiscale',
                    'sdi',
                    'pec',
                    'iban',
                    'banca',
                    'intestatario_conto'
                ]), ARRAY_FILTER_USE_KEY))
                ->columns(2),
            Section::make('Contatti')
                ->schema(array_filter($formSchema, fn($k) => in_array($k, [
                    'telefono',
                    'cellulare',
                    'fax',
                    'sito_web'
                ]), ARRAY_FILTER_USE_KEY))
                ->columns(2),
            Section::make('Sistema')
                ->schema(array_filter($formSchema, fn($k) => in_array($k, [
                    'attivo',
                ]), ARRAY_FILTER_USE_KEY))
                ->columns(2),
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
