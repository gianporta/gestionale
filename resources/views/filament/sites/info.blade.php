@if ($record)

<div class="space-y-4 text-sm">

    <div class="rounded-lg bg-gray-800 p-4">
        <div class="text-gray-400 mb-2">Sito</div>

        @if ($record->url)
        <div><b>Url:</b> <a href="{{ $record->url }}" target="_blank" class="text-primary-400">{{ $record->url }}</a></div>
        @endif

        @if ($record->admin_url)
        <div><b>Admin Url:</b> {{ rtrim($record->url, '/') }}/{{ $record->admin_url }}</div>
        @endif

        @if ($record->admin_user)
        <div><b>Admin User:</b> {{ $record->admin_user }}</div>
        @endif

        @if ($record->admin_psw)
        <div><b>Admin Psw:</b> {{ $record->admin_psw }}</div>
        @endif
    </div>


    <div class="rounded-lg bg-gray-800 p-4">
        <div class="text-gray-400 mb-2">SSH</div>

        @if ($record->ssh_host)
        <div><b>Host:</b> {{ $record->ssh_host }}</div>
        @endif

        @if ($record->ssh_user)
        <div><b>User:</b> {{ $record->ssh_user }}</div>
        @endif

        @if ($record->ssh_psw)
        <div><b>Psw:</b> {{ $record->ssh_psw }}</div>
        @endif

        @if ($record->base_dir)
        <div><b>Base Dir:</b> {{ $record->base_dir }}</div>
        @endif

        @if ($record->ssh_host && $record->ssh_user)
        <div class="mt-3 text-gray-400">Connection command:</div>
        <pre class="text-xs bg-gray-900 p-2 rounded mt-1 overflow-x-auto whitespace-pre-wrap break-all font-mono">
{{ "ssh {$record->ssh_user}@{$record->ssh_host}
cd " . ($record->base_dir ?? '~') }}
            </pre>
        @endif
    </div>


    <div class="rounded-lg bg-gray-800 p-4">
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

        @if ($record->db_psw)
        <div><b>Psw:</b> {{ $record->db_psw }}</div>
        @endif

        @if ($record->db_host && $record->db_user && $record->db_name)
        <div class="mt-3 text-gray-400">Connection command:</div>
        <pre class="text-xs bg-gray-900 p-2 rounded mt-1 overflow-x-auto whitespace-pre-wrap break-all font-mono">
{{ "mysql -u{$record->db_user} -p'{$record->db_psw}' {$record->db_name} -h{$record->db_host}" }}
            </pre>

        <div class="mt-3 text-gray-400">Dump command:</div>
        <pre class="text-xs bg-gray-900 p-2 rounded mt-1 overflow-x-auto whitespace-pre-wrap break-all font-mono">
{{ "mysqldump -u{$record->db_user} -p'{$record->db_psw}' {$record->db_name} -h{$record->db_host} > dump.sql" }}
            </pre>

        <div class="mt-3 text-gray-400">Restore command:</div>
        <pre class="text-xs bg-gray-900 p-2 rounded mt-1 overflow-x-auto whitespace-pre-wrap break-all font-mono">
{{ "mysql -u{$record->db_user} -p'{$record->db_psw}' {$record->db_name} -h{$record->db_host} < dump.sql" }}
            </pre>
        @endif
    </div>


    <div class="rounded-lg bg-gray-800 p-4">
        <div class="text-gray-400 mb-2">Tecnologie</div>

        @if ($record->cms)
        <div><b>CMS:</b> {{ $record->cms }}</div>
        @endif

        @if ($record->cms_version)
        <div><b>CMS Version:</b> {{ $record->cms_version }}</div>
        @endif

        @if ($record->php_version)
        <div><b>PHP:</b> {{ $record->php_version }}</div>
        @endif

        @if ($record->mysql_version)
        <div><b>MySQL:</b> {{ $record->mysql_version }}</div>
        @endif

        @if ($record->composer_version)
        <div><b>Composer:</b> {{ $record->composer_version }}</div>
        @endif

        @if ($record->elasticsearch_version)
        <div><b>Elasticsearch:</b> {{ $record->elasticsearch_version }}</div>
        @endif
    </div>


    <div class="rounded-lg bg-gray-800 p-4">
        <div class="text-gray-400 mb-2">Integrazioni</div>

        @if ($record->trello)
        <div><b>Trello:</b> <a href="{{ $record->trello }}" target="_blank" class="text-primary-400">Apri</a></div>
        @endif

        @if ($record->clickup)
        <div><b>Clickup:</b> <a href="{{ $record->clickup }}" target="_blank" class="text-primary-400">Apri</a></div>
        @endif
    </div>

</div>

@endif
