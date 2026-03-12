<?php
declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Helpers\DBHelper;
use App\Helpers\FormHelper;
use App\Helpers\TableHelper;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Section;
class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'Anagrafiche';

    public static function getModelLabel(): string
    {
        return 'Cliente';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Clienti';
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
            ->where('tipo_cliente', 2);
    }

    public static function table(Table $table): Table
    {
        $columns = DBHelper::getTableColumns((new Customer())->getTable());
        $tableColumns = [];

        foreach ($columns as $column) {
            if (in_array($column, TableHelper::getExcludedColumns()))
                continue;

            $tableColumns[] = TextColumn::make($column)
                ->label(ucfirst(str_replace('_', ' ', $column)))
                ->sortable()
                ->searchable()
                ->formatStateUsing(fn($state) => TableHelper::formatColumnValue($column, $state))
                ->extraAttributes([
                    'style' => 'max-width:250px; overflow-x:auto; white-space:nowrap;'
                ]);
        }

        return $table
            ->columns($tableColumns)
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([

                    Tables\Actions\BulkAction::make('duplicate')
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

                    Tables\Actions\DeleteBulkAction::make(),

                ]),
            ]);
    }

    public static function form(Form $form): Form
    {
        $columns = DBHelper::getTableColumns((new Customer())->getTable());
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
            $formSchema['attivo'] ?? null,
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
