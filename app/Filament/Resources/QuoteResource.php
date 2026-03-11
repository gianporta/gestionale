<?php
declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\QuoteResource\Pages;
use App\Helpers\DBHelper;
use App\Helpers\FormHelper;
use App\Helpers\TableHelper;
use App\Models\Quote;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class QuoteResource extends Resource
{
    protected static ?string $model = Quote::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'Documenti';

    public static function getModelLabel(): string
    {
        return 'Preventivo';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Preventivi';
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
        $columns = DBHelper::getTableColumns((new Quote())->getTable());
        $tableColumns = [];

        foreach ($columns as $column) {
            if (in_array($column, TableHelper::getExcludedColumns()))
                continue;

            $col = TextColumn::make($column)
                ->label(ucfirst(str_replace('_', ' ', $column)))
                ->sortable()
                ->searchable()
                ->formatStateUsing(fn($state) => TableHelper::formatColumnValue($column, $state))
                ->extraAttributes([
                    'style' => 'max-width:250px; overflow-x:auto; white-space:nowrap;'
                ]);

            TableHelper::decorateColumn($column, $col);

            $tableColumns[] = $col;
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
        $columns = DBHelper::getTableColumns((new Quote())->getTable());
        $formSchema = [];

        foreach ($columns as $column) {
            if (in_array($column, FormHelper::getExcludedColumns()))
                continue;

            $config = FormHelper::getFormFieldConfig($column);

            switch ($config['type']) {

                case 'select':
                    $formSchema[] = Forms\Components\Select::make($column)
                        ->label(ucfirst(str_replace('_', ' ', $column)))
                        ->options($config['options'])
                        ->required(false);
                    break;

                case 'datetime':
                    $formSchema[] = Forms\Components\DateTimePicker::make($column)
                        ->label(ucfirst(str_replace('_', ' ', $column)))
                        ->required(false);
                    break;

                case 'password':
                    $formSchema[] = Forms\Components\TextInput::make($column)
                        ->label(ucfirst(str_replace('_', ' ', $column)))
                        ->password()
                        ->required(false);
                    break;

                case 'text':
                default:
                    $formSchema[] = Forms\Components\TextInput::make($column)
                        ->label(ucfirst(str_replace('_', ' ', $column)))
                        ->required(false);
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
