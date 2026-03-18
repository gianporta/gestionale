<?php

namespace App\Helpers;

use App\Models\Caps;
use App\Models\Comuni;
use App\Models\Countries;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Packages;
use App\Models\Provinces;
use App\Models\Repo;
use App\Models\Job;
use App\Models\StatePayment;
use App\Models\StatoTask;
use App\Models\Stime;
use App\Models\Task;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;

class FormHelper
{
    public static function getHashTypes(): array
    {
        return [
            'md5' => 'MD5',
            'bcrypt' => 'Bcrypt',
        ];
    }

    public static function getYesNoOptions(): array
    {
        return [
            0 => 'No',
            1 => 'Sì',
        ];
    }

    public static function getCmsOptions(): array
    {
        return [
            'm2' => 'Magento 2',
            'm1' => 'Magento 1',
            'wp' => 'Wordpress',
        ];
    }

    public static function getPhpVersions(): array
    {
        return [
            'php@7.2' => 'PHP 7.2',
            'php@7.4' => 'PHP 7.4',
            'php@8.0' => 'PHP 8.0',
            'php@8.1' => 'PHP 8.1',
            'php@8.3' => 'PHP 8.3',
            'php@8.4' => 'PHP 8.4',
        ];
    }

    private static function getUserOptions(): array
    {
        return User::query()
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    private static function getClienteOptions(): array
    {
        return Customer::query()
            ->where('tipo_cliente', 2)
            ->where('attivo', 1)
            ->pluck('ragione_sociale', 'id')
            ->toArray();
    }

    private static function getTaskOptions(): array
    {
        return Task::query()
            ->where('attivo', 1)
            ->pluck('task', 'id')
            ->toArray();
    }

    private static function getPackagesActive(): array
    {
        return Packages::query()
            ->join('customers', 'customers.id', '=', 'packages.cliente_id')
            ->where('packages.attivo', 1)
            ->selectRaw("packages.id, CONCAT(customers.ragione_sociale, ' - ', packages.nome) as label")
            ->pluck('label', 'packages.id')
            ->toArray();
    }

    private static function getOreOptions(): array
    {
        return [
            10 => '10',
            20 => '20',
            30 => '30',
            50 => '50',
            100 => '100',
        ];
    }

    public static function getFormFieldConfig(string $column): array
    {
        switch ($column) {
            case 'numero_documento':
                return [
                    'type' => 'text',
                    'disabled' => true,
                    'default' => Invoice::getNextNumeroDocumento()
                ];
            case 'progressivo_sdi':
                return [
                    'type' => 'text',
                    'disabled' => true,
                    'default' => Invoice::getNextProgressivoSdi()
                ];
            case 'stima':
                return [
                    'type' => 'select',
                    'options' =>
                        Stime::query()
                            ->pluck('nome', 'id')
                            ->toArray(),
                    'default' => 1];
            case 'stato_documento':
                return [
                    'type' => 'select',
                    'options' =>
                        StatePayment::query()
                            ->pluck('nome', 'id')
                            ->toArray(),
                    'default' => 1];
            case 'tipo_cliente':
                return [
                    'type' => 'select',
                    'options' => [
                        1 => 'Fornitore',
                        2 => 'Cliente',
                    ],
                    'default' => request()->routeIs('filament.admin.resources.suppliers.*') ? 1 : 2,
                ];
            case 'data_documento':
            case 'data_lavorazione':
                return [
                    'type' => 'date',
                    'default' => now()->toDateString(),
                ];
            case 'guard_name':
                return [
                    'type' => 'text',
                    'default' => 'web'
                ];
            case 'attivo':
            case 'is_active':
                return [
                    'type' => 'select',
                    'options' => [
                        0 => 'No',
                        1 => 'Sì',
                    ],
                    'default' => 1,
                ];
            case 'pacchetto_id':
                return [
                    'type' => 'select',
                    'options' => self::getPackagesActive(),
                ];
            case 'ore':
                return [
                    'type' => 'select',
                    'options' => self::getOreOptions(),
                ];
            case 'stato_job':
                return [
                    'type' => 'select',
                    'options' => Job::getStatoJob(),
                    'default' => 1
                ];
            case 'stato':
                return [
                    'type' => 'select',
                    'options' => StatoTask::query()
                        ->pluck('nome', 'id')
                        ->toArray(),
                    'default' => 1
                ];
            case 'cliente':
            case 'cliente_id':
                return [
                    'type' => 'select',
                    'options' => self::getClienteOptions(),
                    'default' => null,
                ];
            case 'task_id':
                return [
                    'type' => 'select',
                    'options' => self::getTaskOptions(),
                ];
            case 'expiration':
                return [
                    'type' => 'datetime',
                ];
            case 'psw':
                return [
                    'type' => 'password',
                ];
            case 'costo':
            case 'max_ore':
            case 'ore_lavorate':
                return [
                    'type' => 'number',
                ];
            case 'costo_orario':
                return [
                    'type' => 'number',
                    'default' => 50,
                ];
            case 'descrizione':
            case 'note':
                return [
                    'type' => 'textarea',
                ];
            case 'email':
                return [
                    'type' => 'email',
                ];
            case 'user_id':
                return [
                    'type' => 'multiselect',
                    'options' => self::getUserOptions(),
                    'default' => auth()->id(),
                ];
            case 'id_repo':
                return [
                    'type' => 'select',
                    'options' => Repo::pluck('packages', 'id')->toArray()
                ];
            case 'id_user':
            case 'user':
                return [
                    'type' => 'select',
                    'options' => self::getUserOptions(),
                    'default' => auth()->id(),
                ];
            case 'country_code':
                return [
                    'type' => 'select',
                    'options' => Countries::query()
                        ->orderBy('country_name')
                        ->pluck('country_name', 'country_code')
                        ->toArray(),
                    'default' => 'IT',
                ];
            case 'nazione':
                return [
                    'type' => 'country',
                ];
            case 'provincia':
                return [
                    'type' => 'province',
                ];
            case 'citta':
                return [
                    'type' => 'city',
                ];
            case 'cap':
                return [
                    'type' => 'cap',
                ];
            default:
                return [
                    'type' => 'text',
                ];
        }
    }

    public static function getFieldForm($columns, $type = ''): array
    {
        $formSchema = [];
        foreach ($columns as $column) {
            if (in_array($column, FormHelper::getExcludedField()['default']))
                continue;
            if ($type != '' && in_array($column, FormHelper::getExcludedField()[$type]))
                continue;
            $config = FormHelper::getFormFieldConfig($column);
            switch ($config['type']) {
                case 'country':
                    $field = Select::make($column)
                        ->label('Nazione')
                        ->options(fn() => Countries::orderBy('country_name')
                            ->pluck('country_name', 'country_code')
                        )
                        ->default('IT')
                        ->disabled(fn($record) => $record !== null)
                        ->searchable()
                        ->preload()
                        ->reactive()
                        ->afterStateUpdated(fn(Set $set) => $set('provincia', null));
                    break;
                case 'province':
                    $field = Select::make($column)
                        ->label('Provincia')
                        ->options(fn(Get $get) => Provinces::where('country_code', $get('nazione'))
                            ->pluck('prov', 'sigla')
                        )
                        ->searchable()
                        ->reactive()
                        ->afterStateUpdated(fn(Set $set) => $set('citta', null));
                    break;
                case 'city':
                    $field = Select::make($column)
                        ->label('Città')
                        ->options(fn(Get $get) => Comuni::where('provincia', $get('provincia'))
                            ->pluck('comune', 'comune')
                        )
                        ->searchable()
                        ->reactive()
                        ->afterStateUpdated(fn(Set $set) => $set('cap', null));
                    break;
                case 'cap':
                    $field = Select::make($column)
                        ->label('CAP')
                        ->options(fn(Get $get) => Comuni::where('comune', $get('citta'))
                            ->pluck('cap', 'cap')
                        )
                        ->searchable()
                        ->reactive();
                    break;
                case 'multiselect':
                    $field = Select::make($column)
                        ->label(ucfirst(str_replace('_', ' ', $column)))
                        ->options($config['options'])
                        ->multiple()
                        ->searchable()
                        ->required(false);
                    if (isset($config['default']))
                        $field->default([$config['default']]);
                    break;
                case 'select':
                    $field = Select::make($column)
                        ->label(ucfirst(str_replace('_', ' ', $column)))
                        ->options($config['options'])
                        ->searchable()
                        ->required(false);

                    if (isset($config['default']))
                        $field->default($config['default']);
                    break;
                case 'datetime':
                    $field = DateTimePicker::make($column)
                        ->label(ucfirst(str_replace('_', ' ', $column)))
                        ->required(false);
                    break;
                case 'date':
                    $field = DatePicker::make($column)
                        ->label(ucfirst(str_replace('_', ' ', $column)))
                        ->required(false);
                    if (isset($config['default']))
                        $field->default($config['default']);
                    break;
                case 'password':
                    $field = TextInput::make($column)
                        ->label(ucfirst(str_replace('_', ' ', $column)))
                        ->password()
                        ->required(false);
                    break;
                case 'textarea':
                    $field = Textarea::make($column)
                        ->label(ucfirst(str_replace('_', ' ', $column)))
                        ->rows(10)
                        ->required(false);
                    if (isset($config['default']))
                        $field->default($config['default']);
                    break;
                case 'relationship':
                    $field = Select::make($column)
                        ->label(ucfirst(str_replace('_', ' ', $column)))
                        ->relationship($column, $config['label'])
                        ->searchable()
                        ->preload()
                        ->required(false);
                    if (isset($config['default']))
                        $field->default($config['default']);
                    break;
                case 'decimal':
                    $field = TextInput::make($column)
                        ->label(ucfirst(str_replace('_', ' ', $column)))
                        ->numeric()
                        ->step(0.01)
                        ->required(false);
                    if (isset($config['default']))
                        $field->default($config['default']);
                    break;
                case 'number':
                    $field = TextInput::make($column)
                        ->label(ucfirst(str_replace('_', ' ', $column)))
                        ->numeric()
                        ->required(false);
                    if (isset($config['default']))
                        $field->default($config['default']);
                    break;
                case 'text':
                default:
                    $field = TextInput::make($column)
                        ->label(ucfirst(str_replace('_', ' ', $column)))
                        ->required(false);

                    if (isset($config['default']))
                        $field->default($config['default']);
                    break;
            }
            if (!empty($config['disabled']))
                $field->disabled();
            $formSchema[$column] = $field;
        }
        return $formSchema;
    }

    public static function getExcludedField(): array
    {
        $listExclude['default'] = array(
            'id',
            'created_at',
            'updated_at',
            'remember_token',
            'email_verified_at',
            'two_factor_secret',
            'two_factor_recovery_codes',
            'two_factor_confirmed_at',
            'durata',
        );
        $listExclude['job_suppliers'] = array('costo_orario');
        $listExclude['job_customer'] = array('costo');
        $listExclude['customer'] = array('banca', 'iban', 'intestatario_conto');
        $listExclude['acquisti'] = array();
        $listExclude['quote'] = array();
        $listExclude['creditMemo'] = array();
        $listExclude['proforma'] = array();
        $listExclude['invoice'] = array('tipo_documento', 'codice_fattura', 'tipo_doc_fatt_el', 'template', 'file', 'filexml', 'ricevuta', 'filexmlname', 'ricevutaname', 'user', 'attivo', 'creato_da');
        $listExclude['externalInvoice'] = array();
        return $listExclude;
    }
}

