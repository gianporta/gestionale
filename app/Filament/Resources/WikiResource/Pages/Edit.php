<?php

namespace App\Filament\Resources\WikiResource\Pages;

use App\Filament\Resources\WikiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class Edit extends EditRecord
{
    protected static string $resource = WikiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
