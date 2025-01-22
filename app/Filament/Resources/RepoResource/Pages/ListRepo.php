<?php
declare(strict_types=1);
namespace App\Filament\Resources\RepoResource\Pages;

use App\Filament\Resources\RepoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRepo extends ListRecords
{
    protected static string $resource = RepoResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
