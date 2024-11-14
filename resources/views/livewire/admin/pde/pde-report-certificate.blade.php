<section class="container-fluid p-4">
    <div class="row">
        <span wire:loading>
            <livewire:components.loading-screen-component />
        </span>
        <!-- Page Header -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-md-flex align-items-center justify-content-between">
                <div class="mb-3 mb-md-0">
                    <h1 class="mb-1 h2 fw-bold">PDE Certificate</h1>
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('a.pdereport') }}">Dashboard</a>
                            </li>

                            <li class="breadcrumb-item active" aria-current="page">
                                Certificate
                            </li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('a.pdereport') }}" class="btn btn-outline-secondary">Back to All Category</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">

            <div class="card mb-4">
                <!-- Card header -->
                <div class="card-header border-bottom-0">
                    <!-- Form -->
                    <!-- Card -->
                    
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#CertificateSerialModal">
                        <i class="fe fe-edit" style="color: white;"></i> Certificate Serial Number Paper
                    </button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#CertificateNumberModal">
                        <i class="fe fe-edit" style="color: white;"></i>  Certificate Number
                    </button>
                    
                    <form class="d-flex align-items-center col-12 col-md-12 col-lg-12 mt-5">
                        <span class="position-absolute ps-3 search-icon"><i class="fe fe-search"></i></span>
                        <input type="search" class="form-control ps-6" wire:model.debounce.500ms="search"
                            placeholder="Search Crew or PDEID ">
                    </form>
                </div>
                <!-- Table -->

                <div wire:ignore.self class="table-responsive">
                    <table class="table mb-0 text-nowrap table-hover table-centered">
                        <thead class="table-light">
                            <tr>

                                <th>No</th>
                                <th>Action</th>
                                <th> </th>   
                                <th>Name </th>                       
                                <th>Employment Details</th>
                                

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pdecertificatesdata as $certificates)

                            <tr>
                                <td>{{ $loop->index + 1}}</td>
                                <td> <button href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#generateModal" wire:click="pdegeneratecertificate({{ $certificates->pdeID }})">
                                        <i class="fe fe-file-text" style="color: white;"></i> Print Certificate
                                    </button>

                                    <button href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                         data-bs-target="#updatecrewpicture" wire:click="pdepicedit({{ $certificates->pdeID }})">
                                          <i class="fe fe-image" style="color: white;" title="Upload"></i> Update Picture
                                    </button>
                                </td>
                              
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="position-relative">
                                            
                                            @if ($certificates->imagepath)
                                            <img src="{{ asset('storage/uploads/pdecrewpicture/' . $certificates->imagepath) }}" alt=""
                                                 class="img-4by3-lg rounded">

                                        @else
                                            <img src="{{ asset('assets/images/oesximg/no-image.svg') }}" alt=""
                                                 class="img-4by3-lg rounded">
                                        @endif 
                                          
                                        </div>
                                       
                                    </div>
                                </td>
                                <td> 
                                    <small>
                                        <strong class="text-muted fw-bold mt-2">Surname : </strong> {{ $certificates->surname }} <br />
                                        <strong class="text-muted fw-bold mt-2">Firstname: </strong> {{ $certificates->givenname }} <br />
                                        <strong class="text-muted fw-bold mt-2">Middlename: </strong>  {{ $certificates->middlename }} <br />
                                        <strong class="text-muted fw-bold mt-2">Suffix: </strong> {{ $certificates->suffix }} <br />
                                    </small>
                                </td>
                              
                                <td>
                                    <small>
                                        <strong class="text-muted fw-bold mt-2">Rank : </strong> {{ $certificates->position }} <br />
                                        <strong class="text-muted fw-bold mt-2">Date Assessment: </strong> {{ $certificates->TRDateprinted }} <br />
                                        <strong class="text-muted fw-bold mt-2">Receipt: </strong>  {{ $certificates->referencenumber }} <br />
                                        <strong class="text-muted fw-bold mt-2">Requested By : </strong> {{ $certificates->requestby }} <br />
                                    </small>
                                </td>


                            </tr>

                            @endforeach

                        </tbody>

                    </table>
                    <div class="card-footer">
                        <div class="row">
                            {{ $pdecertificatesdata->appends(['search' =>
                            $search])->links('livewire.components.customized-pagination-link')}}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Generate Certificate Modal -->
    <div wire:ignore.self class="modal fade" id="generateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel" wire:model.defer="title">Warning</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="datatogeneratecertificate" id="datatogeneratecertificate">  
                        <input type="text" class="form-control ps-6" wire:model.defer="pdeid" name="pdeid" hidden>
                        <div class="row gx-3">
                            <!-- Warning alert -->
                        <div class="alert alert-warning d-flex align-items-center" role="alert">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill me-2" viewBox="0 0 16 16">
                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                            </svg>
                            <div>
                                    You are about to generate the certificate for {{ $title }}. Are you sure you want to proceed? 
                            </div>
                        </div>
                            
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="datatogeneratecertificate" class="btn btn-primary">Proceed</button>
                </div>
            </div>
        </div>
    </div>

<!-- Certificate Serial Number Modal -->
<div wire:ignore.self class="modal fade" id="CertificateSerialModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Certificate Serial Number</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="getlastpdecertificateserialno_update" id="getlastpdecertificateserialno_update">
                            @csrf
                            <label>Last Generated Serial Number</label>
                            <input type="text" class="form-control" name="serialnumberedit" wire:model="serialnumberedit" required>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" form="getlastpdecertificateserialno_update" class="btn btn-primary">Update Serial Number</button>
                    </div>
                </div>
            </div>
        </div>



    
<!-- Certificate Number Modal -->
<div wire:ignore.self class="modal fade" id="CertificateNumberModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Certificate Serial Number</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="getlastcertificatenumber1" id="getlastcertificatenumber1">
                    @csrf
                    <label>Last Generated Certificate Number</label>
                    <input type="text" class="form-control" name="certificatenumberedit" wire:model.defer="certificatenumberedit" required>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="getlastcertificatenumber1" class="btn btn-primary">Update Certificate Number</button>
            </div>
        </div>
    </div>
</div>


<div wire:ignore.self class="modal fade" id="updatecrewpicture" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Upload Picture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="updatecrewpic" id="updatecrewpic" enctype="multipart/form-data">
                    {{-- <div class="custom-file-container" data-upload-id="courseImage" wire:model="coverfile" accept="image/png, image/jpg, image/jpeg"> </div> --}}
                    <input type="file" class="custom-file-input" wire:model="coverfile" accept="image/png, image/jpg, image/jpeg">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="updatecrewpic" class="btn btn-primary">Upload</button>
            </div>
        </div>
    </div>
</div>


</section>