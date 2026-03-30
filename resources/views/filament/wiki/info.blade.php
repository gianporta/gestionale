@if ($record)

<div class="space-y-4 text-sm">

    <div class="rounded-lg bg-gray-800 p-4">
        <div class="text-gray-400 mb-2">Generale</div>

        @if ($record->categoriaRel)
        <div><b>Categoria:</b> {{ $record->categoriaRel->nome }}</div>
        @endif

        @if ($record->problema)
        <div><b>Problema:</b> {{ $record->problema }}</div>
        @endif

        @if ($record->link)
        <div><b>Link:</b> <a href="{{ $record->link }}" target="_blank" class="text-primary-400">{{ $record->link }}</a></div>
        @endif

        @if (!is_null($record->attivo))
        <div><b>Attivo:</b> {{ $record->attivo ? 'Sì' : 'No' }}</div>
        @endif
    </div>


    @if ($record->comando)
    <div
        x-data
        class="rounded-lg bg-gray-800 p-4"
    >
        <div class="flex justify-between items-center mb-2">
            <div class="text-gray-400">Comando</div>

            <button
                class="text-xs px-2 py-1 bg-gray-700 rounded hover:bg-gray-600"
                x-on:click="
                navigator.clipboard.writeText(@js($record->comando));
                new FilamentNotification()
                    .title('Copiato negli appunti')
                    .success()
                    .send();
            "
            >
                Copia
            </button>
        </div>

        <pre class="text-xs bg-gray-900 p-2 rounded overflow-x-auto whitespace-pre-wrap break-all font-mono">
{{ $record->comando }}
    </pre>
    </div>
    @endif


    @if ($record->sql)
    <div x-data class="rounded-lg bg-gray-800 p-4">
        <div class="flex justify-between items-center mb-2">
            <div class="text-gray-400">SQL</div>

            <button
                class="text-xs px-2 py-1 bg-gray-700 rounded hover:bg-gray-600"
                x-on:click="
                navigator.clipboard.writeText(@js($record->sql));
                new FilamentNotification()
                    .title('Copiato negli appunti')
                    .success()
                    .send();
            "
            >
                Copia
            </button>
        </div>

        <pre class="text-xs bg-gray-900 p-2 rounded overflow-x-auto whitespace-pre-wrap break-all font-mono">
{{ $record->sql }}
    </pre>
    </div>
    @endif


    @if ($record->note)
    <div class="rounded-lg bg-gray-800 p-4">
        <div class="text-gray-400 mb-2">Note</div>
        <pre class="text-xs bg-gray-900 p-2 rounded overflow-x-auto whitespace-pre-wrap break-all">{{ $record->note }}</pre>
    </div>
    @endif

</div>

@endif
