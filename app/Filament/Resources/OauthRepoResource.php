<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OauthRepoResource\Pages;
use App\Filament\Resources\OauthRepoResource\RelationManagers;
use App\Helpers\DBHelper;
use App\Helpers\FormHelper;
use App\Helpers\TableHelper;
use App\Models\OauthRepo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OauthRepoResource extends Resource
{
    protected static ?string $model = OauthRepo::class;

    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';

    public static function getModelLabel(): string
    {
        return 'Auth Repo';
    }

    /**
     * Nome plurale della risorsa (per il menu e altre visualizzazioni).
     */
    public static function getPluralModelLabel(): string
    {
        return 'Auth Repo';
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function table(Table $table): Table
    {
        $columns = DBHelper::getTableColumns((new OauthRepo())->getTable());
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
        $columns = DBHelper::getTableColumns((new OauthRepo())->getTable());
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
            'index' => Pages\ListOauthRepo::route('/'),
            'create' => Pages\CreateOauthRepo::route('/create'),
            'edit' => Pages\EditOauthRepo::route('/{record}/edit'),
        ];
    }
}
