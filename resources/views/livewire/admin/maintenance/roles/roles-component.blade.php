<section class="container-fluid p-4">
    <div class="row">
        <!-- Page Header -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-md-flex align-items-center justify-content-between">
                <div class="mb-3 mb-md-0">
                    <h1 class="mb-1 h2 fw-bold"><i class="mdi mdi-bulletin-board"></i>Roles Maintenance</h1>
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="../dashboard/admin-dashboard.html">Roles Maintenance</a>
                            </li>

                            <li class="breadcrumb-item active" aria-current="page">
                                Roles Maintenance
                            </li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addmodal">
                        <i class="mdi mdi-library-plus"></i> Add Roles
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <!-- Card -->
            <div class="card mb-4">
                <!-- Card header -->
                <div class="card-header border-bottom-0">
                    <!-- Form -->
                    
                </div>
                <!-- Table -->
                <div class="card-body">
                    
                    
                    <input type="text" class="form-control ml-3 mr-3" placeholder="Search..." wire:model.debounce.500ms="search">

                    <div class="table-responsive mt-3">
                                <table class="table table-bordered table-hover text-center" width="100%">
                                        <thead>
                                                <tr>
                                                        <th>No.</th>
                                                        <th>Role</th>
                                                </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($roles as $role)
                                                <tr>
                                                        <td>{{$role->id}}</td>
                                                        <td>{{$role->rolename}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                </table>

                                <div class="row">
                                    {{ $roles->links('livewire.components.customized-pagination-link')}}
                                    Total: {{ $t_allschedules }}
                                </div>
                    </div>

                    
                </div>
                
            
            </div>
        </div>
    </div>


    <!-- ADD Roles Modal -->
    <div wire:ignore class="modal fade" id="addmodal" tabindex="-1" role="dialog" aria-labelledby="addmodal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title mb-0" id="newCatgoryLabel">
                        Add Roles
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="addRole">
                        @csrf
                        <div class="mb-3 mb-2">
                            <label class="form-label" for="title">Role name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model.defer="role_name" required>


                        </div>



                        <div>
                            <button type="submit" class="btn btn-primary">Add</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- ADD Roles Modal -->



</section>