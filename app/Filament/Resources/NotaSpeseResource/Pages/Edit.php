<?php

namespace App\Filament\Resources\NotaSpeseResource\Pages;

use App\Filament\Resources\NotaSpeseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class Edit extends EditRecord
{
    protected static string $resource = NotaSpeseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
