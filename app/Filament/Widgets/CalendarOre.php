<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;

class CalendarOre extends Widget
{
    protected static string $view = 'filament.widgets.calendar-ore';

    protected int | string | array $columnSpan = 1;

    public int $month;
    public int $year;

    public function mount(): void
    {
        $this->month = now()->month;
        $this->year = now()->year;
    }

    public function prevMonth()
    {
        $date = now()->setMonth($this->month)->setYear($this->year)->subMonth();

        $this->month = $date->month;
        $this->year = $date->year;
    }

    public function nextMonth()
    {
        $date = now()->setMonth($this->month)->setYear($this->year)->addMonth();

        $this->month = $date->month;
        $this->year = $date->year;
    }

    public function getViewData(): array
    {
        $userId = auth()->id();

        $ore = DB::table('hours')
            ->selectRaw("DAY(data_lavorazione) as giorno, SUM(CAST(REPLACE(ore_lavorate, ',', '.') as DECIMAL(10,2))) as totale")
            ->whereMonth('data_lavorazione', $this->month)
            ->whereYear('data_lavorazione', $this->year)
            ->where('user', $userId)
            ->groupBy('giorno')
            ->pluck('totale', 'giorno')
            ->toArray();

        $date = now()->setMonth($this->month)->setYear($this->year);

        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        $weeks = [];
        $currentWeek = [];

        $startDay = $startOfMonth->dayOfWeekIso;

        for ($i = 1; $i < $startDay; $i++)
            $currentWeek[] = null;

        for ($day = 1; $day <= $endOfMonth->day; $day++) {
            $currentWeek[] = [
                'day' => $day,
                'ore' => $ore[$day] ?? 0,
            ];

            if (count($currentWeek) === 7) {
                $weeks[] = $currentWeek;
                $currentWeek = [];
            }
        }

        if (count($currentWeek))
            $weeks[] = array_pad($currentWeek, 7, null);

        return [
            'weeks' => $weeks,
            'label' => ucfirst($date->translatedFormat('F Y')),
        ];
    }
}
