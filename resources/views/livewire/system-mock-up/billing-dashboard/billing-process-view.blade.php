<div class="container-fluid mt-5">

    <div class="card text-center">

        <div class="card-header">
            <h1 class="h1 fw-bold mt-3"> {{ $status }}</h1>
            <h5> {{ $company }}</h5>
            <span class="badge text-bg-info float-start">{{ $course }}</span>
            <span class="badge text-bg-info float-end">Training Date: {{ $trainingDate }}</span>
        </div>

        <style>
            .position-absolute {
                position: relative;
                width: 100%;
                border: 1px solid #e3e6f0;
                background-color: rgb(27, 135, 193);
            }
        </style>
        <div class="col-md-12 m-4">
            <div class="col-md-4 float-start">
                <x-request-message />
                <div class="card" id="courseAccordion">
                    
                    <ul class="list-group list-group-flush position-absolute">
                        <li class="list-group-item p-0 bg-transparent">
                            
                            <a class="d-flex align-items-center text-inherit text-decoration-none py-3 px-4"
                                data-bs-toggle="collapse" href="#courseTwo" role="button" aria-expanded="false"
                                aria-controls="courseTwo">
                                <div class="me-auto text-start">
                                    <h4 class="mb-0 text-white"> <i class="bi bi-receipt me-1"></i> Billing Functions
                                    </h4>
                                    <p class="mb-0 text-white">Click me to show more options.</p>
                                </div>
                                
                                <span class="chevron-arrow ms-4 text-white">
                                    <i class="bi bi-plus-lg"></i>
                                </span>
                            </a>
                            
                            <div class="collapse" id="courseTwo" data-bs-parent="#courseAccordion">
                                
                                <style>
                                    .list-group-item {
                                        background-color: #f1f5f9;
                                    }
                                </style>
                                <ul class="list-group list-group-flush">

                                    <li class="list-group-item" style="cursor: pointer;">
                                        <div class="row h-100">
                                            <div class="col-md-6">
                                                <a wire:click="openDocument('Mock Billing Statement.pdf')"
                                                    class="d-flex justify-content-between align-items-center text-inherit text-decoration-none">
                                                    <div class="text-truncate">
                                                        <span class="icon-shape bg-success text-white icon-sm rounded-circle me-2"><i
                                                                class="bi bi-receipt-cutoff"></i></span>
                                                        <span>Generate Billing Statement</span>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item" style="cursor: pointer;">
                                        <a data-bs-target="#addAttachmentModal" data-bs-toggle='modal'
                                            class="d-flex justify-content-between align-items-center text-inherit text-decoration-none">
                                            <div class="text-truncate">
                                                <span class="icon-shape bg-success text-white icon-sm rounded-circle me-2"><i
                                                        class="bi bi-file-earmark-arrow-up"></i></span>
                                                <span>Add Attachment</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="list-group-item" style="cursor: pointer;">
                                        <a data-bs-target="#viewAttachmentModal" data-bs-toggle='modal'
                                            class="d-flex justify-content-between align-items-center text-inherit text-decoration-none">
                                            <div class="text-truncate">
                                                <span class="icon-shape bg-success text-white icon-sm rounded-circle me-2"><i
                                                        class="bi bi-file-earmark-arrow-up"></i></span>
                                                <span>View Attachment</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="list-group-item" style="cursor: pointer;">
                                        <div class="row h-100">
                                            <div class="col-md-6">
                                                <a 
                                                    class="d-flex justify-content-between align-items-center text-inherit text-decoration-none">
                                                    <div class="text-truncate">
                                                        <span class="icon-shape bg-success text-white icon-sm rounded-circle me-2"><i
                                                                class="bi bi-receipt-cutoff"></i></span>
                                                        <span>Confirm</span>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </li>

                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="card-body row mt-8">

            <div class="col-md-12 table-responsive">
                <a  wire:click="openDocument('Mock Attendance Sheet.pdf')"
                    class="btn btn-outline-primary mb-2 me-2 float-start btn-sm">Attendance Sheet</a>
                    <x-mockup-trainee-list-component :traineeListData="$traineeListData" />
            </div>

        </div> 
        
    </div>

    {{-- Add Attachment Modal --}}
    <div wire:ignore.self class="modal fade" id="addAttachmentModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Add Attachment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-12 mt-3">
                                <label class="float-start">Title</label>
                                <input type="text" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                                    wire:model.defer="title">
                                @error('title')
                                <small class="text-danger float-start">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-12 mt-3">
                                <label class="float-start">Attachment type</label>
                                <select class="form-control {{ $errors->has('attachment_type') ? 'is-invalid' : '' }}"
                                    wire:model="attachment_type">
                                    <option value="">Proof of payment</option>
                                </select>
                                @error('attachment_type')
                                <small class="text-danger float-start">{{ $message }}</small>
                                @enderror
                            </div>
                            
                            <div class="col-md-12 mt-3">
                                <label class="float-start">Choose file</label>
                                <input type="file" class="form-control {{ $errors->has('file') ? 'is-invalid' : '' }}"
                                    wire:model.defer="file">
                                @error('file')
                                <small class="text-danger float-start">{{ $message }}</small>
                                @enderror
                            </div>
                            <span wire:loading wire:target='file'><small class="text-success">Uploading...</small></span>
                            <div class="col-md-12 mt-3 ">
                                <button type="submit" wire:target="file" wire:loading.class="disabled"
                                    class="btn btn-primary">Save
                                    changes</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                            </div>
                        </div>
                </div>

            </div>
        </div>
    </div>
    {{-- Add Attachment Modal End--}}

    {{-- View Attachment Modal --}}
    <div wire:ignore.self class="modal fade" id="viewAttachmentModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">View Attachment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Attachment type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                                @foreach ($attachment as $row)
                                    <tr>
                                        <td>{{ $row['title'] }}</td>
                                        <td>{{ $row['attachmentType'] }}</td>
                                        <td>
                                            <div class="row">
                                                <div class="col-lg-6 m-1">
                                                    <a wire:click='openDocument("{{$row['filePath']}}")' class="btn btn-primary">
                                                        <i class="bi bi-cloud-download-fill"></i></a>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>
                                @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    {{-- View Attachment Modal End --}}

</div>