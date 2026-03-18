<?php

namespace App\Filament\Resources\StatoTaskResource\Pages;

use App\Filament\Resources\StatoTaskResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class Listing extends ListRecords
{
    protected static string $resource = StatoTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
