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
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;
class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'Sistema';
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
    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole('admin');
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
                    $field = Forms\Components\Select::make($column)
                        ->label(ucfirst(str_replace('_', ' ', $column)))
                        ->options($config['options'])
                        ->searchable()
                        ->required(false);
                    if (isset($config['default']))
                        $field->default($config['default']);
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
                        ->revealable()
                        ->placeholder('Lascia vuoto per non modificarla')
                        ->formatStateUsing(fn () => '')
                        ->dehydrated(fn ($state) => filled($state))
                        ->dehydrateStateUsing(fn ($state) => md5($state))
                        ->required(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord);
                    break;
                case 'text':
                default:
                    $formSchema[] = Forms\Components\TextInput::make($column)
                        ->label(ucfirst(str_replace('_', ' ', $column)))
                        ->required(false);
                    break;
            }
        }
        $formSchema[] = Forms\Components\Select::make('roles')
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
            'create' => Pages\Create::route('/create'),
            'edit' => Pages\Edit::route('/{record}/edit'),
        ];
    }
}
