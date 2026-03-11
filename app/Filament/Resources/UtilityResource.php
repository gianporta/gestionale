<?php
declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\UtilityResource\Pages;
use App\Helpers\DBHelper;
use App\Helpers\FormHelper;
use App\Models\Utility;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;

class UtilityResource extends Resource
{
    protected static ?string $model = Utility::class;
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationGroup = 'Utility';
    public static function getModelLabel(): string
    {
        return 'Utility';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Utiliies';
    }

    public static function form(Form $form): Form
    {
        $columns = DBHelper::getTableColumns((new Utility())->getTable());
        $formSchema = [];

        foreach ($columns as $column) {
            if (in_array($column, FormHelper::getExcludedColumns()))
                continue;

            $config = FormHelper::getFormFieldConfig($column);

            switch ($config['type']) {

                case 'select':
                    $formSchema[] = Forms\Components\Select::make($column)
                        ->label(ucfirst(str_replace('_', ' ', $column)))
                        ->options($config['options'])->searchable()
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
            'index' => Pages\Utilities::route('/'),
        ];
    }
}
