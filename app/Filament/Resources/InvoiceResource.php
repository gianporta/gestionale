<?php
declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Helpers\DBHelper;
use App\Helpers\FormHelper;
use App\Helpers\TableHelper;
use App\Models\Invoice;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;
    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';
    protected static ?int $navigationSort = 6;
    protected static ?string $navigationGroup = 'Documenti';

    public static function getModelLabel(): string
    {
        return 'Fattura';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Fatture';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('tipo_documento', Invoice::INVOICE_TYPE_DOC);
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
        $columns = DBHelper::getTableColumns((new Invoice())->getTable());
        $tableColumns = TableHelper::getColumns($columns,'invoice');

        return $table
            ->columns($tableColumns)
            ->filters(TableHelper::getTableFilter())
            ->defaultSort('id', 'desc')
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
        $columns = DBHelper::getTableColumns((new Invoice())->getTable());
        $formSchema = FormHelper::getFieldForm($columns);
        return $form->schema([
            Section::make('Documento')
                ->schema(array_filter($formSchema, fn($k) => in_array($k, [
                    'numero_documento',
                    'progressivo_sdi',
                    'data_documento'
                ]), ARRAY_FILTER_USE_KEY))
                ->columns(2),
            Section::make('Cliente')
                ->schema(array_filter($formSchema, fn($k) => in_array($k, [
                    'cliente',
                    'cliente_ragione_sociale',
                    'cliente_company_id',
                    'cliente_partita_iva',
                    'cliente_codice_fiscale',
                    'cliente_indirizzo',
                    'cliente_cap',
                    'cliente_citta',
                    'cliente_provincia',
                    'cliente_nazione'
                ]), ARRAY_FILTER_USE_KEY))
                ->columns(2),
            Section::make('Dati fiscali')
                ->schema(array_filter($formSchema, fn($k) => in_array($k, [
                    'contributo_inps',
                    'ritenuta_di_acconto',
                    'mostra_inps',
                    'mostra_ritenuta',
                    'somma_inps'
                ]), ARRAY_FILTER_USE_KEY))
                ->columns(2),
            Section::make('Importi')
                ->schema(array_filter($formSchema, fn($k) => in_array($k, [
                    'imponibile',
                    'iva',
                    'netto_a_pagare'
                ]), ARRAY_FILTER_USE_KEY))
                ->columns(3),
            Section::make('Pagamento')
                ->schema(array_filter($formSchema, fn($k) => in_array($k, [
                    'condizioni_pagamento',
                    'modalita_pagamento',
                    'banca',
                    'iban',
                    'intestatario_conto',
                    'anticipo',
                    'pagato',
                    'data_pagamento',
                    'data_scadenza'
                ]), ARRAY_FILTER_USE_KEY))
                ->columns(2),
            Section::make('Note')
                ->schema(array_filter($formSchema, fn($k) => in_array($k, [
                    'descrizione',
                    'frase_in_calce'
                ]), ARRAY_FILTER_USE_KEY))
                ->columns(1),
            Section::make('Sistema')
                ->schema(array_filter($formSchema, fn($k) => in_array($k, [
                    'stato_documento',
                    'document_to_state'
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
