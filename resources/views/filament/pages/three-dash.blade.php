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

            <div class="mt-4 flex justify-between text-sm">

                <div class="text-center">
                    <span class="text-gray-400">Ore</span><br>
                    <span class="font-semibold">
                            {{ $package->ore }}
                        </span>
                </div>

                <div class="text-center">
                    <span class="text-gray-400">Usate</span><br>
                    <span class="font-semibold">
                            {{ $package->ore_usate }}
                        </span>
                </div>

                <div class="text-center">
                    <span class="text-gray-400">Rimaste</span><br>
                    <span class="font-semibold text-amber-400">
                            {{ $package->ore_rimaste }}
                        </span>
                </div>

            </div>

        </div>

        @endforeach

    </div>

</x-filament-panels::page>
