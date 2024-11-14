<section class="container-fluid p-4">
    <div class="row">
        <!-- Page Header -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-md-flex align-items-center justify-content-between">
                <div class="mb-3 mb-md-0">
                    <h1 class="mb-1 h2 fw-bold">PDE History</h1>
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('a.pdereport') }}">Dashboard</a>
                            </li>

                            <li class="breadcrumb-item active" aria-current="page">
                                History
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
            <!-- Card -->
            <div class="card mb-4">
                <!-- Card header -->
                <div class="card-header border-bottom-0">
                    <!-- Form -->
                    <form class="d-flex align-items-center col-12 col-md-12 col-lg-12">
                        @csrf
                        <span class="position-absolute ps-3 search-icon"><i class="fe fe-search"></i></span>
                        <input type="search" class="form-control ps-6" wire:model.debounce.500ms="search"
                            placeholder="Search Crew ">
                    </form>
                </div>
                <!-- Table -->

                <div  wire:ignore.self class="table-responsive">
                    <table class="table mb-0 text-nowrap table-centered table-hover text-sm">
                        <thead style="background-color: #f8f9fa;">
                            <tr >

                                <th>No</th>
                                <th>Picture</th>
                                <th>Name</th>
                                <th>Crew Details </th>
                                <th></th>      
                                <th> </th>         
                                <th> </th>     
                                                 
                               
                               


                            </tr>
                        </thead>
                        <tbody>
                            @foreach ( $pdehistory as $history )                  
                            <tr>
                                <td>{{$loop->index+1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="position-relative">
                                            
                                            @if ($history->imagepath)
                                                <img src="{{ asset('storage/uploads/pdecrewpicture/' . $history->imagepath) }}" alt=""
                                                 class="img-4by3-lg rounded">
                                            @else
                                                 <img src="{{ asset('assets/images/avatar/avatar.jpg') }}" alt=""
                                                 class="img-4by3-lg rounded">
                                            @endif 
                                          
                                        </div>
                                </td>
                                    </div>
                                <td>
                                    <small>
                                        <span class="badge bg-success btn-sm">{{ $history->pdeID}}</span> <br/>
                                        <strong class="text-muted fw-bold mt-2">Surname : </strong>   {{ $history->surname }} <br />
                                        <strong class="text-muted fw-bold mt-2">Firstname: </strong>   {{ $history->givenname }} <br />
                                        <strong class="text-muted fw-bold mt-2">Middlename: </strong>    {{ $history->middlename }} <br />
                                        <strong class="text-muted fw-bold mt-2">Suffix: </strong>  {{ $history->suffix }} <br />
                                        <button type="button" class="btn btn-danger btn-sm" title="Edit Crew Information" data-bs-toggle="modal" data-bs-target="#editModal" wire:click="pdeedit({{ $history->pdeID}})"> 
                                            <i class="fe fe-edit dropdown-item-icon" style="color: white;"></i> </button>
                                            <a href="/storage/uploads/pdefiles/{{ $history->attachmentpath }}" download="{{  $history->attachment_filename }}" title="Download Crew Attachment" class="btn btn-info btn-sm"><i class="bi bi-download"></i></a>
                                    </small>

                                </td>   
                                   
                                <td>
                                    <small>
                                        <strong class="text-muted fw-bold mt-2">Date of Birth : </strong> {{ $history->dateofbirth }} <br />
                                        <strong class="text-muted fw-bold mt-2">Age: </strong>   {{ $history->age }} <br />
                                        <strong class="text-muted fw-bold mt-2">Position : </strong> {{ $history->position }} <br />
                                        <strong class="text-muted fw-bold mt-2">Vessel : </strong> {{ $history->vessel }} <br />
                                        <strong class="text-muted fw-bold mt-2">Company : </strong> {{ $history->company->company }} <br />
                                        <strong class="text-muted fw-bold mt-2">Passport No: </strong> {{ $history->passportno }} <br />
                                        <strong class="text-muted fw-bold mt-2">Passport Expiry: </strong>  {{ $history->passportexpirydate }} <br />
                                        <strong class="text-muted fw-bold mt-2">Medical Expiry : </strong>  {{ $history->medicalexpirydate }} <br />
                                 
                                    </small>
                                </td>   
                                <td>
                                    <small>
                                        <strong class="text-muted fw-bold mt-2">Certificate No : </strong> {{ $history->certificatenumber }} <br />
                                        <strong class="text-muted fw-bold mt-2">Reference No : </strong>{{ $history->referencenumber }} <br />
                                        <strong class="text-muted fw-bold mt-2">Requested Date : </strong> {{ $history->created_at }}<br />
                                        <strong class="text-muted fw-bold mt-2">Requested By : </strong> {{ $history->requestby }}<br />
                                        <strong class="text-muted fw-bold mt-2">Cert printed date  : </strong> {{ $history->certdateprinted }} <br />
                                        <strong class="text-muted fw-bold mt-2">Cert valid date : </strong>{{ $history->certvaliduntil }} <br />
                                        <strong class="text-muted fw-bold mt-2">Cert printed by: </strong> {{ $history->certprintedby }}<br />
                                    </small>
                                </td> 
                                <td>
                                    <small>
            
                                        <strong class="text-muted fw-bold mt-2">TR Date Printed  : </strong> {{ $history->TRDateprinted }} <br />
                                        <strong class="text-muted fw-bold mt-2">TR Printed by : </strong>{{ $history->TRPrintedBy }} <br />
                                      
                                    </small>

                                </td> 
                                                  

                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                    <div class="card-footer">
                        <div class="row">
                            {{ $pdehistory->appends(['search'=>$search])->links('livewire.components.customized-pagination-link')}}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <!-- Generate Assessment Modal -->
    <div class="modal fade" id="generateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Generate Assessment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="formgenerateassessment" id="formgenerateassessment">
                        @csrf
                        <!-- form -->
                        <div class="row gx-3">
                            <!-- form group -->
                            <div class="mb-3 col-12">
                                <label class="form-label">Select Assessor <span class="text-danger">*</span></label>
                                <select class="form-select text-black" data-width="100%">
                                    <option value="" disabled>--Select--</option>

                                    <option value="">

                                    </option>

                                </select>
                            </div>

                            <!-- form group -->
                            <div class="mb-3 col-md-6 col-12">
                                <label class="form-label">Department Signiture? <span
                                        class="text-danger">*</span></label>
                                <!-- input -->
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                        id="inlineRadio1" value="0">
                                    <label class="form-check-label" for="inlineRadio1">No</label>
                                </div>
                                <!-- input -->
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                        id="inlineRadio2" value="option2">
                                    <label class="form-check-label" for="inlineRadio2">Yes</label>
                                </div>

                            </div>
                            <!-- form group -->
                            <div class="mb-3 col-md-6 col-12">

                            </div>

                            <div class="mb-3 col-12">
                                <label class="form-label">Select Department <span class="text-danger">*</span></label>
                                <br />
                                <select class="form-select text-black" data-width="100%">
                                    <option value="" disabled>--Select--</option>


                                    <option value="">

                                    </option>

                                </select>
                            </div>

                            <!-- form group -->
                            <div class="mb-3 col-md-6 col-12">
                                <label class="form-label">General Manager Signiture?<span class="text-danger">
                                        *</span></label>
                                <!-- input -->
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                        id="inlineRadio1" value="0">
                                    <label class="form-check-label" for="inlineRadio1">No</label>
                                </div>
                                <!-- input -->
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                        id="inlineRadio2" value="option2">
                                    <label class="form-check-label" for="inlineRadio2">Yes</label>
                                </div>



                            </div>


                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Generate Assessment</button>
                </div>
            </div>
        </div>
    </div>

 <!-- EDIT CREW INFORMATION MODAL -->
 <div wire:ignore.prevent class="modal fade gd-example-modal-lg" id="editModal" tabindex="-1" role="dialog"
 aria-labelledby="exampleModalLabel" aria-hidden="true">

 <div class="modal-dialog modal-lg" role="document">
     <div class="modal-content">
         <div class="modal-header">
             <h5 class="modal-title" id="exampleModalLabel">
                 <i class="fe fe-edit" style="color: black;"></i> Update Crew Information
             </h5>
             <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                 <span aria-hidden="true">&times;</span>
             </button>
         </div>
         <div class="modal-body">
             <form wire:submit.prevent="pdeupdate" id="pdeupdate" enctype="multipart/form-data">
                 <!-- row -->
                 <div class="row gx-3">
                     <!-- input -->
                     <div class="mb-3 col-md-12">
                         <label class="form-label" for="pdeid">PDE ID</label>
                         <input type="text" class="form-control" wire:model.defer="editpdeid" required>
                     </div>
                     <div class="mb-3 col-md-6">
                        <label class="form-label" for="pdeid">PDE Certificate Serial No.</label>
                        <input type="text" class="form-control" wire:model.defer="editPDECertPaperSerialNumber" required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="pdeid">Certificate No.</label>
                        <input type="text" class="form-control" wire:model.defer="editcertificatenumber" required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="pdeid">PDE Serial No.</label>
                        <input type="text" class="form-control" wire:model.defer="editpdeserialno" required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="pdeid">Receipt No.</label>
                        <input type="text" class="form-control" wire:model.defer="editreferencenumber" required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="pdeid">Date Issue</label>
                        <input type="date" class="form-control sb-form-control-solid flatpickr"  wire:model.defer="editcertdateprinted" placeholder="--Not Set--" required>      
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="pdeid">Date Valid</label>
                        <input type="date" class="form-control sb-form-control-solid flatpickr"  wire:model.defer="editcertvaliduntil" placeholder="--Not Set--" required>      
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="pdeid">Assessor</label>
                        <select class="form-select text-black" data-width="100%" wire:model.defer="editselectedassessor" required>
                            <option value="" disabled>--Select option--</option>
                            @foreach ($retrieveAssessor as $retrieveAssessors)
                            <option value="{{ $retrieveAssessors->userid }}">  {{ $retrieveAssessors->rankacronym }} {{ $retrieveAssessors->l_name }} {{ $retrieveAssessors->f_name }}</option>
                            @endforeach
                        </select>   
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="pdeid">Department Head</label>
                        <select class="form-select text-black" data-width="100%" wire:model.defer="editselecteddepartmenthead" required>
                        <option value="" disabled>--Select option--</option>
                        @foreach ($retrievedepartment as $retrievedepartments)
                        <option value="{{ $retrievedepartments->coursedepartmentid }}">{{ $retrievedepartments->departmenthead }}</option>
                        @endforeach
                    </select>   
                    </div>
                     <div class="mb-3 col-md-4">
                         <label class="form-label" for="firstname">Firstname</label>
                         <input type="text" class="form-control" wire:model.defer="editfirstname" required>
                     </div>
                     <div class="mb-3 col-md-3">
                         <label class="form-label" for="middlename">Middle Name</label>
                         <input type="text" class="form-control" wire:model.defer="editmiddlename" required>
                     </div>
                     <div class="mb-3 col-md-3">
                         <label class="form-label" for="lastname">Last Name</label>
                         <input type="text" class="form-control" wire:model.defer="editlastname" required>
                     </div>
                     <div class="mb-3 col-md-2">
                         <label class="form-label" for="suffix">Suffix</label>
                         <input type="text" class="form-control" wire:model.defer="editsuffix">
                     </div>
                     <div class="mb-3 col-md-6">
                         <label class="form-label" for="birthday">Birthday</label>
                         <input type="date" wire:model.defer="editbirthday" class="form-control sb-form-control-solid flatpickr" placeholder="--Not Set--" id="dateOfBirth" required>
                     </div>
                     <div class="mb-3 col-md-6">
                         <label class="form-label" for="age">Age</label>
                         <input type="number" class="form-control" id="age" wire:model.defer="editage" disabled>
                     </div>
                     <!-- input -->
                     <div class="mb-3 col-md-6">
                         <label class="form-label" for="position">Company</label>
                         <select class="form-select text-black" data-width="100%"
                             wire:model.defer="editselectedcompany" required>
                             <option value="" disabled>--Select option--</option>
                             @foreach ($retrievecompany as $retrievecompanys)
                             <option value="{{ $retrievecompanys->companyid }}">{{ $retrievecompanys->company }}</option>
                             @endforeach
                         </select>
                     </div>

                     <div class="mb-3 col-md-6">
                         <label class="form-label" for="position">Position</label>
                         <select class="form-select text-black" data-width="100%"
                             wire:model.defer="editselectedPosition" required>
                             <option value="" disabled>--Select option--</option>
                             @foreach ($retrieverank as $retrieveranks)
                             <option value="{{ $retrieveranks->rankid }}">{{ $retrieveranks->rank }}</option>
                             @endforeach
                         </select>
                     </div>

                     <div class="mb-3 col-md-6">
                         <label class="form-label" for="vessels">Vessels</label>
                         <input type="text" class="form-control" wire:model.defer="editvessels" required>
                     </div>
                     <div class="mb-3 col-md-6">
                         <label class="form-label" for="passportno">Passport No</label>
                         <input type="text" class="form-control" wire:model.defer="editpassportno" required>
                     </div>
                     <div class="mb-3 col-md-6">
                         <label class="form-label" for="passportexpirydate">Passport Expiry Date</label>
                         <input type="date" wire:model.defer="passportexpirydate" class="form-control sb-form-control-solid flatpickr" placeholder="--Not Set--" required>
                     </div>

                     <div class="mb-3 col-md-6">
                         <label class="form-label" for="medicalexpirydate">Medical Expiry Date</label>
                         <input type="date" wire:model.defer="medicalexpirydate" class="form-control sb-form-control-solid flatpickr" placeholder="--Not Set--" required>
                     </div>

                     <div class="mb-3 col-md-12">
                         <label class="form-label" for="fileattachment">Attachment</label>
                         <div class="alert alert-primary d-flex align-items-center" role="alert">
                             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                 class="bi bi-info-circle-fill me-2" viewBox="0 0 16 16">
                                 <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
                             </svg>
                             <div>
                                 OPTIONAL - select file if you want to change the attachment and files must be zipped.
                             </div>
                         </div>
                         <input type="file" class="form-control" wire:model.defer="fileattachment">
                     </div>

                 </div>
             </form>
         </div>
         <div class="modal-footer">
             <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
             <button type="submit" form="pdeupdate" class="btn btn-primary">Save changes</button>
         </div>
     </div>
 </div>
</div>

</section>
