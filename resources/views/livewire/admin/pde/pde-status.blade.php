<section class="container-fluid p-4">
    <div class="row">
        <!-- Page Header -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-md-flex align-items-center justify-content-between">
                <div class="mb-3 mb-md-0">
                    <h1 class="mb-1 h2 fw-bold">PDE Status</h1>
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route( Auth::user()->pde_request_route) }}">PDE</a>
                            </li>
                       
                            <li class="breadcrumb-item active" aria-current="page">
                              PDE Status
                            </li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route( Auth::user()->pde_request_route) }}" class="btn btn-primary"> Request PDE </a>
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
                        placeholder="Search Crew">
                </form>
                </div>
                <!-- Table -->
                <div class="table-responsive table-sm border-0 overflow-y-hidden">
                    <table class="table mb-0 text-nowrap table-centered table-hover table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>No.</th>
                                <th>Action</th>
                                <th>Date Requested</th>
                                <th>Status</th>  
                                {{-- <th>Requested by</th> --}}
                                <th>Surname</th>
                                <th>Firstname</th>
                                <th>Middlename</th>
                                <th>Suffix</th>
                                <th>Date of Birth</th>
                                <th>Position</th>
                                <th>Vessels</th>
                                <th>Passport No.</th>   
                                <th>Passport Exp. Date</th>            
                                <th>Medical Exp. Date</th>  
                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mypdeStatusRecords as $record)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td> <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal" wire:click="pdeedit({{ $record->pdeID}})" ><i class="fe fe-edit" style="color: white;"></i> Edit </button>
                                          <a href="/storage/uploads/pdefiles/{{ $record->attachmentpath }}" download="{{  $record->attachment_filename }}" class="btn btn-info btn-sm"><i class="bi bi-download"></i> Download </a>
                                          {{-- <a href="{{ route('a.uploadpderequirements', $record->pdeID) }}"  class="btn btn-info btn-sm"><i class="bi bi-upload"></i> Upload Requirements </a> --}}
                                    </td>
                                    <td>{{ $record->created_at }}</td>
                                    <td>
                                        <span class="badge bg-success btn-sm">{{ $this->getStatusLabel($record->statusid) }}</span>
                                    </td>
                                    {{-- <td>{{ $record->requestby }}</td> --}}
                                    <td>{{ $record->surname }}</td>
                                    <td>{{ $record->givenname }}</td>
                                    <td>{{ $record->middlename }}</td>
                                    <td>{{ $record->suffix }}</td>
                                    <td>{{ $record->dateofbirth }}</td>
                                    <td>{{ $record->position }}</td>
                                    <td>{{ $record->vessel }}</td>
                                    <td>{{ $record->passportno }}</td>
                                    <td>{{ $record->passportexpirydate }}</td>
                                    <td>{{ $record->medicalexpirydate }}</td>
                                   
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="card-footer">
                        <div class="row">
                            {{ $mypdeStatusRecords->appends(['search' => $search])->links('livewire.components.customized-pagination-link')}}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- EDIT CREW INFORMATION MODAL -->
<!-- EDIT CREW INFORMATION MODAL -->
<div wire:ignore.prevent class="modal fade gd-example-modal-lg" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel"><i class="fe fe-edit" ></i>  Update Crew Information</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <form wire:submit.prevent="pdeupdate" id="pdeupdate" >
                <!-- row -->
                <div class="row gx-3">
                  <!-- input -->
                <div class="mb-3 col-md-4">
                  <label class="form-label" for="firstname">Firstname</label>
                  <input type="text" class="form-control" wire:model.defer="firstname"  required>
                </div>
                  <!-- input -->
                <div class="mb-3 col-md-3">
                  <label class="form-label" for="middlename">Middle Name</label>
                  <input type="text" class="form-control" wire:model="middlename"required>
                </div>
             
                  <!-- input -->
                <div class="mb-3 col-md-3">
                  <label class="form-label" for="lastname">Last Name</label>
                  <input type="text" class="form-control" wire:model="lastname"required>

                </div>
                <div class="mb-3 col-md-2">
                  <label class="form-label" for="suffix">Suffix</label>
                  <input type="text" class="form-control" wire:model="suffix" >
                </div>
            
                <div class="mb-3 col-md-6">
                  <label class="form-label" for="birthday">Birthday</label>
                  <input type="date" wire:model.defer="birthday" class="form-control sb-form-control-solid flatpickr" placeholder="--Not Set--" id="dateOfBirth"required>
                </div>

                <div class="mb-3 col-md-6">
                  <label class="form-label" for="age">Age</label>
                  <input type="number" class="form-control"  id="age" wire:model="age" disabled>
                </div>

                  <!-- input -->
                <div class="mb-3 col-md-6">
                  <label class="form-label" for="position">Position</label>
                  <select class="form-select text-black" data-width="100%" wire:model="selectedPosition" required>
                      <option value="" disabled>--Select option--</option>
                      @foreach ($retrieverank as $retrieveranks)
                          <option value="{{ $retrieveranks->rankid }}">{{ $retrieveranks->rank }}</option>
                      @endforeach 
                  </select>
                </div>
                <div class="mb-3 col-md-6">
                  <label class="form-label" for="vessels">Vessels</label>
                  <input type="text" class="form-control" wire:model="vessels" required>
                </div>

                <div class="mb-3 col-md-6">
                  <label class="form-label" for="passportno">Passport No</label>
                  <input type="text" class="form-control" wire:model="passportno" required>
                </div>

                <div class="mb-3 col-md-6">
                  <label class="form-label" for="passportexpirydate">Passport Expiry Date</label>
                  <input type="date" wire:model.defer="passportexpirydate" class="form-control sb-form-control-solid flatpickr" placeholder="--Not Set--" required>
                </div>

                <div class="mb-3 col-md-6">
                  <label class="form-label" for="medicalexpirydate">Medical Expiry Date<</label>
                  <input type="date" wire:model.defer="medicalexpirydate" class="form-control sb-form-control-solid flatpickr" placeholder="--Not Set--" required>
                </div>

                <div class="mb-3 col-md-12">
                  <label class="form-label" for="fileattachment">Attachment</label>
                  <div class="alert alert-primary d-flex align-items-center" role="alert">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-info-circle-fill me-2" viewBox="0 0 16 16">
                         <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                      </svg>
                      <div>
                          OPTIONAL - select file if you want to change the attachment and files must be zipped.
                      </div>
                   </div>
                   <input type="file" class="form-control" wire:model="fileattachment" accept=".zip, .rar">

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

