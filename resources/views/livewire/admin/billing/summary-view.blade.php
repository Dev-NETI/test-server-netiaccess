<x-card-layout cardTitle="Billing Module Summary" cardDescription="Showing data for">
    <span wire:loading>
        <livewire:components.loading-screen-component />
    </span>
    <div class="row">
        <div class="col-md-4 offset-md-4">
            <select wire:model="currentWeek" class="form-control">
                @foreach ($weekData as $item)
                    <option value="{{ $item->batchno }}">{{ $item->batchno }}</option>
                @endforeach
            </select>
        </div>

        <x-horizontal-card title="On process">
            <livewire:components.admin.billing.summary-list-component :billingstatusid="1" currentWeek="{{ $currentWeek }}"
                :key="$currentWeek" />
        </x-horizontal-card>
        <x-horizontal-card title="Billing Statement Review Board">
            <livewire:components.admin.billing.summary-list-component :billingstatusid="2"
                currentWeek="{{ $currentWeek }}" :key="$currentWeek" />
        </x-horizontal-card>
        <x-horizontal-card title="BOD Manager Review Board">
            <livewire:components.admin.billing.summary-list-component :billingstatusid="3"
                currentWeek="{{ $currentWeek }}" :key="$currentWeek" />
        </x-horizontal-card>
        <x-horizontal-card title="GM Review Board">
            <livewire:components.admin.billing.summary-list-component :billingstatusid="4"
                currentWeek="{{ $currentWeek }}" :key="$currentWeek" />
        </x-horizontal-card>
        <x-horizontal-card title="BOD Manager Dispatch Board">
            <livewire:components.admin.billing.summary-list-component :billingstatusid="5"
                currentWeek="{{ $currentWeek }}" :key="$currentWeek" />
        </x-horizontal-card>
        <x-horizontal-card title="Client Confirmation Board">
            <livewire:components.admin.billing.summary-list-component :billingstatusid="6"
                currentWeek="{{ $currentWeek }}" :key="$currentWeek" />
        </x-horizontal-card>
        <x-horizontal-card title="Proof of Payment Upload Board">
            <livewire:components.admin.billing.summary-list-component :billingstatusid="7"
                currentWeek="{{ $currentWeek }}" :key="$currentWeek" />
        </x-horizontal-card>
        <x-horizontal-card title="Official Receipt Issuance Board">
            <livewire:components.admin.billing.summary-list-component :billingstatusid="8"
                currentWeek="{{ $currentWeek }}" :key="$currentWeek" />
        </x-horizontal-card>
        <x-horizontal-card title="Official Receipt Confirmation Board">
            <livewire:components.admin.billing.summary-list-component :billingstatusid="9"
                currentWeek="{{ $currentWeek }}" :key="$currentWeek" />
        </x-horizontal-card>
        <x-horizontal-card title="Transaction Close Board">
            <livewire:components.admin.billing.summary-list-component :billingstatusid="10"
                currentWeek="{{ $currentWeek }}" :key="$currentWeek" />
        </x-horizontal-card>
    </div>

</x-card-layout>
