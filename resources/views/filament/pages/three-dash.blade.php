<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($packages as $package)
        <div class="rounded-xl bg-gray-900 border border-gray-800 p-6">
            <div class="text-center mb-4">
                <div class="text-sm text-gray-400">
                    {{ $package->cliente }}
                </div>
                <div class="text-lg font-semibold">
                    {{ $package->nome }}
                </div>
            </div>
            <div class="mt-4 text-sm">
                <div class="grid grid-cols-3 text-gray-400 text-center">
                    <div>Ore</div>
                    <div>Usate</div>
                    <div>Rimaste</div>
                </div>
                <div class="grid grid-cols-3 text-center font-semibold mt-1">
                    <div>{{ $package->ore }}</div>
                    <div>{{ $package->ore_usate }}</div>
                    <div class="text-amber-400">{{ $package->ore_rimaste }}</div>
                </div>
                <div class="border-t border-gray-800 my-4"></div>
                <div class="grid grid-cols-3 text-gray-400 text-center">
                    <div>Proforma</div>
                    <div>Fatturato</div>
                    <div>Saldato</div>
                </div>
                <div class="grid grid-cols-3 text-center font-semibold mt-1">
                    <div>{{ $package->proforma ? 'Sì' : 'No' }}</div>
                    <div>{{ $package->fatturato ? 'Sì' : 'No' }}</div>
                    <div class="text-amber-400">{{ $package->saldato ? 'Sì' : 'No' }}</div>
                </div>

            </div>
        </div>
        @endforeach

    </div>
    <x-filament::section>
        <x-slot name="heading">
            Task in lavorazione
        </x-slot>

        {{ $this->table }}
    </x-filament::section>
</x-filament-panels::page>
