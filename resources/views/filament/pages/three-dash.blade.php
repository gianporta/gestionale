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
            <div class="mt-4 grid grid-cols-3 gap-y-4 text-center">
                <div>
                    <span class="text-gray-400 text-sm">Ore</span><br>
                    <span class="font-semibold text-sm">{{ $package->ore }}</span>
                </div>
                <div>
                    <span class="text-gray-400 text-sm">Usate</span><br>
                    <span class="font-semibold text-sm">{{ $package->ore_usate }}</span>
                </div>
                <div>
                    <span class="text-gray-400 text-sm">Rimaste</span><br>
                    <span class="font-semibold text-sm text-amber-400">{{ $package->ore_rimaste }}</span>
                </div>
                <div class="border-t border-gray-800 pt-4">
                    <span class="text-gray-400 text-xs">Proforma</span><br>
                    <span class="font-semibold {{ $package->proforma ? 'text-green-400' : 'text-red-400' }}">
            {{ $package->proforma ? 'Sì' : 'No' }}
        </span>
                </div>
                <div class="border-t border-gray-800 pt-4">
                    <span class="text-gray-400 text-xs">Fatturato</span><br>
                    <span class="font-semibold {{ $package->fatturato ? 'text-green-400' : 'text-red-400' }}">
            {{ $package->fatturato ? 'Sì' : 'No' }}
        </span>
                </div>
                <div class="border-t border-gray-800 pt-4">
                    <span class="text-gray-400 text-xs">Saldato</span><br>
                    <span class="font-semibold {{ $package->saldato ? 'text-green-400' : 'text-red-400' }}">
            {{ $package->saldato ? 'Sì' : 'No' }}
        </span>
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
