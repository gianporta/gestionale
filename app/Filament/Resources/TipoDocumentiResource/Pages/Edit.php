<?php

namespace App\Filament\Resources\TipoDocumentiResource\Pages;

use App\Filament\Resources\TipoDocumentiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class Edit extends EditRecord
{
    protected static string $resource = TipoDocumentiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
