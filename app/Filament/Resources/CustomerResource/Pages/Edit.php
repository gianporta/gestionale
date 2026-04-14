<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class Edit extends EditRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            Action::make('back')
                ->label('Indietro')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(fn () => static::getResource()::getUrl('index')),
            Action::make('new')
                ->label('Nuovo')
                ->icon('heroicon-o-plus')
                ->color('success')
                ->url(fn () => static::getResource()::getUrl('create')),
            Action::make('save_top')
                ->label('Salva')
                ->color('primary')
                ->action(function () {
                    $this->save();
                }),
        ];
    }
}
