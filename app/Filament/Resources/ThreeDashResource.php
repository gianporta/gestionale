<?php
declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ThreeDashResource\Pages;
use App\Models\Three;
use Filament\Resources\Resource;

class ThreeDashResource extends Resource
{
    protected static ?string $model = Utility::class;
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'Utility';
    public static function getModelLabel(): string
    {
        return 'Dash';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Dash';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\Dash::route('/'),
        ];
    }
}
