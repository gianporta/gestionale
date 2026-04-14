<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class Create extends CreateRecord
{
    protected static string $resource = CustomerResource::class;
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
                ->action(function () {
                    $this->create();
                }),
        ];
    }
}
