<div class="col-md-6 offset-md-3 table-responsive">
    <table class="table table-hover table-striped">
            <thead>
                    <th>Email</th>
                    <th>Action</th>
            </thead>
            <tbody>
                    @foreach ($email_data as $data)
                        <livewire:admin.billing.child.email-management.email-list-item-component :data="$data" wire:key="{{$data->id}}" />
                    @endforeach
            </tbody>
    </table>
</div>
