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
                        Room Price Maintenance
                    </h1>
                    <!-- Breadcrumb  -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="#">Maintenance</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Room Price
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
                        <div class="col-lg-6">
                            <h3>Room Table</h3>
                        </div>
                        <div class="col-lg-6 text-end">
                            <button class="btn btn-info" wire:click.prevent="addbutton"><i class="bi bi-plus-lg"></i> Add</button>
                        </div>
                    </div>
                </div>
                <div class="p-3 table-responsive">
                    {{-- <livewire:power-grid-tables.room-type/> --}}

                    <table class="table table-hover text-nowrap mb-0 table-centered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Roomtype</th>
                                <th>Capacity</th>
                                <th>NMCRoomPrice</th>
                                <th>NMCMealPrice</th>
                                <th>MandatoryRoomPrice</th>
                                <th>MandatoryMealPrice</th>
                                <th>DeletedID</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($tabledata))
                                @foreach ($tabledata as $data)
                                    <tr>
                                        <td>{{ $data->id }}</td>
                                        <td>{{ $data->roomtype }}</td>
                                        <td>{{ $data->capacity }}</td>
                                        <td>{{ $data->nmcroomprice }}</td>
                                        <td>{{ $data->nmcmealprice }}</td>
                                        <td>{{ $data->mandatoryroomprice }}</td>
                                        <td>{{ $data->mandatorymealprice }}</td>
                                        <td>@if ($data->deleteid == 0)
                                            <p class="text-success">Active</p>
                                        @else
                                            <p class="text-danger">Deleted</p>
                                        @endif</td>
                                        <td>
                                            <button class="btn btn-primary" wire:click.prevent="editdata({{$data->id}})"><i class="bi bi-pencil-square"></i> Edit</button>
                                            @if ($data->deleteid == 1)
                                                <button class="btn btn-success" wire:click.prevent="activatefunc({{$data->id}})"><i class="bi bi-arrow-repeat"></i> Activate</button>
                                            @else
                                                <button class="btn btn-danger" wire:click.prevent="delete({{$data->id}})"><i class="bi bi-trash2-fill"></i> Delete</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach

                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

        <div id="edit" class="modal fade gd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Edit Room Details</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <form id="edit" action="" wire:submit.prevent="saveedit">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 mt-2">
                                    <label for=""><h4>Roomtype</h4></label>
                                    <input wire:model.defer="roomtype" class="form-control" type="text" required placeholder="{{$roomtype}}">
                                </div>
                                <div class="col-lg-6 mt-2">
                                    <label for=""><h4>Capacity</h4></label>
                                    <input wire:model.defer="capacity" class="form-control" type="text" required placeholder="{{$capacity}}">
                                </div>
                                <div class="col-lg-6 mt-2">
                                    <label for=""><h4>NMC Room Price</h4></label>
                                    <input wire:model.defer="nmcroom" class="form-control" type="text" required placeholder="{{$nmcroom}}">
                                </div>
                                <div class="col-lg-6 mt-2">
                                    <label for=""><h4>NMC Meal Price</h4></label>
                                    <input wire:model.defer="nmcmeal" class="form-control" type="text" required placeholder="{{$nmcmeal}}">
                                </div>
                                <div class="col-lg-6 mt-2">
                                    <label for=""><h4>Mandatory Room Price</h4></label>
                                    <input wire:model.defer="mandatoryroom" class="form-control" type="text" required placeholder="{{$mandatoryroom}}">
                                </div>
                                <div class="col-lg-6 mt-2">
                                    <label for=""><h4>Mandatory Meal Price</h4></label>
                                    <input wire:model.defer="mandatorymeal" class="form-control" type="text" required placeholder="{{$mandatorymeal}}">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div id="addmodal" class="modal fade gd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Add Room</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <form id="addroom" action="" wire:submit.prevent="addroom">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 mt-2">
                                    <label for=""><h4>Roomtype</h4></label>
                                    <input wire:model.defer="createroomtype" class="form-control" type="text" required>
                                </div>
                                <div class="col-lg-6 mt-2">
                                    <label for=""><h4>Capacity</h4></label>
                                    <input wire:model.defer="createcapacity" class="form-control" type="text" required>
                                </div>
                                <div class="col-lg-6 mt-2">
                                    <label for=""><h4>NMC Room Price</h4></label>
                                    <input wire:model.defer="createnmcroomprice" class="form-control" type="text" required>
                                </div>
                                <div class="col-lg-6 mt-2">
                                    <label for=""><h4>NMC Meal Price</h4></label>
                                    <input wire:model.defer="createnmcmealprice" class="form-control" type="text" required>
                                </div>
                                <div class="col-lg-6 mt-2">
                                    <label for=""><h4>Mandatory Room Price</h4></label>
                                    <input wire:model.defer="createmandatoryroomprice" class="form-control" type="text" required>
                                </div>
                                <div class="col-lg-6 mt-2">
                                    <label for=""><h4>Mandatory Meal Price</h4></label>
                                    <input wire:model.defer="createmandatorymealprice" class="form-control" type="text" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button form="addroom" type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    
</section>

