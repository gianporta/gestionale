<?php
declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\WikiResource\Pages;
use App\Helpers\DBHelper;
use App\Helpers\FormHelper;
use App\Helpers\TableHelper;
use App\Models\Wiki;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\View;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class WikiResource extends Resource
{
    protected static ?string $model = Wiki::class;
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'Utility';

    public static function getModelLabel(): string
    {
        return 'Wiki';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Wiki';
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
        $columns = DBHelper::getTableColumns((new Wiki())->getTable());
        $tableColumns = TableHelper::getColumns($columns, 'wiki');

        return $table
->modifyQueryUsing(function (Builder $query, $livewire) {
                TableHelper::setFullSearch($livewire->tableSearch ?? null);
                return $query;
            })
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
        $columns = DBHelper::getTableColumns((new Wiki())->getTable());
        $formSchema = FormHelper::getFieldForm($columns);

        return $form->schema([
            Grid::make(2)
                ->schema([

                    Grid::make(1)->schema([

                        Section::make('Generale')
                            ->collapsible()
                            ->collapsed()
                            ->schema([
                                $formSchema['categoria'] ?? null,
                                $formSchema['problema'] ?? null,
                                $formSchema['link'] ?? null,
                                $formSchema['attivo'] ?? null,
                            ]),

                        Section::make('Comando')
                            ->collapsible()
                            ->collapsed()
                            ->schema([
                                $formSchema['comando'] ?? null,
                            ]),

                        Section::make('SQL')
                            ->collapsible()
                            ->collapsed()
                            ->schema([
                                $formSchema['sql'] ?? null,
                            ]),

                        Section::make('Note')
                            ->collapsible()
                            ->collapsed()
                            ->schema([
                                $formSchema['note'] ?? null,
                            ]),

                    ])->columnSpan(1),

                    Grid::make(1)->schema([

                        Section::make('Info')
                            ->schema([
                                View::make('filament.wiki.info')
                                    ->viewData(fn($record) => [
                                        'record' => $record,
                                    ]),
                            ]),

                    ])->columnSpan(1),

                ])
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
