<x-filament-widgets::widget>

    <div class="rounded-2xl bg-gray-900 p-4 ring-1 ring-white/10">

        {{-- HEADER --}}
        <div class="flex items-center justify-between mb-4">

            <button wire:click="prevMonth" class="text-gray-400 hover:text-white">
                ←
            </button>

            <div class="text-sm font-semibold text-white">
                {{ $label }}
            </div>

            <button wire:click="nextMonth" class="text-gray-400 hover:text-white">
                →
            </button>

        </div>

        {{-- giorni --}}
        <div class="grid grid-cols-7 text-center text-xs text-gray-400 mb-2">
            <div>L</div><div>M</div><div>M</div><div>G</div><div>V</div><div>S</div><div>D</div>
        </div>

        {{-- settimane --}}
        @foreach($weeks as $week)
        <div class="grid grid-cols-7 gap-2 mb-2 text-center text-xs">
            @foreach($week as $day)
            <div class="h-14 flex flex-col items-center justify-center rounded-lg bg-gray-800">

                @if($day)
                <div class="text-gray-400 text-[10px]">
                    {{ $day['day'] }}
                </div>

                <div class="text-white text-sm font-semibold">
                    {{ $day['ore'] > 0 ? number_format($day['ore'], 1) : '-' }}
                </div>
                @endif

            </div>
            @endforeach
        </div>
        @endforeach

    </div>

</x-filament-widgets::widget>
