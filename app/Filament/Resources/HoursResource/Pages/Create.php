<?php

namespace App\Filament\Resources\HoursResource\Pages;

use App\Filament\Resources\HoursResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class Create extends CreateRecord
{
    protected static string $resource = HoursResource::class;
}
