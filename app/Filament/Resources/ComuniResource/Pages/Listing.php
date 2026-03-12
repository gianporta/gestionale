<?php

namespace App\Filament\Resources\ComuniResource\Pages;

use App\Filament\Resources\ComuniResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class Listing extends ListRecords
{
    protected static string $resource = ComuniResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
