<?php
declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Helpers\DBHelper;
use App\Helpers\TableHelper;
use App\Helpers\FormHelper;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'Sistema Utenti';
    public static function getModelLabel(): string
    {
        return 'Utente';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Utenti';
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->hasAnyRole('admin');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasAnyRole('admin');
    }
    public static function table(Table $table): Table
    {
        $columns = DBHelper::getTableColumns((new User())->getTable());
        $tableColumns = TableHelper::getColumns($columns,'user');
        $tableColumns[] = TextColumn::make('roles.name')
            ->label('Ruolo')
            ->badge();
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
        $columns = DBHelper::getTableColumns((new User())->getTable());
        $formSchema = FormHelper::getFieldForm($columns);
        $formSchema[] = Select::make('roles')
            ->label('Ruoli')
            ->multiple()
            ->relationship('roles', 'name')
            ->preload();

        return $form->schema($formSchema);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\Listing::route('/'),
        ];
    }
}
