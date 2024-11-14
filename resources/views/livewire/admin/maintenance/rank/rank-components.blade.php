<section class="container-fluid p-4">
    <div class="row">
        <!-- Page Header -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-md-flex align-items-center justify-content-between">
                <div class="mb-3 mb-md-0">
                    <h1 class="mb-1 h2 fw-bold"><i class="mdi mdi-bulletin-board"></i> Rank Maintenance</h1>
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('a.maintenance') }}">Maintenance</a>
                            </li>

                            <li class="breadcrumb-item active" aria-current="page">
                                Rank Maintenance
                            </li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addmodal">
                       Add Rank
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
                    <form class="d-flex align-items-center col-12 col-md-12 col-lg-12">
                        @csrf
                        <span class="position-absolute ps-3 search-icon"><i class="fe fe-search"></i></span>
                        <input type="search" class="form-control ps-6" wire:model.debounce.500ms="search"
                            placeholder="Search Rank">
                    </form>
                </div>
                <!-- Table -->

                <div class="table-responsive border-0 overflow-y-hidden">
                    <table class="table mb-0 text-nowrap table-centered table-hover">
                        <thead class="table-secondary">
                            <tr>

                                <th>NO</th>
                                <th>ACTION</th>
                                <th>RANK</th>
                                <th>RANK CODE</th>
                                <th>RANK LEVEL</th>
                                <th>RANK DEPARTMENT</th>

                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($rank_maintenance as $ranks)

                            <tr>
                                <td>
                                    <a href="#" class="text-inherit">
                                        <h5 class="mb-0 text-primary-hover">{{ $loop->index + 1}}</h5>
                                    </a>

                                </td>
                                <td>

                                    <div class="btn-group" role="group">
                                        <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Setting
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editmodal" wire:click="rankedit({{ $ranks->rankid }})"><i
                                                class="fe fe-edit"></i> Edit</a>

                                             @if ($ranks->IsPDECert == 1)
                                               
                                                <a class="dropdown-item text-danger mb-1" wire:click.prevent="deactivatePDE({{ $ranks->rankid }})"> <i
                                                    class="fe fe-send "></i> Deactivate Pde</a>
                                               
                                            @else
                                                <a class="dropdown-item" wire:click="activatePDE({{ $ranks->rankid }})"><i
                                                    class="fe fe-send "></i> Activate Pde </a>
                                            @endif 
                                            
                                            {{-- <a class="dropdown-item" href="#" wire:click="activePdeConfirmation({{ $ranks->rankid }})"><i
                                                class="fe fe-send "></i> Activate PDE</a>     --}}
                                            

                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {{ $ranks->rank }}
                                </td>
                                <td>
                                    {{ $ranks->rankacronym }}

                                </td>
                                <td>
                                    {{ $ranks->ranklevel->ranklevel }}
                                </td>
                                <td>
                                    {{ $ranks->rankdepartment->rankdepartment }}
                                </td>
                            </tr>

                            @endforeach


                        </tbody>
                    </table>
                    <div class="card-footer">
                        <div class="row">
                            {{ $rank_maintenance->appends(['search' => $search])->links('livewire.components.customized-pagination-link')}}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Edit Rank Modal -->
    <div wire:ignore.self class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="editmodal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title mb-0" id="newCatgoryLabel">
                        Edit Rank
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="rankupdate">
                        @csrf
                        <div class="mb-3 mb-2">
                            <label class="form-label" for="title">Rank name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model.defer="rankstredit" required>

                            <label class="form-label" for="title">Rank code<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model.defer="rankacronymedit" required>

                            <label class="form-label" for="title">Rank level<span class="text-danger">*</span></label>
                            <select class="form-select text-black" data-width="100%" wire:model.defer="selectranklevel">                              
                            <option value="">Select rank level</option>
                                @foreach ($ranklevel as $ranklevels)
                                  <option value="{{ $ranklevels->ranklevelid }}">{{ $ranklevels->ranklevel }}</option>  
                                @endforeach                  
                            </select>

                            <label class="form-label" for="title">Rank department<span class="text-danger">*</span></label>
                                
                            <select class="form-select text-black" data-width="100%" wire:model.defer="selectrankdepartment">
                                <option value="">Select rank department</option>
                                @foreach ($rankdepartment as $rankdepartments)
                                  <option value="{{ $rankdepartments->rankdepartmentid }}">{{ $rankdepartments->rankdepartment }}</option>  
                                  @endforeach
                            </select>                          
                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary">Update Rank</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Rank Modal -->


    <!-- ADD Rank Modal -->
    <div wire:ignore class="modal fade" id="addmodal" tabindex="-1" role="dialog" aria-labelledby="addmodal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title mb-0" id="newCatgoryLabel">
                        Add New Rank
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="rankadd">
                        @csrf
                        <div class="mb-3 mb-2">
                            <label class="form-label" for="title">Rank name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model.defer="rankstr" required>

                            <label class="form-label" for="title">Rank code<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model.defer="rankacronym" required>

                            <label class="form-label" for="title">Rank level<span class="text-danger">*</span></label>                         
                            <select class="form-select" data-width="100%" wire:model.defer="selectranklevel">
                                <option value="">-- Select option --</option>
                                @foreach ($ranklevel as $ranklevels)
                                <option value="{{$ranklevels->ranklevelid}}">{{$ranklevels->ranklevel}}</option>
                                @endforeach
                            </select>

                            <label class="form-label" for="title">Rank department<span class="text-danger">*</span></label>
                            <select class="form-select" data-width="100%" wire:model.defer="selectrankdepartment">
                                <option value="">-- Select option --</option>
                                @foreach ($rankdepartment as $rankdepartments)
                                <option value="{{$rankdepartments->rankdepartmentid}}">
                                    {{$rankdepartments->rankdepartment}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary">Add Rank</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- ADD Rank Modal -->


    <!-- Modal -->
    <div wire:ignore class="modal fade" id="activatepdemodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Activate PDE</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                Are you sure you want to activate pde?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>



</section>