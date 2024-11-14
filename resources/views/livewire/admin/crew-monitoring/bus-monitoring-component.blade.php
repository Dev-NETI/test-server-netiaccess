<x-container title="Bus Monitoring" subTitle="Monitoring">
    <x-button wire:click="local"
        class="btn btn-sm mb-2 {{ $local ? 'btn    -primary disabled' : 'btn-outline-primary' }}">
        Local
    </x-button>
    <x-button wire:click="foreign"
        class="btn btn-sm mb-2 {{ $foreign ? 'btn-success disabled' : 'btn-outline-success' }}">
        Foreign
    </x-button>

    @if ($local)
    <x-scanner-input mobileScannerRoute="a.bus-scanner" mobileScanner="true" mobileLabel="Bus Mobile Scanner"
        wire:model.debounce.700ms="enroledid" autofocus />
    @else
    <x-foreign-input-bus model="foreignInput" model1="foreignNumber" model2="addBusData" class="card p-1">
    </x-foreign-input-bus>
    @endif

    <livewire:components.admin.crew-monitoring.crew-monitoring-list-component wire:key='{{rand(0,999999)}}' />

</x-container>