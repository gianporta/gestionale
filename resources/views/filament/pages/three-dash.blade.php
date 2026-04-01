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
                <table class="w-full text-sm">
                    <tbody>
                    <tr class="text-gray-400">
                        <td>Ore</td>
                        <td>Usate</td>
                        <td>Rimaste</td>
                    </tr>

                    <tr class="font-semibold">
                        <td>{{ $package->ore }}</td>
                        <td>{{ $package->ore_usate }}</td>
                        <td class="text-amber-400">{{ $package->ore_rimaste }}</td>
                    </tr>

                    <tr>
                        <td colspan="3" class="py-3">
                            <div class="border-t border-gray-800"></div>
                        </td>
                    </tr>

                    <tr class="text-gray-400">
                        <td>Proforma</td>
                        <td>Fatturato</td>
                        <td>Saldato</td>
                    </tr>

                    <tr class="font-semibold">
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
