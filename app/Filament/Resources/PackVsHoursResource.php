<?php
declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\PackVsHoursResource\Pages;
use App\Helpers\DBHelper;
use App\Helpers\FormHelper;
use App\Helpers\TableHelper;
use App\Models\PackVsHours;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use App\Models\Packages;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
class PackVsHoursResource extends Resource
{
    protected static ?string $model = PackVsHours::class;
    protected static ?string $navigationIcon = 'heroicon-o-link';
    protected static ?int $navigationSort = 6;
    protected static ?string $navigationGroup = 'Threecommerce';

    public static function getModelLabel(): string
    {
        return 'PackVsHours';
    }

    public static function getPluralModelLabel(): string
    {
        return 'PackVsHours';
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->hasAnyRole('admin');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasAnyRole('admin');
    }
    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        $columns = DBHelper::getTableColumns((new PackVsHours())->getTable());
        $tableColumns = TableHelper::getColumns($columns,'pack_vs_hours');

        return $table
->modifyQueryUsing(function (Builder $query, $livewire) {
                TableHelper::setFullSearch($livewire->tableSearch ?? null);
                return $query;
            })
            ->modifyQueryUsing(fn ($query) => $query->whereNull('packages_id'))
            ->columns($tableColumns)
            ->filters([])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('assegna_pacchetto')
                        ->label('Assegna pacchetto')
                        ->icon('heroicon-o-link')
                        ->form([
                            Select::make('package_id')
                                ->label('Pacchetto')
                                ->options(
                                    Packages::query()
                                        ->join('customers', 'customers.id', '=', 'packages.cliente_id')
                                        ->whereRaw('packages.totale_ore_lavorate < packages.ore')
                                        ->selectRaw("packages.id, CONCAT(customers.ragione_sociale, ' - ', packages.nome) as label")
                                        ->pluck('label', 'packages.id')
                                )
                                ->required()
                        ])
                        ->action(function (Collection $records, array $data) {
                            $packageId = $data['package_id'];
                            foreach ($records as $record)
                                $record->update([
                                    'packages_id' => $packageId
                                ]);
                            $totale = DB::table('hours')
                                ->where('packages_id', $packageId)
                                ->sum(DB::raw("CAST(REPLACE(ore_lavorate, ',', '.') AS DECIMAL(10,2))"));
                            Packages::where('id', $packageId)
                                ->update([
                                    'totale_ore_lavorate' => $totale
                                ]);
                        }),
                ])
            ]);
    }

    public static function form(Form $form): Form
    {
        $columns = DBHelper::getTableColumns((new PackVsHours())->getTable());
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
