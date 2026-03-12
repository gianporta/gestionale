<x-filament-panels::page>
    <x-filament::modal id="result-modal">
        <div class="fi-input block w-full text-center">
            {{ $htResult }}
        </div>

        <x-slot name="footer">
            <x-filament::button
                x-on:click="$dispatch('close-modal', { id: 'result-modal' })"
            >
                Chiudi
            </x-filament::button>
        </x-slot>

    </x-filament::modal>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- USER CREATION --}}
        <x-filament::section>
            <x-slot name="heading">Crea utente CMS</x-slot>

            <div class="space-y-4">

                <div class="space-y-1">
                    <label class="text-sm text-gray-400">CMS</label>

                    <x-filament::input.wrapper>
                        <x-filament::input.select>
                            <option value="m2">Magento 2</option>
                            <option value="m1">Magento 1</option>
                        </x-filament::input.select>
                    </x-filament::input.wrapper>
                </div>

                <div class="space-y-1">
                    <label class="text-sm text-gray-400">Username</label>

                    <x-filament::input.wrapper>
                        <x-filament::input wire:model.defer="cmsUser" placeholder="User"/>
                    </x-filament::input.wrapper>
                </div>

                <div class="space-y-1">
                    <label class="text-sm text-gray-400">Nome</label>

                    <x-filament::input.wrapper>
                        <x-filament::input wire:model.defer="cmsName" placeholder="Nome"/>
                    </x-filament::input.wrapper>
                </div>

                <div class="space-y-1">
                    <label class="text-sm text-gray-400">Cognome</label>

                    <x-filament::input.wrapper>
                        <x-filament::input wire:model.defer="cmsLastname" placeholder="Cognome"/>
                    </x-filament::input.wrapper>
                </div>

                <div class="space-y-1">
                    <label class="text-sm text-gray-400">Email</label>

                    <x-filament::input.wrapper>
                        <x-filament::input wire:model.defer="cmsEmail" placeholder="Email"/>
                    </x-filament::input.wrapper>
                </div>

                <div class="space-y-1">
                    <label class="text-sm text-gray-400">Password</label>

                    <x-filament::input.wrapper>
                        <x-filament::input wire:model.defer="cmsPassword" placeholder="Password"/>
                    </x-filament::input.wrapper>
                </div>

                <x-filament::button
                    type="button"
                    wire:click="generateCmsUserCommand"
                    class="inline-flex items-center justify-center gap-x-2 rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-400"
                >
                    Crea comando
                </x-filament::button>

            </div>
        </x-filament::section>

        {{-- CONVERT PASSWORD --}}
        <x-filament::section>
            <x-slot name="heading">Convert password</x-slot>

            <div class="space-y-4">

                <div class="space-y-1">
                    <label class="text-sm text-gray-400">
                        Password
                    </label>

                    <x-filament::input.wrapper>
                        <x-filament::input
                            wire:model.defer="convertPassword"
                            type="password"
                            placeholder="Password"
                        />
                    </x-filament::input.wrapper>
                </div>

                <div class="space-y-1">
                    <label class="text-sm text-gray-400">
                        Hash type
                    </label>

                    <x-filament::input.wrapper>
                        <x-filament::input.select wire:model.defer="convertType">
                            @foreach(\App\Helpers\FormHelper::getHashTypes() as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </x-filament::input.select>
                    </x-filament::input.wrapper>
                </div>

                <x-filament::button
                    type="button"
                    wire:click="convertPasswordHash"
                    class="inline-flex items-center justify-center gap-x-2 rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-400"
                >
                    Convert
                </x-filament::button>

            </div>
        </x-filament::section>

        {{-- PASSWORD GENERATOR --}}
        <x-filament::section>
            <x-slot name="heading">Create password</x-slot>

            <div class="space-y-4">

                <div class="space-y-1">
                    <label class="text-sm text-gray-400">
                        Numero caratteri
                    </label>

                    <x-filament::input.wrapper>
                        <x-filament::input
                            wire:model.defer="passwordLength"
                            placeholder="12"
                        />
                    </x-filament::input.wrapper>
                </div>

                <div class="space-y-1">
                    <label class="text-sm text-gray-400">
                        Special characters
                    </label>

                    <x-filament::input.wrapper>
                        <x-filament::input.select wire:model.defer="passwordSpecial">
                            @foreach(\App\Helpers\FormHelper::getYesNoOptions() as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </x-filament::input.select>
                    </x-filament::input.wrapper>
                </div>

                <x-filament::button
                    type="button"
                    wire:click="generatePassword"
                    class="inline-flex items-center justify-center gap-x-2 rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-400"
                >
                    Crea
                </x-filament::button>

            </div>
        </x-filament::section>

        {{-- HTP PASSWORD GENERATOR --}}
        <x-filament::section>
            <x-slot name="heading">Htpasswd</x-slot>

            <div class="space-y-4">
                <x-filament::input.wrapper>
                    <x-filament::input
                        wire:model.defer="htUser"
                        placeholder="User"
                    />
                </x-filament::input.wrapper>

                <x-filament::input.wrapper>
                    <x-filament::input
                        wire:model.defer="htPassword"
                        placeholder="Password"
                    />
                </x-filament::input.wrapper>

                <x-filament::button
                    type="button"
                    wire:click="generateHtpasswd"
                    class="inline-flex items-center justify-center gap-x-2 rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-400"
                >
                    Crea
                </x-filament::button>

            </div>
        </x-filament::section>

        {{-- PHP VERSION --}}
        <x-filament::section>
            <x-slot name="heading">Cambia versione PHP</x-slot>

            <div class="space-y-4">

                <div class="space-y-1">
                    <label class="text-sm text-gray-400">
                        Version From
                    </label>

                    <x-filament::input.wrapper>
                        <x-filament::input.select wire:model.defer="phpFrom">
                            @foreach(\App\Helpers\FormHelper::getPhpVersions() as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </x-filament::input.select>
                    </x-filament::input.wrapper>
                </div>

                <div class="space-y-1">
                    <label class="text-sm text-gray-400">
                        Version To
                    </label>

                    <x-filament::input.wrapper>
                        <x-filament::input.select wire:model.defer="phpTo">
                            @foreach(\App\Helpers\FormHelper::getPhpVersions() as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </x-filament::input.select>
                    </x-filament::input.wrapper>
                </div>

                <x-filament::button
                    type="button"
                    wire:click="switchPhp"
                    class="inline-flex items-center justify-center gap-x-2 rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-400 focus:outline-none"
                >
                    Crea comando
                </x-filament::button>

            </div>
        </x-filament::section>

        {{-- COUNT CHAR --}}
        <x-filament::section>
            <x-slot name="heading">Count char</x-slot>
            <div class="space-y-4">
                <x-filament::input.wrapper>
                    <x-filament::input
                        wire:model.defer="countString"
                        placeholder="String"
                    />
                </x-filament::input.wrapper>
                <x-filament::button
                    type="button"
                    wire:click="countChars"
                    class="inline-flex items-center justify-center gap-x-2 rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-400 focus:outline-none"
                >
                    Conta
                </x-filament::button>
            </div>
        </x-filament::section>

    </div>

</x-filament-panels::page>
