<?php

namespace App\Filament\Resources\CondizioniPagamentoResource\Pages;

use App\Filament\Resources\CondizioniPagamentoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class Listing extends ListRecords
{
    protected static string $resource = CondizioniPagamentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
