<?php

namespace App\Filament\Resources\SiteResource\Pages;

use App\Filament\Resources\SiteResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class Edit extends EditRecord
{
    protected static string $resource = SiteResource::class;
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            Action::make('save_top')
                ->label('Salva')
                ->color('primary')
                ->action(function () {
                    $this->save();
                }),
        ];
    }
}
