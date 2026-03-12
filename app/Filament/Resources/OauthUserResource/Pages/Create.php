<?php

namespace App\Filament\Resources\OauthUserResource\Pages;

use App\Filament\Resources\OauthUserResource;
use Filament\Resources\Pages\CreateRecord;

class Create extends CreateRecord
{
    protected static string $resource = OauthUserResource::class;
}
