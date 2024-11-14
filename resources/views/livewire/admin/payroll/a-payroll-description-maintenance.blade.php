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
                        List of Custom description
                    </h1>
                    <!-- Breadcrumb  -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('a.instructor') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">User</li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Custom description Maintenance
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="row">

        <div wire:ignore.self class="modal fade" id="addnewratemodal" tabindex="-1" role="dialog" aria-labelledby="addnewratemodal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="exampleModalScrollableTitle">ADD DESCRIPTION</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <form wire:submit.prevent="CreateDescription">
                                @csrf
                                <div class="mt-2 col-lg-12">
                                    <label class="form-label" for="">Description</label><br>
                                    <input type="text" class="col-lg-12 form-control" wire:model.defer="description_name" required>
                                </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" type="button" class="btn btn-info">Add</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="addeditdescription" tabindex="-1" role="dialog" aria-labelledby="addeditdescription" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="exampleModalScrollableTitle">EDIT DESCRIPTION</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <form wire:submit.prevent="UpdateDescription">
                                @csrf
                                <div class="mt-2 col-lg-12">
                                    <label class="form-label" for="">Description</label><br>
                                    <input type="text" class="col-lg-12 form-control" wire:model.defer="description_name" required>
                                </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" type="button" class="btn btn-info">Save</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-12 mb-5">
            <button class="btn btn-info mb-2" data-bs-toggle="modal" data-bs-target="#addnewratemodal">Add Description</button>
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-5">
                            <h4 class="mb-1">List of Description</h4>
                        </div>
                        <div class="col-lg-3 text-end">
                            <label for="" class="form-label pt-2">Search:</label>
                        </div>
                        <div class="col-lg-4 float-end">
                            <input type="text" placeholder="search in description" wire:model.debounce.500ms="search" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm text-nowrap mb-0 table-centered table-dark" width="100%" height="100%">
                        <thead>
                            <tr>
                                <th class="text-center">Action</th>
                                <th>DESCRIPTION NAME</th>
                                <th class="text-center">STATUS</th>
                            </tr>
                        </thead>
                        <tbody class="" style="font-size: 11px;">
                            @if ($description_data->count())
                            @foreach ($description_data as $data)
                            <tr class="mt-1 mb-2">
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Edit
                                        </button>
                                        <div class="dropdown-menu">
                                            <button data-bs-toggle="modal" data-bs-target="#addeditdescription" wire:click="EditDescription({{ $data->id }})" class="dropdown-item"><i class="bi bi-archive-fill"></i>&nbsp; Edit Description</button>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $data->description }}</td>
                                @if($data->is_deleted == 0)
                                <td class="text-center">ACTIVE</td>
                                @else
                                <td class="text-center">INACTIVE</td>
                                @endif
                            </tr>
                            @endforeach
                            @else
                            <tr class="text-center">
                                <td colspan="6">-----No Records Found-----</td>
                            </tr>
                            @endif

                        </tbody>
                    </table>
                    <div class="card-footer">
                        <div class="row mt-5" style="padding-bottom: 6.5em;">
                            {{ $description_data->appends(['search' => $search])->links('livewire.components.customized-pagination-link')}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>