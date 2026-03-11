<?php

namespace App\Filament\Resources\ExternalInvoiceResource\Pages;

use App\Filament\Resources\ExternalInvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class Create extends CreateRecord
{
    protected static string $resource = ExternalInvoiceResource::class;
}
