<x-filament::widget>
    <x-filament::card>
        <h2 class="text-lg font-bold mb-3">
            Configurazione Repo su composer
        </h2>
        <pre class="bg-gray-900 text-gray-200 text-sm rounded p-4 overflow-x-auto mb-3">
composer config repositories.threecommerce composer https://repo.threecommerce.it
        </pre>
        <h2 class="text-lg font-bold mb-3">
            Da non condividere con il cliente le credenziali, in quanto dovrà avere le use
        </h2>
        <pre class="bg-gray-900 text-gray-200 text-sm rounded p-4 overflow-x-auto">
composer config --auth http-basic.repo.threecommerce.it threecommerce 'Thr33C0mm3r€'
composer require threecommerce/.....
        </pre>
    </x-filament::card>
</x-filament::widget>
