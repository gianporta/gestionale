<?php

namespace App\Filament\Resources\PackVsHoursResource\Pages;

use App\Filament\Resources\PackVsHoursResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class Listing extends ListRecords
{
    protected static string $resource = PackVsHoursResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
