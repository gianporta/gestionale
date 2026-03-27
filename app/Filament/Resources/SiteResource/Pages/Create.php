<?php

namespace App\Filament\Resources\SiteResource\Pages;

use App\Filament\Resources\SiteResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class Create extends CreateRecord
{
    protected static string $resource = SiteResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Indietro')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(fn () => static::getResource()::getUrl('index')),
            Action::make('save_top')
                ->label('Salva')
                ->color('primary')
                ->submit('create')
        ];
    }
}
