<div class="container-fluid p-4">
    <div class="row">
        <!-- Page Header -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-flex justify-content-between align-items-center">
                <div class="mb-2 mb-lg-0">
                    <h1 class="mb-1 h2 fw-bold">
                        Notification Logs
                    </h1>
                    <!-- Breadcrumb  -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="#">Notifications</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Logs
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="input-group">
                                <span class="input-group-text bg-secondary" id="basic-addon1"><i class="bi bi-search text-white"> Search</i> </span>
                                <input type="text" wire:model.debounce.150ms="search" class="form-control" placeholder="You can search on Firstname, Lastname, Details" aria-label="Username" aria-describedby="basic-addon1">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {{-- <livewire:notification.notification-history-table /> --}}
                    <div class="table-responsive">
                        <table class="table table-hover second" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Log ID</th>
                                    <th>Firstname</th>
                                    <th>Lastname</th>
                                    <th>Details</th>
                                    <th>Data</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($logs as $log)
                                <tr>
                                    <td>{{ $log->log_id }}</td>
                                    <td>{{ $log->f_name }}</td>
                                    <td>{{ $log->l_name }}</td>
                                    <td>{{ $log->details }}</td>
                                    <td>
                                        @foreach ($log->data as $key => $value)
                                        <span class="text-danger"> {{ $key }} </span> : {{ is_array($value) ? json_encode($value) : htmlspecialchars($value) }} <br>
                                        @endforeach
                                    </td>
                                    <td>{{ date("d F Y, h:m:s A", strtotime($log->created_at)) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center mb-0">
                            {{$logs->links()}}
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>