<section class="container-fluid p-4">
    <div class="row">
        <!-- Page Header -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-md-flex align-items-center justify-content-between">
                <div class="mb-3 mb-md-0">
                    <h1 class="mb-1 h2 fw-bold"><i class="mdi mdi-bulletin-board"></i> Room Maintenance</h1>
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('a.maintenance') }}">Maintenance</a>
                            </li>

                            <li class="breadcrumb-item active" aria-current="page">
                                Room Maintenance
                            </li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addmodal">
                        <i class="mdi mdi-library-plus"></i> Add New Room
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
                    <form class="d-flex align-items-center">
                        @csrf
                        <span class="position-absolute ps-3 search-icon">
                            <i class="fe fe-search"></i>
                        </span>
                        <input type="search" class="form-control ps-6" wire:model.debounce.500ms="search"
                            placeholder="Search Room">
                    </form>
                </div>
                <!-- Table -->

                <div wire:ignore.self class="table-responsive border-0 overflow-y-hidden">
                    <table class="table mb-0 text-nowrap table-centered table-hover table-with-checkbox table-centered table-hover">
                        <thead class="table-secondary">
                            <tr>

                                <th>NO</th>
                                <th>ACTION</th>
                                <th>ROOM NAME</th>

                            </tr>
                        </thead>
                        <tbody>

                            @foreach ( $room_maintenance as $room_maint )
                            <tr>
                                <td>
                                    <a href="#" class="text-inherit">
                                        <h5 class="mb-0 text-primary-hover">{{ $loop->index + 1}}</h5>
                                    </a>
                                </td>

                                <td>
                                    <button href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#editModal" wire:click="roomedit({{$room_maint->roomid}})">
                                        <i class="fe fe-edit dropdown-item-icon" style="color: white;"></i> Edit
                                    </button>
                                </td>
                                <td>
                                    <a href="#" class="text-inherit">
                                        <h5 class="mb-0 text-primary-hover">{{ $room_maint->room}}</h5>
                                    </a>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                    <div class="card-footer">
                        <div class="row">
                            {{ $room_maintenance->appends(['search' => $search])->links('livewire.components.customized-pagination-link')}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Edit Room Modal -->
    <div wire:ignore.self class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="editmodal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title mb-0" id="newCatgoryLabel">
                        Edit Room
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="roomupdate">
                        <div class="mb-3 mb-2">
                            <label class="form-label" for="title">Room<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="room" required>
                        </div>


                        <div>
                            <button type="submit" class="btn btn-primary">Update Room</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Room Modal -->


    <!-- ADD Room Modal -->
    <div wire:ignore class="modal fade" id="addmodal" tabindex="-1" role="dialog" aria-labelledby="addmodal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title mb-0" id="newCatgoryLabel">
                        Add New Room
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit="roomadd">
                        <div class="mb-3 mb-2">
                            <label class="form-label" for="title">Room name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model.defer="room" required>


                        </div>



                        <div>
                            <button type="submit" class="btn btn-primary">Add new Room</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- ADD Room Modal -->



</section>