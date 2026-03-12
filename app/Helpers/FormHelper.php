<?php

namespace App\Helpers;

use App\Models\Customer;
use App\Models\Packages;
use App\Models\Task;
use App\Models\User;
use App\Models\Countries;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Provinces;
use App\Models\Comuni;
use App\Models\Caps;
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

    public static function getSstatusOptions(): array
    {
        return [
            '1' => 'In lavorazione',
            '2' => 'Da Testare',
            '3' => 'Finito',
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
            case 'tipo_cliente':
                return [
                    'type' => 'select',
                    'options' => [
                        1 => 'Fornitore',
                        2 => 'Cliente',
                    ],
                    'default' => request()->routeIs('filament.admin.resources.suppliers.*') ? 1 : 2,
                ];
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
            case 'stato':
                return [
                    'type' => 'select',
                    'options' => self::getSstatusOptions(),
                ];
            case 'cliente_id':
                return [
                    'type' => 'select',
                    'options' => self::getClienteOptions(),
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
            case 'ore_lavorate':
                return [
                    'type' => 'number',
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

    public static function getFieldForm($columns): array
    {
        $formSchema = [];
        foreach ($columns as $column) {
            if (in_array($column, FormHelper::getExcludedColumns()))
                continue;
            $config = FormHelper::getFormFieldConfig($column);
            switch ($config['type']) {
                case 'country':
                    $field = Select::make($column)
                        ->label('Nazione')
                        ->options(fn () =>
                        Countries::orderBy('country_name')
                            ->pluck('country_name', 'country_code')
                        )
                        ->default('IT')
                        ->disabled(fn ($record) => $record !== null)
                        ->searchable()
                        ->preload()
                        ->reactive()
                        ->afterStateUpdated(fn (Set $set) => $set('provincia', null));
                    break;
                case 'province':
                    $field = Select::make($column)
                        ->label('Provincia')
                        ->options(fn (Get $get) =>
                        Provinces::where('country_code', $get('nazione'))
                            ->pluck('prov', 'sigla')
                        )
                        ->searchable()
                        ->reactive()
                        ->afterStateUpdated(fn (Set $set) => $set('citta', null));
                    break;
                case 'city':
                    $field = Select::make($column)
                        ->label('Città')
                        ->options(fn (Get $get) =>
                        Comuni::where('provincia', $get('provincia'))
                            ->pluck('comune', 'comune')
                        )
                        ->searchable()
                        ->reactive()
                        ->afterStateUpdated(fn (Set $set) => $set('cap', null));
                    break;
                case 'cap':
                    $field = Select::make($column)
                        ->label('CAP')
                        ->options(fn (Get $get) =>
                        Comuni::where('comune', $get('citta'))
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
                        ->rows(4)
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
                case 'text':
                default:
                    $field = TextInput::make($column)
                        ->label(ucfirst(str_replace('_', ' ', $column)))
                        ->required(false);

                    if (isset($config['default']))
                        $field->default($config['default']);
                    break;
            }
            $formSchema[$column] = $field;
        }
        return $formSchema;
    }

    public static function getExcludedColumns(): array
    {
        return ['id', 'created_at', 'updated_at', 'remember_token', 'email_verified_at', 'two_factor_secret', 'two_factor_recovery_codes', 'two_factor_confirmed_at'];
    }
}
