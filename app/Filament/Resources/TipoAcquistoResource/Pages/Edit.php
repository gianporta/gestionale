<?php

namespace App\Filament\Resources\TipoAcquistoResource\Pages;

use App\Filament\Resources\TipoAcquistoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class Edit extends EditRecord
{
    protected static string $resource = TipoAcquistoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
