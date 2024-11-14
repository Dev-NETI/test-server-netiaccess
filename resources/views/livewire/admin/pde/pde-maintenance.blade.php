<section class="container-fluid p-4">
    <div class="row">
        <span wire:loading>
            <livewire:components.loading-screen-component />
        </span>

        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-md-flex align-items-center justify-content-between">
                <div class="mb-3 mb-md-0">
                    <h1 class="mb-1 h2 fw-bold"><i class="mdi mdi-comment-question-outline"></i> PDE Maintenance </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active" aria-current="page">
                                PDE List
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="card mb-4">
                <div class="card-header p-0">
                    <div>
                        <ul class="nav nav-lb-tab  border-bottom-0 " id="tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="courses-tab" data-bs-toggle="pill" href="#courses"
                                    role="tab" aria-controls="courses" aria-selected="true">All ({{ $count_allpde}})</a>
                            </li>
                        </ul>
                    </div>

                    <div class="p-4 row">
                        <form class="d-flex align-items-center col-12 col-md-12 col-lg-12">
                            <span class="position-absolute ps-3 search-icon"><i class="fe fe-search"></i></span>
                            <input type="search" class="form-control ps-6" wire:model.debounce.500ms="search"
                                placeholder="Search Rank">
                        </form>
                    </div>

                </div>
                <div wire:ignore.self class="table-responsive border-0 overflow-y-hidden">
                    <table class="table mb-0 text-nowrap table-centered table-hover">
                        <thead class="table-light">
                            <tr>

                                <th>No</th>
                                <th>Action</th>
                                <th>Rank Code</th>
                                <th>Rank </th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($retrievepdelist as $pdelist )
                            <tr>
                                <td>{{ $loop->index + 1}}</td>
                                <td> <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Setting
                                    </button>

                                    {{-- <a href="{{ route('a.pdeassessmaint', ['rankid' => $pdelist->rankid]) }}">
                                        TWS</a> --}}

                                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                            data-bs-target="#addpdemodal"
                                            wire:click="pderequirements({{  $pdelist->rankid }})"><i
                                                class="fe fe-edit"></i> Pde Requirements</a>

                                        <a class="dropdown-item"
                                            href="{{ route('a.pdeassessmaint', ['rankid' => $pdelist->rankid]) }}"><i
                                                class="fe fe-send "></i> PDE Assessment Setting</a>
                                    </div>
                                </td>
                                <td>{{ $pdelist->rankacronym }}</td>
                                <td>{{ $pdelist->rank }}</td>

                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                    <div class="card-footer">
                        <div class="row">
                            {{ $retrievepdelist->appends(['search' =>
                            $search])->links('livewire.components.customized-pagination-link')}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div wire:ignore.self class="modal fade" id="addpdemodal" tabindex="-1" role="dialog" aria-labelledby="addpdemodal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title mb-0" id="newCatgoryLabel">
                        Add requirements
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addpderequirements">
                        <div class="">
                            <label class="form-label">Requirements <span class="text-danger">*</span></label>
                            @if(!$editMode)
                            <input type="text" wire:model.defer="addrequirements" class="form-control"
                                placeholder="Enter requirement" required>
                            @else
                            <input type="text" wire:model.defer="editrequirements" class="form-control"
                                placeholder="Enter requirement" required>
                            @endif
                        </div>
                        <div class="mt-2">
                            <div class="form-check">
                            @if(!$editMode)
                                     <input type="checkbox" class="form-check-input" wire:model.defer="if_any_add" value="1" id="1">
                              @else
                                     <input type="checkbox" class="form-check-input" wire:model.defer="if_any_edit" value="1" id="1">
                              @endif
                                      <label class="form-check-label" for="optional"> Optional </label>
                            </div>
                          </div>

                        <div class="mt-2">
                            <button class="btn btn-primary" type="submit" wire:click.prevent="addpderequirements"
                                     @if($editMode) style="display:none" @endif>Add</button>
                            <button class="btn btn-success" type="submit" wire:click.prevent="updaterequirements"
                                     @if(!$editMode) style="display:none" @endif>Update</button>
                            <button class="btn btn-danger" type="button" wire:click="cancelEdit" @if(!$editMode) style="display:none" @endif>Cancel</button>
                        </div>
                    </form>

                    <table class="table table-centered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Action
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </th>
                                <th>Requirements &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                <th>Optional</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($retrievepderequirements->count() > 0)
                            @foreach ($retrievepderequirements as $pderequirements)
                            <tr>
                                <td>
                                        <a class="btn btn-success btn-sm" href="#"
                                            wire:click="editrequirements({{ $pderequirements->pderequirementsid }})">
                                            <i class="fe fe-edit"></i>
                                        </a>

                                        <a class="btn btn-danger btn-sm" href="#"
                                            wire:click="deleteConfirmation({{ $pderequirements->pderequirementsid }})">
                                            <i class="fe fe-trash"></i>
                                        </a>

                                </td>
                                <td>{{ $pderequirements->pderequirements }}</td>
                                <td> {{ $pderequirements->if_any }} </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="2" class="text-center">----------No PDE requirements are available---------</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>