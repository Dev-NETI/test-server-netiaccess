<div class="container mt-5">
    <div class="card text-center">

        <div class="card-header">
            <h1 class="fw-bold">Client Information for {{ $company_data->company }}</h1>
            <small class="text-muted">Here you can edit information of client used for billing statement.</small>
        </div>

        <div class="card-body row">
            <div class="col-md-4 offset-md-8">
                <button type="button" class="btn btn-primary float-end" wire:click="create()" >
                    Create
                </button>
            </div>

            <div class="col-md-6 offset-md-3 table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <th>Description</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        @foreach ($client_info_data as $data)
                            <livewire:admin.billing.child.clientinfo.client-info-list-item-component :client="$data" />
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer text-body-secondary">
        </div>

    </div>
</div>
