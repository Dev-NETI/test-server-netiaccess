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
                        Room Capacity Maintenance
                    </h1>
                    <!-- Breadcrumb  -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="#">Maintenance</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Room Capacity
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
                        <div class="col-lg-6">
                            <div class="float-end">
                                <select wire:model="selectroomtype" class="form-select" name="" id="">
                                    <option value="">ALL</option>
                                    @foreach ($roomtypes as $data)
                                        <option value="{{ $data->id }}">{{ $data->roomtype }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-3 table-responsive">
                    {{-- <livewire:power-grid-tables.room-type/> --}}

                    <table class="table table-hover text-nowrap mb-0 table-centered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>RoomtypeID</th>
                                <th>Capacity</th>
                                <th>RoomName</th>
                                <th>DeletedID</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($tabledata))
                                @foreach ($tabledata as $data)
                                    <tr>
                                        <td>{{ $data->id }}</td>
                                        <td>{{ $data->roomtype->roomtype }}</td>
                                        <td>{{ $data->capacity }}</td>
                                        <td>{{ $data->roomname }}</td>
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
                            @else
                                <tr>
                                    <td colspan="6">No Data to Show</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="row mt-5" style="padding-bottom: 6.5em;">
                        {{ $tabledata->links('livewire.components.customized-pagination-link') }}
                    </div>
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
                                <div class="col-lg-3 mt-2">
                                    <label for=""><h4>Roomtype</h4></label>
                                    <select name="" class="form-select" id="" wire:model.defer="roomtypeid">
                                        @foreach ($roomtypes as $data)
                                            <option value="{{ $data->id }}">{{ $data->roomtype }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 mt-2">
                                    <label for=""><h4>Room Name</h4></label>
                                    <input wire:model.defer="roomname" class="form-control" type="text" required placeholder="{{$roomname}}">
                                </div>
                                <div class="col-lg-3 mt-2">
                                    <label for=""><h4>Capacity</h4></label>
                                    <input wire:model.defer="idtoupdate" class="form-control" hidden type="text" required>
                                    <input wire:model.defer="capacity" class="form-control" type="text" required placeholder="{{$capacity}}">
                                </div>
                                <div class="col-lg-3 mt-2">
                                    <label for=""><h4>Max Capacity</h4></label>
                                    <input wire:model.defer="idtoupdate" class="form-control" hidden type="text" required>
                                    <input wire:model.defer="maxcapacity" class="form-control" type="text" required placeholder="{{$maxcapacity}}">
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

