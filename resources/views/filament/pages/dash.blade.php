<x-filament-panels::page>
    <div class="grid grid-cols-1 {{ count($packages) > 1 ? 'md:grid-cols-2 xl:grid-cols-3' : '' }} gap-6">
        @forelse($packages as $package)
        <div class="rounded-xl bg-gray-900 border border-gray-800 p-6">
            <div class="text-sm text-gray-400">{{ $package->cliente }}</div>
            <div class="text-lg font-semibold text-center mt-2">{{ $package->nome }}</div>
            <div class="mt-6 flex justify-between text-sm">
                <div><span class="text-gray-400">Ore</span><br><span class="font-semibold">{{ $package->ore }}</span></div>
                <div><span class="text-gray-400">Usate</span><br><span class="font-semibold">{{ $package->ore_usate }}</span></div>
                <div><span class="text-gray-400">Rimaste</span><br><span class="font-semibold text-amber-400">{{ $package->ore_rimaste }}</span></div>
            </div>
        </div>
        @empty
        <div class="col-span-full rounded-xl bg-gray-900 border border-gray-800 p-6">Nessun pacchetto attivo</div>
        @endforelse
    </div>

    <div class="mt-10" x-data="{search:'',anno:'all',mese:'all',page:1,perPage:10,totalRows: {{ collect($hoursByYear)->flatten()->count() }}}">
        <div class="flex gap-4 mb-4">
            <input type="text" x-model="search" placeholder="Cerca task..." style="color:black !important;" class="w-1/2 bg-gray-900 border border-gray-800 rounded-lg px-4 py-2 text-sm"/>
            <select x-model="anno" class="bg-gray-900 border border-gray-800 rounded-lg px-4 py-2 text-sm">
                <option value="all">Tutti gli anni</option>
                @foreach($hoursByYear as $year => $hours)
                <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
            <select x-model="mese" class="bg-gray-900 border border-gray-800 rounded-lg px-4 py-2 text-sm">
                <option value="all">Tutti i mesi</option>
                <option value="01">Gennaio</option>
                <option value="02">Febbraio</option>
                <option value="03">Marzo</option>
                <option value="04">Aprile</option>
                <option value="05">Maggio</option>
                <option value="06">Giugno</option>
                <option value="07">Luglio</option>
                <option value="08">Agosto</option>
                <option value="09">Settembre</option>
                <option value="10">Ottobre</option>
                <option value="11">Novembre</option>
                <option value="12">Dicembre</option>
            </select>
        </div>

        @php $rowIndex = 0; @endphp
        @forelse($hoursByYear as $year => $hours)
        <div class="mb-8">
            <div class="overflow-x-auto">
                <table class="w-full text-sm table-fixed">
                    <thead class="text-gray-400 border-b border-gray-800">
                    <tr>
                        <th class="text-left py-2 w-32">Data</th>
                        <th class="text-left py-2 w-32">Task</th>
                        <th class="text-left py-2 w-40">Descrizione</th>
                        <th class="text-right py-2 w-16">Ore</th>
                        <th class="text-right py-2 w-32">Stato</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($hours as $row)
                    @php $rowIndex++; @endphp
                    <tr x-show="(search === '' || '{{ strtolower($row->task) }}'.includes(search.toLowerCase())) && (anno === 'all' || anno == '{{ \Carbon\Carbon::parse($row->data_lavorazione)->format('Y') }}') && (mese === 'all' || mese == '{{ \Carbon\Carbon::parse($row->data_lavorazione)->format('m') }}') && ({{ $rowIndex }} > ((page-1)*perPage)) && ({{ $rowIndex }} <= (page*perPage))" class="border-b border-gray-800">
                        <td class="py-2 w-32">{{ \Carbon\Carbon::parse($row->data_lavorazione)->format('d/m/Y') }}</td>
                        <td class="py-2 w-32">{{ $row->task }}</td>
                        <td class="py-2 w-40">{{ $row->descrizione }}</td>
                        <td class="text-right py-2 w-16">{{ $row->ore_lavorate }}</td>
                        <td class="text-right py-2 w-32">
                            @if($row->stato == 1)
                            <span class="inline-block px-2 py-1 text-xs font-medium rounded-md bg-yellow-500 text-black">Da fare</span>
                            @elseif($row->stato == 2)
                            <span class="inline-block px-2 py-1 text-xs font-medium rounded-md bg-blue-500 text-white">In lavorazione</span>
                            @elseif($row->stato == 3)
                            <span class="inline-block px-2 py-1 text-xs font-medium rounded-md bg-green-500 text-white">Finito</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @empty
        <div class="rounded-xl bg-gray-900 border border-gray-800 p-6">Nessuna ora registrata</div>
        @endforelse
        <div class="flex justify-between items-center mt-6 text-sm text-gray-400">
            <div class="flex items-center gap-4">
                <div>Mostrati <span x-text="((page - 1) * perPage) + 1"></span>-<span x-text="Math.min(page * perPage, totalRows)"></span> di <span x-text="totalRows"></span> risultati</div>
                <div class="flex items-center gap-2">
                    <span>Per pagina</span>
                    <select x-model="perPage" @change="page=1" style="width:90px;" class="bg-gray-900 border border-gray-800 rounded-lg px-2 py-1 text-sm">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button @click="if(page>1) page--" class="px-3 py-1 bg-gray-800 rounded text-sm">Prev</button>
                <template x-for="p in Math.ceil(totalRows/perPage)">
                    <button @click="page = p" x-text="p" class="px-3 py-1 rounded text-sm" :class="page == p ? 'bg-amber-500 text-black' : 'bg-gray-800'"></button>
                </template>
                <button @click="if(page < Math.ceil(totalRows/perPage)) page++" class="px-3 py-1 bg-gray-800 rounded text-sm">Next</button>
            </div>
        </div>
    </div>
    <div class="mt-10">
        <div class="text-lg font-semibold mb-4">Task da lavorare</div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-gray-400 border-b border-gray-800">
                <tr>
                    <th class="text-left py-2">Task</th>
                    <th class="text-right py-2">Ore lavorate</th>
                </tr>
                </thead>
                <tbody>
                @forelse($openTasks as $task)
                <tr class="border-b border-gray-800">
                    <td class="py-2">{{ $task->task }}</td>
                    <td class="text-right">{{ $task->ore_lavorate }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="py-4 text-gray-400">
                        Nessun task aperto
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-filament-panels::page>
