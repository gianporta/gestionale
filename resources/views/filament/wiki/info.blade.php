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
    @if ($record->note)
    <div class="rounded-lg bg-gray-800 p-4">
        <div class="text-gray-400 mb-2">Note</div>
        <pre class="text-xs bg-gray-900 p-2 rounded overflow-x-auto whitespace-pre-wrap break-all">{{ $record->note }}</pre>
    </div>
    @endif
</div>
@endif
