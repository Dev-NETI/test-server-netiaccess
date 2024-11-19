<x-card-layout cardTitle="Billing and Collection Monitoring"
    cardDescription="">
    
    <div wire:ignore class="row">
                <livewire:components.system-mock-up.billing-dashboard.dashboard-card-component 
                icon="bi bi-building" step="Step 1"
                process="Manning" role="" route="c.billing-process-view" status="1" />

                <livewire:components.system-mock-up.billing-dashboard.dashboard-card-component 
                icon="bi bi-people" step="Step 2"
                process="MHR" role="" route="c.billing-process-view" status="2" />

                <livewire:components.system-mock-up.billing-dashboard.dashboard-card-component 
                icon="bi bi-briefcase" step="Step 3"
                process="NYK-SM" role="" route="c.billing-process-view" status="3" />

                <livewire:components.system-mock-up.billing-dashboard.dashboard-card-component 
                icon="bi bi-credit-card" step="Step 4"
                process="Proof of Payment" role="" route="c.billing-process-view" status="4" />

                <livewire:components.system-mock-up.billing-dashboard.dashboard-card-component 
                icon="bi bi-receipt" step="Step 5"
                process="O.R." role="" route="c.billing-process-view" status="5" />

                <livewire:components.system-mock-up.billing-dashboard.dashboard-card-component 
                icon="bi bi-check-circle" step="Step 6"
                process="Closed Transaction" role="" route="c.billing-process-view" status="6" />
    </div>

</x-card-layout>
