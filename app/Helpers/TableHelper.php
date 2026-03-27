<?php

namespace App\Helpers;

use App\Models\Categoria;
use App\Models\Cms;
use App\Models\Customer;
use App\Models\Job;
use App\Models\Packages;
use App\Models\Repo;
use App\Models\StatoDocumento;
use App\Models\StatoTask;
use App\Models\Stime;
use App\Models\Task;
use App\Models\TipoAcquisto;
use App\Models\User;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class TableHelper
{
    public static function getNumberRecordTable(): array
    {
        return [10, 25, 50, -1];
    }

    public static function formatColumnValue(string $column, mixed $value): mixed
    {
        switch ($column) {
            case 'costo':
            case 'netto_a_pagare':
                return ['value', number_format((float)$value, 2, ',', '.') . ' €'];
            case 'data_lavorazione':
            case 'data_pagamento':
            case 'data_documento':
                return ['value', \Carbon\Carbon::parse($value)->format('d/m/Y')];
            case 'tipo_acquisto':
                return ['value', TipoAcquisto::find($value)?->nome];
            case 'stato_job':
                return ['value', Job::getStatoJob()[$value]];
            case 'proforma':
            case 'fatturato':
            case 'saldato':
            case 'is_active':
            case 'attivo':
                return ['value', ($value == 0) ? 'No' : 'Sì'];
            case 'ambiente':
                return ($value == 0) ? 'Produzione' : 'Staging';
            case 'stima':
                return ['value', Stime::find($value)?->nome];
            case 'stato_documento':
                return ['value', StatoDocumento::find($value)?->nome];
            case 'categoria':
                return ['value', Categoria::find($value)?->nome];
            case 'task_id':
                return ['value', Task::find($value)?->task];
            case 'cms':
                return ['value', Cms::find($value)?->nome];
            case 'id_user':
                return ['value', User::find($value)?->name];
            case 'id_repo':
                return ['value', Repo::find($value)?->packages];
            case 'cliente':
            case 'cliente_id':
                return ['label' => 'Cliente', 'value' => Customer::find($value)?->ragione_sociale];
            case 'pacchetto_id':
                $package = Packages::query()
                    ->join('customers', 'customers.id', '=', 'packages.cliente_id')
                    ->where('packages.id', $value)
                    ->select('packages.nome', 'customers.ragione_sociale as cliente')
                    ->first();
                return ['value', $package
                    ? $package->cliente . ' - ' . $package->nome
                    : null];
            case 'stato':
                return ['value', StatoTask::find($value)?->nome];
            default:
                return ['value', $value];
        }
    }

    public static function decorateColumn(string $column, TextColumn $col): void
    {
        switch ($column) {

            case 'ambiente':
                $col->badge()
                    ->color(fn($state) => match ($state) {
                        0 => 'danger',
                        1 => 'success',
                        default => 'gray',
                    });
                break;
            case 'stato':
                $col->badge()
                    ->formatStateUsing(fn($state) => StatoTask::find($state)?->nome ?? $state)
                    ->color(fn($state) => StatoTask::find($state)?->style ?? 'gray');
                break;
            case 'is_active':
            case 'attivo':
                $col->badge()
                    ->color(fn($state) => $state ? 'success' : 'danger');
                break;
        }
    }

    public static function getExcludedColumns(): array
    {
        $listExclude['default'] = array(
            'remember_token',
            'psw',
            'password',
            'email_verified_at',
            'two_factor_secret',
            'two_factor_recovery_codes',
            'two_factor_confirmed_at',
            'company_id',
            'partita_iva',
            'codice_fiscale',
            'email',
            'pec',
            'sdi',
            'provincia',
            'cap',
            'indirizzo',
            'iban',
            'banca',
            'intestatario_conto',
            'telefono',
            'fax',
            'cellulare',
            'sito_web',
            'tipo_cliente',
            'user',
            'user_id',
            'admin_url',
            'http_user',
            'http_psw',
            'admin_user',
            'admin_psw',
            'ssh_host',
            'ssh_user',
            'ssh_psw',
            'ssh_super_user',
            'ssh_super_psw',
            'ssh_port',
            'ssh_key',
            'ssh_key_name',
            'base_dir',
            'db_host',
            'db_name',
            'db_user',
            'db_psw',
            'db_port',
            'repo',
            'php_version',
            'sucuri',
            'varnish',
            'opcache',
            'redis',
            'enable_ip',
            'trello',
            'clickup',
            'vpn',
            'vpn_name',
            'vpn_host',
            'vpn_user',
            'vpn_psw',
            'vpn_port',
            'note',
            'mysql_version',
            'redis_version',
            'composer_version',
            'elasticsearch_version',
            'cpanel_url',
            'cpanel_user',
            'cpanel_psw',
            'tunnel_ssh',
            'local_port',
            'link',
            'ore_pacchetto',
            'customer',
            'created_at',
            'updated_at',
            'tipo_documento',
            'tipo_doc_fatt_el',
            'condizioni_pagamento',
            'modalita_pagamento',
            'cliente_nazione',
            'cliente_provincia',
            'cliente_cap',
            'cliente_citta',
            'cliente_indirizzo',
            'cliente_ragione_sociale',
            'cliente_company_id',
            'cliente_partita_iva',
            'cliente_codice_fiscale',
            'creato_da',
            'template',
            'file',
            'document_to_state',
            'data_pagamento',
            'anticipo',
            'frase_in_calce',
            'data_scadenza',
            'fattura',
            'mostra_inps',
            'somma_inps',
            'mostra_ritenuta',
            'filexml',
            'filexmlname',
            'ricevuta',
            'ricevutaname',
            'content',
            'id',
            'contributo_inps',
            'ritenuta_di_acconto',
            'iva',
            'imponibile',
            'durata',
            'nome_banca',
            'iban_bonifici',
            'intestatario_conto_corrente',
            'percentuale_ritenuta_di_acconto',
            'percentuale_inps',
            'percentuale_iva',
            'citta',
            'site',
            'cf',
            'p_iva',
            'packages_id',
        );
        $listExclude['user'] = array('ragione_sociale');
        $listExclude['job_suppliers'] = array('costo_orario', 'descrizione');
        $listExclude['job_customer'] = array('costo', 'descrizione');
        $listExclude['siti'] = array('attivo');
        $listExclude['pack_vs_hours'] = array('descrizione', 'stato', 'attivo');
        $listExclude['acquisti'] = array('progressivo_sdi', 'numero_documento', 'attivo', 'pagato', 'descrizione');
        $listExclude['quote'] = array('progressivo_sdi', 'attivo', 'stato_documento', 'codice_fattura', 'pagato', 'descrizione');
        $listExclude['creditMemo'] = array('codice_fattura', 'attivo', 'pagato', 'descrizione');
        $listExclude['proforma'] = array('progressivo_sdi', 'codice_fattura', 'attivo', 'pagato', 'descrizione');
        $listExclude['invoice'] = array('codice_fattura', 'attivo', 'pagato', 'descrizione');
        $listExclude['external_invoice'] = array('codice_fattura', 'iva', 'attivo', 'pagato', 'descrizione');
        return $listExclude;
    }

    public static function getColumns($columns, $type = ''): array
    {
        $tableColumns = [];
        foreach ($columns as $column) {
            if (in_array($column, TableHelper::getExcludedColumns()['default']))
                continue;
            if ($type != '' && in_array($column, TableHelper::getExcludedColumns()[$type]))
                continue;
            $label = isset($column['label']) ? $column['label'] : ucfirst(str_replace('_', ' ', $column));
            $col = TextColumn::make($column)
                ->label($label)
                ->sortable()
                ->formatStateUsing(fn($state) => TableHelper::formatColumnValue($column, $state)['value'])
                ->extraAttributes([
                    'style' => 'max-width:250px; overflow-x:auto; white-space:nowrap;'
                ]);
            if (in_array($column, ['cliente', 'cliente_id'])) {
                $col->searchable(
                    query: function (Builder $query, string $search) use ($column) {
                        $query->whereIn($column, Customer::query()
                            ->select('id')
                            ->where('ragione_sociale', 'like', "%{$search}%")
                        );
                    }
                );
            } else
                $col->searchable();
            TableHelper::decorateColumn($column, $col);
            $tableColumns[] = $col;
        }
        return $tableColumns;
    }

    public static function getTableFilter()
    {
        return [
            SelectFilter::make('anno')
                ->label('Anno')
                ->multiple()
                ->default([date('Y')])
                ->options(
                    DB::table('documenti')
                        ->whereNotNull('data_documento')
                        ->selectRaw('YEAR(data_documento) as anno')
                        ->distinct()
                        ->orderByDesc('anno')
                        ->pluck('anno', 'anno')
                        ->toArray()
                )
                ->query(function ($query, $data) {

                    if (empty($data['values']))
                        return $query;

                    return $query->whereIn(
                        DB::raw('YEAR(data_documento)'),
                        $data['values']
                    );
                }),
        ];
    }

    public static function getTableActions($type = '')
    {
        $actionsDocument = [];
        if ($type == 'invoice' || $type == 'external_invoice' || $type == 'quote' || $type == 'proforma') {
            $actionsDocument = [
                Action::make('print')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->label('')
                    ->button()
                    ->url(fn($record) => route('invoice.print', $record))
                    ->openUrlInNewTab(),
            ];
        }
        $actionsInvoice = [];
        if ($type == 'invoice' || $type == 'external_invoice') {
            $actionsInvoice = [
                Action::make('xml')
                    ->icon('heroicon-o-code-bracket')
                    ->color('warning')
                    ->label('')
                    ->button()
                    ->action(fn($record) => redirect()->route('invoice.xml', $record)),
            ];
        }
        $actionsGeneric = [
            EditAction::make()
                ->color('warning')
                ->label('')
                ->button(),
            DeleteAction::make()
                ->color('danger')
                ->label('')
                ->button()
                ->requiresConfirmation(),

        ];
        $actions = array_merge($actionsDocument, $actionsInvoice, $actionsGeneric);
        return $actions;
    }
}
