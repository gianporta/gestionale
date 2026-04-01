<?php

namespace App\Helpers;

use App\Models\Caps;
use App\Models\Categoria;
use App\Models\Cms;
use App\Models\Comuni;
use App\Models\CondizioniPagamento;
use App\Models\Countries;
use App\Models\CreditMemo;
use App\Models\Customer;
use App\Models\ExternalInvoice;
use App\Models\Invoice;
use App\Models\Job;
use App\Models\ModalitaPagamento;
use App\Models\Packages;
use App\Models\Proforma;
use App\Models\Provinces;
use App\Models\Repo;
use App\Models\Site;
use App\Models\StatePayment;
use App\Models\StatoTask;
use App\Models\Stime;
use App\Models\Task;
use App\Models\TipoAcquisto;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
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

    private static function getClienteOptions($currentId = null): array
    {
        $query = Customer::query()
            ->where('tipo_cliente', Customer::TYPE_CUSTOMER_CUSTOMER)
            ->where('attivo', 1)
            ->whereHas('jobs', function ($q) {
                $q->where('stato_job', '!=', Job::STATO_CHIUSO);
            });
        if ($currentId)
            $query->orWhere('id', $currentId);
        return $query
            ->orderBy('ragione_sociale')
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
            case 'pagato':
                return [
                    'type' => 'text',
                    'default' => NULL,
                    'disabled_callback' => fn(Get $get) => empty($get('data_pagamento')),
                ];
            case 'packages_id':
            case 'imponibile':
            case 'contributo_inps':
            case 'iva':
            case 'ritenuta_di_acconto':
            case 'netto_a_pagare':
                return [
                    'type' => 'text',
                    'readonly' => true,
                ];
            case 'banca':
                return [
                    'type' => 'text',
                    'readonly' => true,
                    'default' => auth()->user()->nome_banca ?? null,
                ];
            case 'iban':
                return [
                    'type' => 'text',
                    'readonly' => true,
                    'default' => auth()->user()->iban_bonifici ?? null,
                ];
            case 'intestatario_conto':
                return [
                    'type' => 'text',
                    'readonly' => true,
                    'default' => auth()->user()->intestatario_conto_corrente ?? null,
                ];
            case 'numero_documento':
                echo "<pre>";print_r(request()->array());die;
                $numDoc = 0;
                if(request()->routeIs('filament.admin.resources.quote.create'))
                    Quote::getNextNumeroDocumento();
                elseif(request()->routeIs('filament.admin.resources.creditmemo.create'))
                    CreditMemo::getNextNumeroDocumento();
                elseif(request()->routeIs('filament.admin.resources.externalinvoice.create'))
                    ExternalInvoice::getNextNumeroDocumento();
                elseif(request()->routeIs('filament.admin.resources.proformas.create'))
                    Proforma::getNextNumeroDocumento();
                elseif(request()->routeIs('filament.admin.resources.invoice.create'))
                    Invoice::getNextNumeroDocumento();
                return [
                    'type' => 'text',
                    'readonly' => true,
                    'default' => $numDoc
                ];
            case 'progressivo_sdi':
                return [
                    'type' => 'text',
                    'readonly' => true,
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
            case 'cms':
                return [
                    'type' => 'select',
                    'options' =>
                        Cms::query()
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
                    'options' => Customer::getTypeCustomer(),
                    'default' => request()->routeIs('filament.admin.resources.suppliers.*') ? 1 : 2,
                ];
            case 'ambiente':
                return [
                    'type' => 'select',
                    'options' => Site::getAmbiente(),
                    'default' => 1
                ];
            case 'categoria':
                return [
                    'type' => 'select',
                    'options' => Categoria::query()
                        ->pluck('nome', 'id')
                        ->toArray()
                ];
            case 'data_scadenza':
                return [
                    'type' => 'date',
                    'default' => fn(Get $get) => $get('data_documento')
                        ? \Carbon\Carbon::parse($get('data_documento'))->addDays(30)->toDateString()
                        : now()->addDays(30)->toDateString(),
                ];
            case 'data_pagamento':
                return [
                    'type' => 'date',
                    'default' => null,
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
            case 'mostra_ritenuta':
            case 'mostra_inps':
                return [
                    'type' => 'select',
                    'options' => [
                        0 => 'No',
                        1 => 'Sì',
                    ],
                    'default' => 1,
                    'reactive' => true,
                    'afterStateUpdated' => fn(Get $get, Set $set) => self::updateTotali($get, $set),
                ];
            case 'cloudflare':
            case 'sucuri':
            case 'varnish':
            case 'opcache':
            case 'redis':
            case 'enable_ip':
            case 'vpn':
            case 'tunnel_ssh':
            case 'proforma':
            case 'fatturato':
            case 'saldato':
                return [
                    'type' => 'select',
                    'options' => [
                        0 => 'No',
                        1 => 'Sì',
                    ],
                    'default' => 0,
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
            case 'somma_inps':
                return [
                    'type' => 'select',
                    'options' => [
                        0 => 'No',
                        1 => 'Sì',
                    ],
                    'default' => 0,
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
            case 'tipo_acquisto':
                return [
                    'type' => 'select',
                    'options' => TipoAcquisto::query()
                        ->pluck('nome', 'id')
                        ->toArray(),
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
                return [
                    'type' => 'select',
                    'options' => fn(Get $get) => self::getClienteOptions($get('cliente')),
                    'reactive' => true,
                    'afterStateUpdated' => function ($state, Set $set) {
                        $set('content', [
                            [
                                'descrizione' => null,
                                'ore' => null,
                                'costo' => null,
                                'imponibile' => 0,
                            ]
                        ]);
                    }
                ];
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
            case 'num_ore':
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
            case 'ssh_key':
            case 'descrizione':
            case 'comando':
            case 'sql':
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
            case 'condizioni_pagamento':
                return [
                    'type' => 'select',
                    'options' => CondizioniPagamento::pluck('nome', 'id')->toArray(),
                    'default' => 1
                ];
            case 'modalita_pagamento':
                return [
                    'type' => 'select',
                    'options' => ModalitaPagamento::pluck('nome', 'id')->toArray(),
                    'default' => 1
                ];
            case 'document_to_state':
                return [
                    'type' => 'select',
                    'options' => Countries::pluck('country_name', 'country_code')->toArray(),
                    'default' => 'IT'
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
            case 'cliente_nazione':
            case 'nazione':
                return [
                    'type' => 'country',
                ];
            case 'cliente_provincia':
            case 'provincia':
                return [
                    'type' => 'province',
                ];
            case 'cliente_citta':
            case 'citta':
                return [
                    'type' => 'city',
                ];
            case 'cliente_cap':
            case 'cap':
                return [
                    'type' => 'cap',
                ];
            case 'content':
                return [
                    'type' => 'repeater_attivita'
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
                        ->default(fn(Get $get) => $get($column) ?: 'IT')
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
                    if (!empty($config['reactive']))
                        $field->live();
                    if (isset($config['afterStateUpdated']))
                        $field->afterStateUpdated($config['afterStateUpdated']);
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
                case 'repeater_attivita':
                    $field = Repeater::make('content')
                        ->label('')
                        ->live()
                        ->schema([

                            Select::make('descrizione')
                                ->label('Attività')
                                ->columnSpan(4)
                                ->options(function (Get $get) {
                                    $cliente = $get('../../cliente');
                                    $current = $get('descrizione');
                                    return Job::query()
                                        ->where('cliente', $cliente)
                                        ->where(function ($q) use ($current) {
                                            $q->where('stato_job', '!=', Job::STATO_CHIUSO);
                                            if ($current)
                                                $q->orWhere('id', $current);
                                        })
                                        ->selectRaw("id, CONCAT(nome, ' - ', descrizione) as label")
                                        ->pluck('label', 'id');
                                })
                                ->searchable()
                                ->live()
                                ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                    $job = Job::find($state);
                                    if (!$job)
                                        return;
                                    $set('costo', (float)($job->costo_orario ?? 0));
                                    if (($get('ore') === null || $get('ore') === '' || (float)$get('ore') === 0) && !empty($job->num_ore))
                                        $set('ore', (float)$job->num_ore);
                                    self::updateRiga($get, $set);
                                    self::updateTotali($get, $set);
                                }),

                            TextInput::make('ore')
                                ->label('Ore')
                                ->columnSpan(1)
                                ->numeric()
                                ->live(debounce: 300)
                                ->afterStateUpdated(function (Get $get, Set $set) {
                                    self::updateRiga($get, $set);
                                    self::updateTotali($get, $set);
                                }),

                            TextInput::make('costo')
                                ->label('Costo')
                                ->columnSpan(1)
                                ->numeric()
                                ->live(debounce: 300)
                                ->afterStateUpdated(function (Get $get, Set $set) {
                                    self::updateRiga($get, $set);
                                    self::updateTotali($get, $set);
                                }),

                            TextInput::make('imponibile')
                                ->label('Imponibile')
                                ->columnSpan(1)
                                ->numeric()
                                ->readOnly(),
                        ])
                        ->columns(7)
                        ->defaultItems(1)
                        ->addActionLabel('Aggiungi riga');
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
                    if (isset($config['disabled_callback']))
                        $field->disabled($config['disabled_callback']);
                    break;
                case 'text':
                default:
                    $field = TextInput::make($column)
                        ->label(ucfirst(str_replace('_', ' ', $column)))
                        ->required(false);
                    if (isset($config['default']))
                        $field->default($config['default']);
                    if (isset($config['disabled_callback']))
                        $field->disabled($config['disabled_callback']);
                    break;
            }
            if (!empty($config['disabled']))
                $field->disabled();
            if (!empty($config['readonly'])) {
                $field->readOnly();
            }
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
            'totale_ore_lavorate'
        );
        $listExclude['job_suppliers'] = array('costo_orario');
        $listExclude['job_customer'] = array('costo');
        $listExclude['customer'] = array('banca', 'iban', 'intestatario_conto');
        $listExclude['acquisti'] = array();
        $listExclude['quote'] = array('condizioni_pagamento', 'modalita_pagamento', 'stato_documento', 'data_pagamento', 'anticipo', 'pagato');
        $listExclude['creditMemo'] = array();
        $listExclude['proforma'] = array('anticipo');
        $listExclude['invoice'] = array('anticipo', 'descrizione', 'tipo_documento', 'codice_fattura', 'tipo_doc_fatt_el', 'template', 'file', 'filexml', 'ricevuta', 'filexmlname', 'ricevutaname', 'user', 'attivo', 'creato_da');
        $listExclude['externalInvoice'] = array();
        return $listExclude;
    }

    private static function updateRiga(Get $get, Set $set): void
    {
        $ore = (float)($get('ore') ?? 0);
        $costo = (float)($get('costo') ?? 0);

        $set('imponibile', round($ore * $costo, 2));
    }

    private static function updateTotali(Get $get, Set $set): void
    {
        $rows = collect($get('../../content') ?? []);

        $imponibile = $rows->sum(fn($row) => (float)($row['imponibile'] ?? 0));
        $set('../../imponibile', round($imponibile, 2));

        $user = auth()->user();

        $inpsPerc = (float)($user->percentuale_inps ?? 0);
        $ritenutaPerc = (float)($user->percentuale_ritenuta_di_acconto ?? 0);
        $ivaPerc = (float)($user->percentuale_iva ?? 22);

        $mostraInps = (int)($get('../../mostra_inps') ?? 0);
        $mostraRitenuta = (int)($get('../../mostra_ritenuta') ?? 0);

        $inps = $mostraInps ? ($imponibile * $inpsPerc / 100) : 0;
        $set('../../contributo_inps', round($inps, 2));

        $baseContributiva = $imponibile + $inps;

        $iva = $baseContributiva * $ivaPerc / 100;
        $set('../../iva', round($iva, 2));

        $ritenuta = $mostraRitenuta ? ($baseContributiva * $ritenutaPerc / 100) : 0;
        $set('../../ritenuta_di_acconto', round($ritenuta, 2));

        $netto = $imponibile + $inps + $iva - $ritenuta;
        $set('../../netto_a_pagare', round($netto, 2));
    }
}

