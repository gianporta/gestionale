<?php
declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Helpers\DBHelper;
use App\Helpers\TableHelper;
use App\Helpers\FormHelper;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function getModelLabel(): string
    {
        return 'Utente';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Utenti';
    }

    public static function table(Table $table): Table
    {
        $columns = DBHelper::getTableColumns((new User())->getTable());
        $tableColumns = [];

        foreach ($columns as $column) {
            if (in_array($column, TableHelper::getExcludedColumns()))
                continue;
            $tableColumns[] = TextColumn::make($column)
                ->label(ucfirst(str_replace('_', ' ', $column)))
                ->sortable()
                ->formatStateUsing(fn($state) => TableHelper::formatColumnValue($column, $state));
        }

        return $table
            ->columns($tableColumns)
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function form(Form $form): Form
    {
        $columns = DBHelper::getTableColumns((new User())->getTable());
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
            'index' => Pages\ListUser::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
