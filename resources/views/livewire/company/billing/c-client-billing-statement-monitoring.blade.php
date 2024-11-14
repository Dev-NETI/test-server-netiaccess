<div>

    <div class="py-lg-14 bg-light pt-8 pb-10">
      
        <div class="card text-center">

            <div class="card-header">
                <h1 class="card-title">Dashboard</h1>
                <p class="card-text">Here you can monitor the status of training payment.</p>
            </div>

            <div class="card-body row">

                    {{-- <livewire:company.billing.child.dashboard.card-component :billingstatusid="1" icon="bi bi-clock" step="Step 1" 
                    process="Pending Statements Board" /> --}}

                    <livewire:company.billing.child.dashboard.card-component :billingstatusid="6" icon="bi bi-check-circle" step="Step 2" 
                    process="Client Confirmation Board" />

                    <livewire:company.billing.child.dashboard.card-component :billingstatusid="7" icon="bi bi-cloud-upload" step="Step 3" 
                    process="Proof of Payment Upload Board" />

                    <livewire:company.billing.child.dashboard.card-component :billingstatusid="9" icon="bi bi-receipt" step="Step 4" 
                    process="Official Receipt Confirmation Board" />

                    <livewire:company.billing.child.dashboard.card-component :billingstatusid="10" icon="bi bi-file-check-fill" step="Step 5" 
                    process="Transaction Close Board" />

            </div>
            
            <div class="card-footer text-body-secondary">
            </div>

        </div>
        
    </div>

</div>
