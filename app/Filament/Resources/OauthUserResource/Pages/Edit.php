<?php

namespace App\Filament\Resources\OauthUserResource\Pages;

use App\Filament\Resources\OauthUserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class Edit extends EditRecord
{
    protected static string $resource = OauthUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
