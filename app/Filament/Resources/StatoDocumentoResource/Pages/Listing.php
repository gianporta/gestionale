<?php

namespace App\Filament\Resources\StatoDocumentoResource\Pages;

use App\Filament\Resources\StatoDocumentoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class Listing extends ListRecords
{
    protected static string $resource = StatoDocumentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
