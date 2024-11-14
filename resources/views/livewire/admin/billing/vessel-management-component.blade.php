<section>
    <div class="m-4 border-bottom">
        <h2 for="">
            Vessel Management
        </h2>
        <p class="text-primary">Add/Edit vessels here</p>
    </div>
    <div class="row m-4">
        <div class="col-lg-2">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalCenter"><i class="bi bi-plus me-2"></i>Add Vessel</button>
        </div>
    </div>
    <div class="row m-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-6">
                            <h3>Vessel List </h3>
                        </div>
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="float-end ms-lg-3 d-none d-md-none d-lg-block">
                                    <!-- Form -->
                                    <form class="d-flex align-items-center">
                                        <span class="position-absolute ps-3 search-icon">
                                                <i class="fe fe-search"></i>
                                            </span>
                                        <input type="search" wire:model.debounce.500ms="search" class="form-control ps-6" placeholder="Search vessel name..">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal -->
                <div wire:ignore.self class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Add Vessel Form</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="mb-3">
                                    <label class="form-label">Vessel Name</label>
                                    <form id="addvessel" wire:submit.prevent="addvessel">
                                        @csrf
                                        <input type="text" wire:model.defer="vesselnameadd" class="form-control" placeholder="Enter Vessel Name">
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" form="addvessel" class="btn btn-primary"><i class="bi bi-plus me-2"></i> Add</button>
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
                                    <th>Vessel Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tblvessel as $vessel)
                                    <tr @if ($vessel->deletedid == 1) class="bg-light-danger" @endif>
                                        <td>{{ $vessel->id }}</td>
                                        @if ($idtoedit == $vessel->id)
                                            <td><input type="text" class="form-control uppercase" style="text-transform: uppercase;" wire:model="vesselname" placeholder="{{ $vessel->vesselname }}"></td>
                                        @else
                                            <td>{{ $vessel->vesselname }}</td>
                                        @endif
                                        <td>
                                            @if ($idtoedit == $vessel->id)
                                                <button class="btn btn-sm btn-success" wire:click="save"><i class="bi bi-check-all me-2"></i>Save</button>
                                            @else   
                                                <button class="btn btn-sm btn-primary" wire:click="toggleeditbtn({{$vessel->id}}, '{{$vessel->vesselname}}')"><i class="bi bi-pencil-square me-2"></i>Edit</button>
                                                @if ($vessel->deletedid == 0)
                                                    <button class="btn btn-sm btn-danger" wire:click="delete({{ $vessel->id }})"><i class="bi bi-trash me-2"></i>Delete</button>
                                                @else
                                                    <button class="btn btn-sm btn-info" wire:click="active({{ $vessel->id }})"><i class="bi bi-check me-2"></i>Re-Active</button>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row mt-5" style="padding-bottom: 6.5em;">
                        {{ $tblvessel->links('livewire.components.customized-pagination-link') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
