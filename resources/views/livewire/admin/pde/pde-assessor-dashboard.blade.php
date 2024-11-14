<section class="container-fluid p-4">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-md-flex align-items-center justify-content-between">
                    <div class="mb-3 mb-md-0">
                        <h1 class="mb-1 h2 fw-bold">PDE Assessor Dashboard</h1>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item active" aria-current="page">
                                            PDE Assessor Dashboard
                                        </li>
                                    </ol>
                                </nav>
                        </div>
                    <div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="card mb-4">
                <div>
                    <ul class="nav nav-lb-tab  border-bottom-0 " id="tab" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" aria-selected="true">All ({{ $count_pde }})</a>
                      </li>
                    </ul>
                </div>

                <div class="card-header border-bottom-0">
                   <form class="d-flex align-items-center col-12 col-md-12 col-lg-12">
                    @csrf
                    <span class="position-absolute ps-3 search-icon"><i class="fe fe-search"></i></span>
                    <input type="search" class="form-control ps-6" wire:model.debounce.500ms="search"
                        placeholder="Search Crew or Certificate Number">
                </form>
                </div>

                <div class="table-responsive table-sm border-0 overflow-y-hidden">
                    <table class="table mb-0 text-nowrap table-centered table-hover table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>No.</th>
                                <th>Action</th>
                                <th>Name</th>
                                <th>Crew Details</th>  
                                <th> </th>
                            </tr>
                        </thead>
                        <tbody>
                         @foreach ($AssessorPdeRecord as $AssessorPdeRecords )
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>  
                                    <td> 
                                        {{-- <a href="" download="" class="btn btn-success"><i class="bi bi-check2"></i> Approved </a>
                                        <a href="" download="" class="btn btn-danger"><i class="bi bi-x"></i> Dissaproved </a> <br/> --}}
                                        <a href="/storage/uploads/pdefiles/{{ $AssessorPdeRecords->attachmentpath }}" download="{{  $AssessorPdeRecords->attachment_filename }}" class="btn btn-info btn-sm mt-2"><i class="bi bi-download"></i> Requirements </a> <br/>
                                        @if ($AssessorPdeRecords->assessmentpdf != null )
                                        <a href="/storage/uploads/pdefiles/{{ $AssessorPdeRecords->assessmentpdf }}" download="{{  'ASSESSMENT ' .$AssessorPdeRecords->surname  }}" class="btn btn-danger btn-sm mt-2"><i class="bi bi-file-earmark-pdf"></i> Assessment </a> <br /> 
                                        @endif
            
                                         @if ($AssessorPdeRecords->certificatepdf != null )
                                         <a href="/storage/{{ $AssessorPdeRecords->certificatepdf }}" download="{{ 'CERTIFICATE ' .$AssessorPdeRecords->surname }}" class="btn btn-danger btn-sm mt-2"><i class="bi bi-file-earmark-pdf"></i> Certificate </a>
                                        @endif
                                       
                                    </td>
                                    <td>
                                        <small>
                                            <span class="badge bg-success btn-sm">{{ $AssessorPdeRecords->pdeID}}</span> <br/> 
                                            <span class="badge bg-success btn-sm mt-2">{{ $this->getStatusLabel($AssessorPdeRecords->statusid) }}</span> <br />
                                            <strong class="text-muted fw-bold mt-2">Surname : </strong>   {{ $AssessorPdeRecords->surname }} <br />
                                            <strong class="text-muted fw-bold mt-2">Firstname: </strong>   {{ $AssessorPdeRecords->givenname }} <br />
                                            <strong class="text-muted fw-bold mt-2">Middlename: </strong>    {{ $AssessorPdeRecords->middlename }} <br />
                                            <strong class="text-muted fw-bold mt-2">Suffix: </strong>  {{ $AssessorPdeRecords->suffix }} <br />
                                        </small>
                                    </td>  
                                    <td>
                                        <small>
                                            <strong class="text-muted fw-bold mt-2">Date of Birth : </strong> {{ $AssessorPdeRecords->dateofbirth }} <br />
                                            <strong class="text-muted fw-bold mt-2">Age: </strong>   {{ $AssessorPdeRecords->age }} <br />
                                            <strong class="text-muted fw-bold mt-2">Position : </strong> {{ $AssessorPdeRecords->position }} <br />
                                            <strong class="text-muted fw-bold mt-2">Vessel : </strong> {{ $AssessorPdeRecords->vessel }} <br />
                                            {{-- <strong class="text-muted fw-bold mt-2">Company : </strong> {{ $AssessorPdeRecords->company->company }} <br /> --}}
                                            <strong class="text-muted fw-bold mt-2">Company : </strong> {{ optional($AssessorPdeRecords->company)->company ?? 'N/A' }} <br />
                                            <strong class="text-muted fw-bold mt-2">Passport No: </strong> {{ $AssessorPdeRecords->passportno }} <br />
                                            <strong class="text-muted fw-bold mt-2">Passport Expiry: </strong>  {{ $AssessorPdeRecords->passportexpirydate }} <br />
                                            <strong class="text-muted fw-bold mt-2">Medical Expiry : </strong>  {{ $AssessorPdeRecords->medicalexpirydate }} <br />
                                     
                                        </small>
                                    </td>        
                                    <td>
                                        <small>
                                            <strong class="text-muted fw-bold mt-2">Certificate No : </strong> {{ $AssessorPdeRecords->certificatenumber }} <br />
                                            <strong class="text-muted fw-bold mt-2">Reference No : </strong>{{ $AssessorPdeRecords->referencenumber }} <br />
                                            <strong class="text-muted fw-bold mt-2">Requested Date : </strong> {{ $AssessorPdeRecords->created_at }}<br />
                                            <strong class="text-muted fw-bold mt-2">Requested By : </strong> {{ $AssessorPdeRecords->requestby }}<br />
                                            <strong class="text-muted fw-bold mt-2">Cert printed date  : </strong> {{ $AssessorPdeRecords->certdateprinted }} <br />
                                            <strong class="text-muted fw-bold mt-2">Cert valid date : </strong>{{ $AssessorPdeRecords->certvaliduntil }} <br />
                                            <strong class="text-muted fw-bold mt-2">Cert printed by: </strong> {{ $AssessorPdeRecords->certprintedby }}<br />
                                        </small>
                                    </td>        
                                                   
                                </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="card-footer">
                        <div class="row">
                            {{ $AssessorPdeRecord->appends(['search' => $search])->links('livewire.components.customized-pagination-link')}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</section>

