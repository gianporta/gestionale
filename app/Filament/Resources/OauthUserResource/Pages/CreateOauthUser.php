<?php

namespace App\Filament\Resources\OauthUserResource\Pages;

use App\Filament\Resources\OauthUserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOauthUser extends CreateRecord
{
    protected static string $resource = OauthUserResource::class;
}
