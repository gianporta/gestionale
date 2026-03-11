<?php

namespace App\Filament\Resources\AcquistiResource\Pages;

use App\Filament\Resources\AcquistiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class Edit extends EditRecord
{
    protected static string $resource = AcquistiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
