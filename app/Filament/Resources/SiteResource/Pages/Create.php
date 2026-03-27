<?php

namespace App\Filament\Resources\SiteResource\Pages;

use App\Filament\Resources\SiteResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class Create extends CreateRecord
{
    protected static string $resource = SiteResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Action::make('save_top')
                ->label('Salva')
                ->color('primary')
                ->action(function () {
                    $this->save();
                }),
        ];
    }
}
