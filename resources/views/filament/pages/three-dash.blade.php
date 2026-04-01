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
                <table class="w-full text-left">
                    <thead class="text-gray-400">
                    <tr>
                        <th>Ore</th>
                        <th>Usate</th>
                        <th>Rimaste</th>
                    </tr>
                    </thead>
                    <tbody class="font-semibold">
                    <tr>
                        <td>{{ $package->ore }}</td>
                        <td>{{ $package->ore_usate }}</td>
                        <td class="text-amber-400">{{ $package->ore_rimaste }}</td>
                    </tr>
                    </tbody>
                </table>
                <div class="border-t border-gray-800 my-4"></div>
                <table class="w-full text-left">
                    <thead class="text-gray-400">
                    <tr>
                        <th>Proforma</th>
                        <th>Fatturato</th>
                        <th>Saldato</th>
                    </tr>
                    </thead>
                    <tbody class="font-semibold">
                    <tr>
                        <td>{{ $package->proforma ? 'Sì' : 'No' }}</td>
                        <td>{{ $package->fatturato ? 'Sì' : 'No' }}</td>
                        <td class="text-amber-400">{{ $package->saldato ? 'Sì' : 'No' }}</td>
                    </tr>
                    </tbody>
                </table>

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
