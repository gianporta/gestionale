<x-filament-panels::page>
    @if(isset($kpiTop) && count($kpiTop))
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
        @foreach($kpiTop as $card)
        <div class="rounded-2xl bg-gray-900 p-6 ring-1 ring-white/10">
            <div class="text-sm text-gray-400">{{ $card['label'] }}</div>
            <div class="mt-3 text-2xl font-semibold text-white">
                {{ number_format($card['value'], 2, ',', '.') }} €
            </div>
        </div>
        @endforeach
    </div>
    @endif

    @if(isset($kpiFatturato) && count($kpiFatturato))
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
        @foreach($kpiFatturato as $card)
        <div class="rounded-2xl bg-gray-900 p-6 ring-1 ring-white/10">
            <div class="text-sm text-gray-400">{{ $card['label'] }}</div>
            <div class="mt-3 text-2xl font-semibold text-white">
                {{ number_format($card['value'], 2, ',', '.') }} €
            </div>
        </div>
        @endforeach
    </div>
    @endif

    @if(isset($kpiIva) && count($kpiIva))
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
        @foreach($kpiIva as $card)
        <div class="rounded-2xl bg-gray-900 p-6 ring-1 ring-white/10">
            <div class="text-sm text-gray-400">{{ $card['label'] }}</div>
            <div class="mt-3 text-2xl font-semibold text-white">
                {{ number_format($card['value'], 2, ',', '.') }} €
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{ $this->getHeaderWidgets() }}
</x-filament-panels::page>
