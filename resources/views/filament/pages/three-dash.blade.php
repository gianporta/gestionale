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
            <div class="mt-4 grid grid-cols-3 gap-4 text-sm">
                <div class="text-center">
                    <div class="text-gray-400">Ore</div>
                    <div class="font-semibold">{{ $package->ore }}</div>
                </div>
                <div class="text-center">
                    <div class="text-gray-400">Usate</div>
                    <div class="font-semibold">{{ $package->ore_usate }}</div>
                </div>
                <div class="text-center">
                    <div class="text-gray-400">Rimaste</div>
                    <div class="font-semibold text-amber-400">{{ $package->ore_rimaste }}</div>
                </div>
            </div>
            <div class="mt-4 grid grid-cols-3 gap-4 text-sm border-t border-gray-800 pt-4">
                <div class="text-center">
                    <div class="text-gray-400">Proforma</div>
                    <div class="font-semibold">{{ $package->proforma ? 'Sì' : 'No' }}</div>
                </div>
                <div class="text-center">
                    <div class="text-gray-400">Fatturato</div>
                    <div class="font-semibold">{{ $package->fatturato ? 'Sì' : 'No' }}</div>
                </div>
                <div class="text-center">
                    <div class="text-gray-400">Saldato</div>
                    <div class="font-semibold text-amber-400">{{ $package->saldato ? 'Sì' : 'No' }}</div>
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
