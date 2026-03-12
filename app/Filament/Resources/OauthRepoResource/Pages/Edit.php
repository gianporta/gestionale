<?php

namespace App\Filament\Resources\OauthRepoResource\Pages;

use App\Filament\Resources\OauthRepoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class Edit extends EditRecord
{
    protected static string $resource = OauthRepoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
