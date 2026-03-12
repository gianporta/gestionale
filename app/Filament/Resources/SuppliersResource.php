<?php
declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\SuppliersResource\Pages;
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

class SuppliersResource extends Resource
{
    protected static ?string $model = Customer::class;
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
            ->where('tipo_cliente', 1);
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
        $formSchema = [];

        foreach ($columns as $column) {
            if (in_array($column, FormHelper::getExcludedColumns()))
                continue;

            $config = FormHelper::getFormFieldConfig($column);

            switch ($config['type']) {

                case 'multiselect':
                    $field = Forms\Components\Select::make($column)
                        ->label(ucfirst(str_replace('_', ' ', $column)))
                        ->options($config['options'])
                        ->multiple()
                        ->searchable()
                        ->required(false);

                    if (isset($config['default']))
                        $field->default([$config['default']]);

                    $formSchema[] = $field;
                    break;
                case 'select':
                    $field = forms\components\select::make($column)
                        ->label(ucfirst(str_replace('_', ' ', $column)))
                        ->options($config['options'])
                        ->searchable()
                        ->required(false);
                    if (isset($config['default']))
                        $field->default($config['default']);
                    $formSchema[] = $field;
                    break;
                case 'datetime':
                    $formSchema[] = Forms\Components\DateTimePicker::make($column)
                        ->label(ucfirst(str_replace('_', ' ', $column)))
                        ->required(false);
                    break;
                case 'date':
                    $field = forms\components\datePicker::make($column)
                        ->label(ucfirst(str_replace('_', ' ', $column)))
                        ->required(false);
                    if (isset($config['default']))
                        $field->default($config['default']);
                    $formSchema[] = $field;
                    break;
                case 'password':
                    $formSchema[] = Forms\Components\TextInput::make($column)
                        ->label(ucfirst(str_replace('_', ' ', $column)))
                        ->password()
                        ->required(false);
                    break;

                case 'textarea':
                    $field = Forms\Components\Textarea::make($column)
                        ->label(ucfirst(str_replace('_', ' ', $column)))
                        ->rows(4)
                        ->required(false);

                    if (isset($config['default']))
                        $field->default($config['default']);

                    $formSchema[] = $field;
                    break;
                case 'text':
                default:
                    $field = Forms\Components\TextInput::make($column)
                        ->label(ucfirst(str_replace('_', ' ', $column)))
                        ->required(false);
                    if (isset($config['default']))
                        $field->default($config['default']);
                    $formSchema[] = $field;
                    break;
            }
        }

        return $form->schema($formSchema);
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
