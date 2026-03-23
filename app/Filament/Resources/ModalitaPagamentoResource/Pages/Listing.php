<?php

namespace App\Filament\Resources\ModalitaPagamentoResource\Pages;

use App\Filament\Resources\ModalitaPagamentoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class Listing extends ListRecords
{
    protected static string $resource = ModalitaPagamentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
