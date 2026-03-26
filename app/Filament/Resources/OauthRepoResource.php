<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OauthRepoResource\Pages;
use App\Filament\Resources\OauthRepoResource\RelationManagers;
use App\Helpers\DBHelper;
use App\Helpers\FormHelper;
use App\Helpers\TableHelper;
use App\Models\OauthRepo;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class OauthRepoResource extends Resource
{
    protected static ?string $model = OauthRepo::class;

    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationGroup = 'Repository';

    public static function getModelLabel(): string
    {
        return 'Auth Repo';
    }
    public static function getPluralModelLabel(): string
    {
        return 'Auth Repo';
    }
    public static function canViewAny(): bool
    {
        return auth()->user()->hasAnyRole(['admin', 'threecommerce']);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasAnyRole(['admin', 'threecommerce']);
    }

    public static function table(Table $table): Table
    {
        $columns = DBHelper::getTableColumns((new OauthRepo())->getTable());
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
        $columns = DBHelper::getTableColumns((new OauthRepo())->getTable());
        $formSchema = FormHelper::getFieldForm($columns);
        return $form->schema($formSchema);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\Listing::route('/'),
        ];
    }
}
