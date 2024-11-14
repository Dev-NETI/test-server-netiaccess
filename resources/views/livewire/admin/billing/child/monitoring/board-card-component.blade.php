<x-step-card :icon="$icon" :title="$process" :description="$step" :count="$counttraineedata"
    wire:click="passSessionData({{ $billingstatusid }}, '{{ $currentWeek }}')">

    <x-accordion title="Balance" :index="$billingstatusid">
        @foreach ($balanceBillingData as $item)
        <ul class="list-group">
            <x-unordered-list-item wire:click="passSessionData({{ $billingstatusid }},'{{ $item['batchno'] }}')">
                <small class="text-small">{{ $item['batchno'] }}</small>
                <span class="badge text-bg-danger rounded-pill">{{ $item['count'] }}</span>
            </x-unordered-list-item>
        </ul>
        @endforeach
    </x-accordion>

</x-step-card>