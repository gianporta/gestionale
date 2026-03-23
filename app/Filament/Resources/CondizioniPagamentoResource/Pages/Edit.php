<?php

namespace App\Filament\Resources\CondizioniPagamentoResource\Pages;

use App\Filament\Resources\CondizioniPagamentoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class Edit extends EditRecord
{
    protected static string $resource = CondizioniPagamentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
