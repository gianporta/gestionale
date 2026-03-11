<?php

namespace App\Filament\Resources\AcquistiResource\Pages;

use App\Filament\Resources\AcquistiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class Listing extends ListRecords
{
    protected static string $resource = AcquistiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
