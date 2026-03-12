<?php
declare(strict_types=1);
namespace App\Filament\Resources\OauthUserResource\Pages;

use App\Filament\Resources\OauthUserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class Listing extends ListRecords
{
    protected static string $resource = OauthUserResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
