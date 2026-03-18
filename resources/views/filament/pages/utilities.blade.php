<x-filament-panels::page>
    <x-filament::modal id="result-modal">
        <div class="space-y-4">

            <div class="text-sm text-gray-400">
                Risultato
            </div>

            <div class="bg-gray-900 rounded-lg p-3 overflow-x-auto">
<pre class="text-xs font-mono whitespace-pre-wrap break-all">
{{ $htResult }}
</pre>
            </div>

            <div class="flex justify-end gap-2">
                <x-filament::button
                    color="gray"
                    x-on:click="navigator.clipboard.writeText(`{{ $htResult }}`)"
                >
                    Copia
                </x-filament::button>

                <x-filament::button
                    x-on:click="$dispatch('close-modal', { id: 'result-modal' })"
                >
                    Chiudi
                </x-filament::button>
            </div>

        </div>
    </x-filament::modal>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

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

                <x-filament::input.wrapper>
                    <x-filament::input wire:model.defer="cmsUser" placeholder="User"/>
                </x-filament::input.wrapper>

                <x-filament::input.wrapper>
                    <x-filament::input wire:model.defer="cmsName" placeholder="Nome"/>
                </x-filament::input.wrapper>

                <x-filament::input.wrapper>
                    <x-filament::input wire:model.defer="cmsLastname" placeholder="Cognome"/>
                </x-filament::input.wrapper>

                <x-filament::input.wrapper>
                    <x-filament::input wire:model.defer="cmsEmail" placeholder="Email"/>
                </x-filament::input.wrapper>

                <x-filament::input.wrapper>
                    <x-filament::input wire:model.defer="cmsPassword" placeholder="Password"/>
                </x-filament::input.wrapper>

                <x-filament::button type="button" wire:click="generateCmsUserCommand" class="bg-amber-500 hover:bg-amber-400 text-white">
                    Crea comando
                </x-filament::button>

            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Convert password</x-slot>

            <div class="space-y-4">

                <x-filament::input.wrapper>
                    <x-filament::input wire:model.defer="convertPassword" type="password" placeholder="Password"/>
                </x-filament::input.wrapper>

                <x-filament::input.wrapper>
                    <x-filament::input.select wire:model.defer="convertType">
                        @foreach(\App\Helpers\FormHelper::getHashTypes() as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </x-filament::input.select>
                </x-filament::input.wrapper>

                <x-filament::button type="button" wire:click="convertPasswordHash" class="bg-amber-500 hover:bg-amber-400 text-white">
                    Convert
                </x-filament::button>

            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Create password</x-slot>

            <div class="space-y-4">

                <x-filament::input.wrapper>
                    <x-filament::input wire:model.defer="passwordLength" placeholder="12"/>
                </x-filament::input.wrapper>

                <x-filament::input.wrapper>
                    <x-filament::input.select wire:model.defer="passwordSpecial">
                        @foreach(\App\Helpers\FormHelper::getYesNoOptions() as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </x-filament::input.select>
                </x-filament::input.wrapper>

                <x-filament::button type="button" wire:click="generatePassword" class="bg-amber-500 hover:bg-amber-400 text-white">
                    Crea
                </x-filament::button>

            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Htpasswd</x-slot>

            <div class="space-y-4">

                <x-filament::input.wrapper>
                    <x-filament::input wire:model.defer="htUser" placeholder="User"/>
                </x-filament::input.wrapper>

                <x-filament::input.wrapper>
                    <x-filament::input wire:model.defer="htPassword" placeholder="Password"/>
                </x-filament::input.wrapper>

                <x-filament::button type="button" wire:click="generateHtpasswd" class="bg-amber-500 hover:bg-amber-400 text-white">
                    Crea
                </x-filament::button>

            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Cambia versione PHP</x-slot>

            <div class="space-y-4">

                <x-filament::input.wrapper>
                    <x-filament::input.select wire:model.defer="phpFrom">
                        @foreach(\App\Helpers\FormHelper::getPhpVersions() as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </x-filament::input.select>
                </x-filament::input.wrapper>

                <x-filament::input.wrapper>
                    <x-filament::input.select wire:model.defer="phpTo">
                        @foreach(\App\Helpers\FormHelper::getPhpVersions() as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </x-filament::input.select>
                </x-filament::input.wrapper>

                <x-filament::button type="button" wire:click="switchPhp" class="bg-amber-500 hover:bg-amber-400 text-white">
                    Crea comando
                </x-filament::button>

            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Count char</x-slot>

            <div class="space-y-4">

                <x-filament::input.wrapper>
                    <x-filament::input wire:model.defer="countString" placeholder="String"/>
                </x-filament::input.wrapper>

                <x-filament::button type="button" wire:click="countChars" class="bg-amber-500 hover:bg-amber-400 text-white">
                    Conta
                </x-filament::button>

            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Install Magento</x-slot>

            <div class="space-y-4">

                <div class="space-y-1">
                    <label class="text-sm text-gray-400">Tipo</label>
                    <x-filament::input.wrapper>
                        <x-filament::input.select wire:model.defer="magentoType">
                            <option value="m2">Magento 2</option>
                            <option value="mageos">Mage-OS</option>
                        </x-filament::input.select>
                    </x-filament::input.wrapper>
                </div>

                <x-filament::input.wrapper>
                    <x-filament::input wire:model.defer="magentoDir" placeholder="Directory (es: magento)" />
                </x-filament::input.wrapper>

                <x-filament::input.wrapper>
                    <x-filament::input wire:model.defer="magentoBaseUrl" placeholder="Base URL" />
                </x-filament::input.wrapper>

                <x-filament::input.wrapper>
                    <x-filament::input wire:model.defer="magentoDbHost" placeholder="DB Host" />
                </x-filament::input.wrapper>

                <x-filament::input.wrapper>
                    <x-filament::input wire:model.defer="magentoDbName" placeholder="DB Name" />
                </x-filament::input.wrapper>

                <x-filament::input.wrapper>
                    <x-filament::input wire:model.defer="magentoDbUser" placeholder="DB User" />
                </x-filament::input.wrapper>

                <x-filament::input.wrapper>
                    <x-filament::input wire:model.defer="magentoDbPassword" placeholder="DB Password" />
                </x-filament::input.wrapper>

                <x-filament::input.wrapper>
                    <x-filament::input wire:model.defer="magentoAdminFirstname" placeholder="Admin Firstname" />
                </x-filament::input.wrapper>

                <x-filament::input.wrapper>
                    <x-filament::input wire:model.defer="magentoAdminLastname" placeholder="Admin Lastname" />
                </x-filament::input.wrapper>

                <x-filament::input.wrapper>
                    <x-filament::input wire:model.defer="magentoAdminEmail" placeholder="Admin Email" />
                </x-filament::input.wrapper>

                <x-filament::input.wrapper>
                    <x-filament::input wire:model.defer="magentoAdminUser" placeholder="Admin User" />
                </x-filament::input.wrapper>

                <x-filament::input.wrapper>
                    <x-filament::input wire:model.defer="magentoAdminPassword" placeholder="Admin Password" />
                </x-filament::input.wrapper>

                <x-filament::button
                    type="button"
                    wire:click="generateMagentoInstall"
                    class="bg-amber-500 hover:bg-amber-400 text-white"
                >
                    Genera comando
                </x-filament::button>

            </div>
        </x-filament::section>

    </div>
</x-filament-panels::page>
