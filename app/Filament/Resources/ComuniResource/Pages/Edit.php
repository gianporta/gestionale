<?php

namespace App\Filament\Resources\ComuniResource\Pages;

use App\Filament\Resources\ComuniResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class Edit extends EditRecord
{
    protected static string $resource = ComuniResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
