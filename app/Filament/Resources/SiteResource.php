<?php
declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\SiteResource\Pages;
use App\Helpers\DBHelper;
use App\Helpers\FormHelper;
use App\Helpers\TableHelper;
use App\Models\Site;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\View;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class SiteResource extends Resource
{
    protected static ?string $model = Site::class;
    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'Utility';

    public static function getModelLabel(): string
    {
        return 'Sito';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Siti';
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
        $columns = DBHelper::getTableColumns((new Site())->getTable());
        $tableColumns = TableHelper::getColumns($columns, 'siti');

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
        $columns = DBHelper::getTableColumns((new Site())->getTable());
        $formSchema = FormHelper::getFieldForm($columns);

        return $form->schema([
            Grid::make(2)
                ->schema([
                    Grid::make(1)->schema([
                        Section::make('Generale')
                            ->collapsible()
                            ->collapsed()
                            ->schema([
                                $formSchema['brand'] ?? null,
                                $formSchema['cliente_id'] ?? null,
                                $formSchema['ambiente'] ?? null,
                                $formSchema['url'] ?? null,
                            ]),
                        Section::make('Accesso Web')
                            ->collapsible()
                            ->collapsed()
                            ->schema([
                                $formSchema['admin_url'] ?? null,
                                $formSchema['admin_user'] ?? null,
                                $formSchema['admin_psw'] ?? null,
                                $formSchema['http_user'] ?? null,
                                $formSchema['http_psw'] ?? null,
                            ]),
                        Section::make('Cpanel')
                            ->collapsible()
                            ->collapsed()
                            ->schema([
                                $formSchema['cpanel_url'] ?? null,
                                $formSchema['cpanel_user'] ?? null,
                                $formSchema['cpanel_psw'] ?? null,
                            ]),
                        Section::make('Accesso Server')
                            ->collapsible()
                            ->collapsed()
                            ->schema([
                                $formSchema['ssh_host'] ?? null,
                                $formSchema['ssh_user'] ?? null,
                                $formSchema['ssh_psw'] ?? null,
                                $formSchema['ssh_super_user'] ?? null,
                                $formSchema['ssh_super_psw'] ?? null,
                                $formSchema['ssh_port'] ?? null,
                                $formSchema['ssh_key_name'] ?? null,
                                $formSchema['ssh_key'] ?? null,
                                $formSchema['base_dir'] ?? null,
                            ]),
                        Section::make('Database')
                            ->collapsible()
                            ->collapsed()
                            ->schema([
                                $formSchema['db_host'] ?? null,
                                $formSchema['db_name'] ?? null,
                                $formSchema['db_user'] ?? null,
                                $formSchema['db_psw'] ?? null,
                                $formSchema['db_port'] ?? null,
                                $formSchema['tunnel_ssh'] ?? null,
                                $formSchema['local_port'] ?? null,
                            ]),
                        Section::make('Tecnologie')
                            ->collapsible()
                            ->collapsed()
                            ->schema([
                                $formSchema['cms'] ?? null,
                                $formSchema['cms_version'] ?? null,
                                $formSchema['php_version'] ?? null,
                                $formSchema['mysql_version'] ?? null,
                                $formSchema['redis_version'] ?? null,
                                $formSchema['composer_version'] ?? null,
                                $formSchema['elasticsearch_version'] ?? null,
                            ]),
                        Section::make('Integrazioni')
                            ->collapsible()
                            ->collapsed()
                            ->schema([
                                $formSchema['sucuri'] ?? null,
                                $formSchema['varnish'] ?? null,
                                $formSchema['opcache'] ?? null,
                                $formSchema['redis'] ?? null,
                                $formSchema['cloudflare'] ?? null,
                                $formSchema['enable_ip'] ?? null,
                                $formSchema['trello'] ?? null,
                                $formSchema['clickup'] ?? null,
                            ]),
                        Section::make('VPN')
                            ->collapsible()
                            ->collapsed()
                            ->schema([
                                $formSchema['vpn'] ?? null,
                                $formSchema['vpn_name'] ?? null,
                                $formSchema['vpn_host'] ?? null,
                                $formSchema['vpn_user'] ?? null,
                                $formSchema['vpn_psw'] ?? null,
                                $formSchema['vpn_port'] ?? null,
                            ]),
                        Section::make('Altro')
                            ->collapsible()
                            ->collapsed()
                            ->schema([
                                $formSchema['note'] ?? null,
                                $formSchema['attivo'] ?? null,
                            ]),
                    ])->columnSpan(1),
                    Grid::make(1)->schema([
                        Section::make('Ambiente')
                            ->schema([
                                Placeholder::make('environment')
                                    ->content(fn($record) => view('filament.components.environment-badge', [
                                        'ambiente' => $record?->ambiente
                                    ])),
                            ]),
                        Section::make('Info')
                            ->schema([
                                View::make('filament.sites.info')
                                    ->viewData(fn($record) => [
                                        'record' => $record,
                                    ]),
                            ])
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
