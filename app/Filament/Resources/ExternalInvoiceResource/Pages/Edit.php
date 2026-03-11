<?php

namespace App\Filament\Resources\ExternalInvoiceResource\Pages;

use App\Filament\Resources\ExternalInvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class Edit extends EditRecord
{
    protected static string $resource = ExternalInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
