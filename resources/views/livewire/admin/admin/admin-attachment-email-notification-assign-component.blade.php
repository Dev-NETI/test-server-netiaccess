<div>
    <section class="container-fluid p-4">
        <span wire:loading>
            <livewire:components.loading-screen-component />
        </span>
        <div class="row">
            <!-- Page Header -->
            <div class="col-lg-12 col-md-12 col-12">
                <div class="border-bottom pb-3 mb-3 d-flex justify-content-between align-items-center">
                    <div class="mb-2 mb-lg-0">
                        <h1 class="mb-1 h2 fw-bold">
                            Email Notification
                        </h1>
                        <!-- Breadcrumb  -->
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="../dashboard/admin-dashboard.html">Settings</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">Notifications</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Assign
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- basic table -->
            <div class="col-md-12 col-12 mb-5">
                <div class="card">
                    <!-- card header  -->
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-5">
                                <h4 class="mb-1">List of Account that the System will Notify <a href="" data-bs-toggle="modal" data-bs-target="#addpersonmodal" class="btn btn-success"><i class="bi bi-plus-square"></i> Add</a></h4>
                                <!-- <p class="mb-0">DataTables is a plug-in for the jQuery Javascript library. It is a
                            highly flexible tool, built upon the foundations of progressive enhancement, that
                            adds all of these advanced features to any HTML table.</p> -->
                            </div>
                            {{--<div class="col-lg-3 text-end">
                                <label for="" class="form-label pt-2">Search:</label>
                            </div>
                             <div class="col-lg-4 float-end">
                                <input type="text" placeholder="search in name & rank .." wire:model.debounce.500ms="search" class="form-control">
                            </div> --}}
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive text-center">
                            <table class="table table-hover" style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th>Fullname</th>
                                        <th>Email</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="" style="font-size: 14px;">
                                    @if ($persontonotify)
                                        @foreach ($persontonotify as $personstonotify)
                                            <tr>
                                                <td>{{ $personstonotify->l_name }} {{ $personstonotify->f_name }}</td>
                                                <td>{{ $personstonotify->email }}</td>
                                                {{-- <td><button class="btn btn-success" wire:click="addpersontonotify({{ $personstonotify->id }}, '{{ $personstonotify->email }}')">Add</button></td> --}}
                                                <td><button class="btn btn-danger" wire:click="removeperson({{ $personstonotify->userid }})">Remove</button></td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                            </table>
                            <div class="card-footer">
                                <div class="row mt-5">
                                    {{ $persontonotify->links('livewire.components.customized-pagination-link')}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

      <!-- modal -->
        <div wire:ignore.self class="modal fade" id="addpersonmodal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h3 class="modal-title">List of Users</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
                </div>
                <div class="modal-body">
                    <div class="table-card">
                        <table class="table table-hover" id="dataTableBasic" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($user as $users)
                                    <tr>
                                        <td>{{ $users->l_name }} {{ $users->f_name }}</td>
                                        <td>{{ $users->email }}</td>
                                        <td><button class="btn btn-success" wire:click="addpersontonotify({{ $users->user_id }}, '{{ $users->email }}')">Add</button></td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
                {{-- <div class="modal-footer">
                    {{ $user->links('livewire.components.customized-pagination-link')}}
                </div> --}}
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
            </div>
        </div>


</div>
