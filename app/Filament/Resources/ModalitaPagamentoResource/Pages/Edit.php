<?php

namespace App\Filament\Resources\ModalitaPagamentoResource\Pages;

use App\Filament\Resources\ModalitaPagamentoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class Edit extends EditRecord
{
    protected static string $resource = ModalitaPagamentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
