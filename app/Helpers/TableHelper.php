<?php

namespace App\Helpers;

use App\Models\Cms;
use App\Models\Customer;
use App\Models\Categoria;
use App\Models\Task;
use Filament\Tables\Columns\TextColumn;

class TableHelper
{
    public static function formatColumnValue(string $column, mixed $value): mixed
    {
        switch ($column) {
            case 'is_active':
            case 'attivo':
                return ($value == 0) ? 'No' : 'Sì';
            case 'ambiente':
                return ($value == 0) ? 'Produzione' : 'Staging';
            case 'categoria':
                return Categoria::find($value)?->nome;
            case 'task_id':
                return Task::find($value)?->task;
            case 'cms':
                return Cms::find($value)?->nome;
            case 'cliente_id':
                return Customer::find($value)?->ragione_sociale;
            case 'stato':
                return self::getStatusOptions()[$value] ?? $value;
            default:
                return $value;
        }
    }
    public static function getStatusOptions(): array
    {
        return [
            '1' => 'In lavorazione',
            '2' => 'Da Testare',
            '3' => 'Finito',
        ];
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
                    ->color(fn ($state) => match ($state) {
                        1 => 'warning',
                        2 => 'info',
                        3 => 'success',
                        default => 'gray',
                    });
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
        return [
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
            'data_creazione',
            'data_modifica',
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
            'cliente',
            'costo_orario',
            'created_at',
            'updated_at',
        ];
    }
}
