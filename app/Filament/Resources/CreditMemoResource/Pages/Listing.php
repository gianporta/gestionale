<?php

namespace App\Filament\Resources\CreditMemoResource\Pages;

use App\Filament\Resources\CreditMemoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class Listing extends ListRecords
{
    protected static string $resource = CreditMemoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
