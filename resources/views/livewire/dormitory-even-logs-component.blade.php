<section class="container-fluid p-4">
    <h1>Event Logs</h1>
    <hr>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-lg-6">
                    <h3>Logs</h3>
                </div>
                <div class="col-lg-6">
                    <div class="float-end">
                        <div class="ms-lg-4 d-none d-md-none d-lg-block">
                            <!-- Form -->
                            <div class="d-flex align-items-center">
                                <span class="position-absolute ps-3 search-icon">
                                    <i class="fe fe-search"></i>
                                </span>
                                <input type="search" wire:model.debounce.1000ms="search" class="form-control ps-6" placeholder="Search Logs .." >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Logged By</th>
                            <th>Log Content</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($logs as $log => $data)
                            <tr>
                                <td>{{ ($logs->currentPage() - 1) * $logs->perPage() + $log + 1 }}</td>
                                <td>{{ $data->created_by }}</td>
                                <td>{{ $data->logs }}</td>
                                <td>{{ $data->formatted_date }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $logs->links('livewire.components.customized-pagination-link') }}
        </div>
    </div>
</section>
