<?php

namespace App\Filament\Resources\WikiResource\Pages;

use App\Filament\Resources\WikiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class Create extends CreateRecord
{
    protected static string $resource = WikiResource::class;
}
