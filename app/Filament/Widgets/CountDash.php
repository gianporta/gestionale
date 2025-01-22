<?php
declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\OauthRepo;
use App\Models\OauthUser;
use App\Models\Repo;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class CountDash extends StatsOverviewWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Numero di Repository', Repo::count())
                ->color('primary'),
            Card::make('Numero di Utenti OAuth', OauthUser::count())
                ->color('success'),
            Card::make('Numero di OAuth Repository', OauthRepo::count())
                ->color('warning'),
        ];
    }
}
