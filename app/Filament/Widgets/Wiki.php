<?php
declare(strict_types=1);

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class Wiki extends Widget
{
    protected static string $view = 'vendor.filament.widgets.wiki';

    protected int | string | array $columnSpan = 'full';
}
