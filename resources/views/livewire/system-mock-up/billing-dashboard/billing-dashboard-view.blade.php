<x-card-layout cardTitle="Billing and Collection Monitoring"
    cardDescription="">
    
    <div wire:ignore class="row">
                <livewire:components.system-mock-up.billing-dashboard.dashboard-card-component 
                icon="bi bi-building" step="Step 1"
                process="Manning" role="" />

                <livewire:components.system-mock-up.billing-dashboard.dashboard-card-component 
                icon="bi bi-people" step="Step 2"
                process="MHR" role="" />

                <livewire:components.system-mock-up.billing-dashboard.dashboard-card-component 
                icon="bi bi-briefcase" step="Step 3"
                process="NYK-SM" role="" />

                <livewire:components.system-mock-up.billing-dashboard.dashboard-card-component 
                icon="bi bi-credit-card" step="Step 4"
                process="Proof of Payment" role="" />

                <livewire:components.system-mock-up.billing-dashboard.dashboard-card-component 
                icon="bi bi-receipt" step="Step 5"
                process="O.R." role="" />

                <livewire:components.system-mock-up.billing-dashboard.dashboard-card-component 
                icon="bi bi-check-circle" step="Step 6"
                process="Closed Transaction" role="" />
    </div>

</x-card-layout>
