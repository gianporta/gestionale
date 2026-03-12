<?php

namespace App\Filament\Resources\CountriesResource\Pages;

use App\Filament\Resources\CountriesResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class Create extends CreateRecord
{
    protected static string $resource = CountriesResource::class;
}
