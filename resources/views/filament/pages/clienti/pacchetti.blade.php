<x-filament-panels::page>

    <div class="rounded-2xl bg-gray-900 border border-gray-800 p-6">
        <div class="text-lg font-semibold mb-6">Pacchetti</div>

        <div class="grid grid-cols-1 {{ count($packages) > 1 ? 'md:grid-cols-2 xl:grid-cols-3' : '' }} gap-6">
            @forelse($packages as $package)
            <div class="rounded-xl bg-gray-950 border border-gray-800 p-5">
                <div class="text-sm text-gray-400">{{ $package->cliente }}</div>
                <div class="text-lg font-semibold text-center mt-2">{{ $package->nome }}</div>

                <div class="mt-6 flex justify-between text-sm">
                    <div>
                        <span class="text-gray-400">Ore</span><br>
                        <span class="font-semibold">{{ $package->ore }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400">Usate</span><br>
                        <span class="font-semibold">{{ $package->ore_usate }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400">Rimaste</span><br>
                        <span class="font-semibold text-amber-400">{{ $package->ore_rimaste }}</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-gray-400">Nessun pacchetto attivo</div>
            @endforelse
        </div>
    </div>

    <div class="mt-8 rounded-2xl bg-gray-900 border border-gray-800 p-6">
        <div class="text-lg font-semibold mb-4">Task da lavorare</div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-gray-400 border-b border-gray-800">
                <tr>
                    <th class="text-left py-2">Task</th>
                    <th class="text-right py-2">Ore lavorate</th>
                    <th class="text-right py-2">Stato</th>
                </tr>
                </thead>

                <tbody>
                @forelse($openTasks as $task)
                <tr class="border-b border-gray-800">
                    <td class="py-2">{{ $task->task }}</td>
                    <td class="text-right py-2">{{ $task->ore_lavorate }}</td>
                    <td class="text-right py-2">
                            <span class="badge-status {{ $task->stato_style ?? 'default' }}">
                                {{ $task->stato_nome ?? 'Unknown' }}
                            </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="py-4 text-gray-400">
                        Nessun task aperto
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-filament-panels::page>
