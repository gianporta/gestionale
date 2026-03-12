@if ($record)

<div class="space-y-4">

    @if ($record->url || $record->admin_url || $record->admin_user)
    <div class="rounded-lg bg-gray-800 p-4 text-sm">
        <div class="text-gray-400 mb-2">Sito</div>

        @if ($record->url)
        <div><b>Url:</b> {{ $record->url }}</div>
        @endif

        @if ($record->admin_url)
        <div><b>Admin Url:</b> {{ $record->admin_url }}</div>
        @endif

        @if ($record->admin_user)
        <div><b>Admin User:</b> {{ $record->admin_user }}</div>
        @endif

    </div>
    @endif


    @if ($record->ssh_host || $record->ssh_user || $record->ssh_port)
    <div class="rounded-lg bg-gray-800 p-4 text-sm">
        <div class="text-gray-400 mb-2">SSH</div>

        @if ($record->ssh_host)
        <div><b>Host:</b> {{ $record->ssh_host }}</div>
        @endif

        @if ($record->ssh_user)
        <div><b>User:</b> {{ $record->ssh_user }}</div>
        @endif

        @if ($record->ssh_port)
        <div><b>Port:</b> {{ $record->ssh_port }}</div>
        @endif

    </div>
    @endif


    @if ($record->db_host || $record->db_name || $record->db_user)
    <div class="rounded-lg bg-gray-800 p-4 text-sm">
        <div class="text-gray-400 mb-2">Database</div>

        @if ($record->db_host)
        <div><b>Host:</b> {{ $record->db_host }}</div>
        @endif

        @if ($record->db_name)
        <div><b>Name:</b> {{ $record->db_name }}</div>
        @endif

        @if ($record->db_user)
        <div><b>User:</b> {{ $record->db_user }}</div>
        @endif

    </div>
    @endif

</div>

@endif
