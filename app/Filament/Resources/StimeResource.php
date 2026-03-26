<?php
declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\StimeResource\Pages;
use App\Helpers\DBHelper;
use App\Helpers\FormHelper;
use App\Helpers\TableHelper;
use App\Models\Stime;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class StimeResource extends Resource
{
    protected static ?string $model = Stime::class;
    protected static ?string $navigationIcon = 'heroicon-o-calculator';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'Sistema Task';

    public static function getModelLabel(): string
    {
        return 'Stima';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Stime';
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
        $columns = DBHelper::getTableColumns((new Stime())->getTable());
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
        $columns = DBHelper::getTableColumns((new Stime())->getTable());
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
