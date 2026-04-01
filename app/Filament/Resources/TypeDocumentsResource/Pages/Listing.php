<?php

namespace App\Filament\Resources\TypeDocumentsResource\Pages;

use App\Filament\Resources\TypeDocumentsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class Listing extends ListRecords
{
    protected static string $resource = TypeDocumentsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
