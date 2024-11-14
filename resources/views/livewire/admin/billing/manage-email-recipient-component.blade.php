<section>
    <div class="py-lg-14 bg-light pt-8 pb-10">
        <div class="container">




            <div class="card text-center">
                <div class="card-header">
                    <h1 class="fw-bold">Email Management</h1>
                    <h6 class="text-muted">Here you can manage email recipients for {{ $company_data->company }}</h6>
                </div>

                <div class="card-body row">
                    <livewire:admin.billing.child.email-management.create-email-component :companyid="$companyid" />

                    <div class="col-md-6 offset-md-3 ">
                        <x-toggle-switch :label="$toggleLabel" wire:model="toggle" />
                    </div>

                    <livewire:admin.billing.child.email-management.email-list-component :companyid="$companyid" />
                </div>

                <div class="card-footer text-body-secondary">
                </div>
            </div>




        </div>
    </div>
</section>
