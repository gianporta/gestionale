<x-filament-panels::page>

    <div class="rounded-2xl bg-gray-900 border border-gray-800 p-6">
        <div class="text-lg font-semibold mb-4">Stime Task</div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-gray-400 border-b border-gray-800">
                <tr>
                    <th class="text-left py-2">Task</th>
                    <th class="text-left py-2">Stima</th>
                    <th class="text-right py-2">Totale Ore Lavorate</th>
                    <th class="text-right py-2">Stato</th>
                </tr>
                </thead>

                <tbody>
                @forelse($stimeTasks as $task)
                <tr class="border-b border-gray-800">
                    <td class="py-2">{{ $task->task }}</td>
                    <td class="py-2">{{ $task->stima ?? '-' }}</td>
                    <td class="text-right py-2">{{ $task->totale_ore_lavorate }}</td>
                    <td class="text-right py-2">
                        <span class="badge-status {{ $task->stato_style ?? 'default' }}">{{ $task->stato_nome ?? 'Unknown' }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-4 text-gray-400">
                        Nessun dato disponibile
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-filament-panels::page>
