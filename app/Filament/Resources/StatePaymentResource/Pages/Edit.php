<?php

namespace App\Filament\Resources\StatePaymentResource\Pages;

use App\Filament\Resources\StatePaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class Edit extends EditRecord
{
    protected static string $resource = StatePaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
