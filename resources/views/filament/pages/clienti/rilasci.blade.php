<x-filament-panels::page>

    <div class="rounded-2xl bg-gray-900 border border-gray-800 p-6"
         x-data="{search:'',anno:'all',mese:'all',page:1,perPage:10,totalRows: {{ collect($rilasci)->flatten()->count() }}}">

        <div class="mb-6">
            <div class="text-lg font-semibold mb-4">Rilasci</div>

            <div class="flex gap-4">
                <input type="text"
                       x-model="search"
                       placeholder="Cerca descrizione..."
                       class="w-1/2 bg-gray-950 border border-gray-800 rounded-lg px-4 py-2 text-sm text-white"/>

                <select x-model="anno"
                        class="bg-gray-950 border border-gray-800 rounded-lg px-4 py-2 text-sm text-white">
                    <option value="all">Tutti gli anni</option>
                    @foreach($rilasci as $year => $rilascio)
                    <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>

                <select x-model="mese"
                        class="bg-gray-950 border border-gray-800 rounded-lg px-4 py-2 text-sm text-white">
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
        </div>

        @php $rowIndex = 0; @endphp

        <div class="rounded-xl bg-gray-950 border border-gray-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm table-fixed">
                    <thead class="text-gray-400 border-b border-gray-800 bg-gray-900">
                    <tr>
                        <th class="text-left py-3 px-2 w-40">Data</th>
                        <th class="text-left py-3 px-2">Descrizione</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($rilasci as $year => $rilascio)
                    @foreach($rilascio as $row)
                    @php $rowIndex++; @endphp
                    <tr
                        x-show="(search === '' || '{{ strtolower($row->descrizione) }}'.includes(search.toLowerCase())) &&
                                        (anno === 'all' || anno == '{{ \Carbon\Carbon::parse($row->data_lavorazione)->format('Y') }}') &&
                                        (mese === 'all' || mese == '{{ \Carbon\Carbon::parse($row->data_lavorazione)->format('m') }}') &&
                                        ({{ $rowIndex }} > ((page-1)*perPage)) &&
                                        ({{ $rowIndex }} <= (page*perPage))"
                        class="border-b border-gray-800 hover:bg-gray-900 transition">

                        <td class="py-2 px-2 w-40">
                            {{ \Carbon\Carbon::parse($row->data_lavorazione)->format('d/m/Y') }}
                        </td>

                        <td class="py-2 px-2 text-gray-300">
                            {{ $row->descrizione }}
                        </td>

                    </tr>
                    @endforeach
                    @empty
                    <tr>
                        <td colspan="2" class="py-6 text-center text-gray-400">
                            Nessun rilascio
                        </td>
                    </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6 flex justify-between items-center text-sm text-gray-400">
            <div class="flex items-center gap-4">
                <div>
                    Mostrati
                    <span x-text="((page - 1) * perPage) + 1"></span> -
                    <span x-text="Math.min(page * perPage, totalRows)"></span>
                    di <span x-text="totalRows"></span>
                </div>

                <div class="flex items-center gap-2">
                    <span>Per pagina</span>
                    <select x-model="perPage" @change="page=1"
                            class="bg-gray-950 border border-gray-800 rounded-lg px-2 py-1 text-sm text-white">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button @click="if(page>1) page--"
                        class="px-3 py-1 bg-gray-800 rounded hover:bg-gray-700">
                    Prev
                </button>

                <template x-for="p in Math.ceil(totalRows/perPage)">
                    <button @click="page = p"
                            x-text="p"
                            class="px-3 py-1 rounded"
                            :class="page == p ? 'bg-amber-500 text-black' : 'bg-gray-800 hover:bg-gray-700'">
                    </button>
                </template>

                <button @click="if(page < Math.ceil(totalRows/perPage)) page++"
                        class="px-3 py-1 bg-gray-800 rounded hover:bg-gray-700">
                    Next
                </button>
            </div>
        </div>

    </div>

</x-filament-panels::page>
