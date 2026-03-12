<?php
declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\UtilityResource\Pages;
use Filament\Resources\Resource;

class UtilityResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationGroup = 'Utility';

    public static function getModelLabel(): string
    {
        return 'Utility';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Utilities';
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->hasAnyRole(['admin', 'threecommerce']);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasAnyRole(['admin', 'threecommerce']);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\Utilities::route('/'),
        ];
    }
}
