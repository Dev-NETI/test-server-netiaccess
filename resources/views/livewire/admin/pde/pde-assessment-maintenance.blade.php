<section class="container-fluid p-4">
    <div class="row">
      <span wire:loading>
        <livewire:components.loading-screen-component />
    </span>
      <!-- Page header -->
      <div class="col-lg-12 col-md-12 col-12">
        <div class="border-bottom pb-3 mb-3 d-md-flex align-items-center justify-content-between">
          <div class="mb-3 mb-md-0">
            <h1 class="mb-1 h2 fw-bold">Pde Assessment Maintenance </h1>
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">
                  <a href="{{ route('a.dashboard') }}">Dashboard </a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('a.pdemaintenance') }}">Pde Maintenance </a></li>
                <li class="breadcrumb-item active" aria-current="page">
                  Pde Assessment Maintenance
                </li>
              </ol>
            </nav>
          </div>
          <div>
            <a href="{{ route('a.pdemaintenance') }}" class="btn btn-outline-secondary">Back to All Post</a>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xl-9 col-lg-8 col-md-12 col-12">
        <!-- Card -->
        <div class="card border-0 mb-4">
          <!-- Card header -->
          <div class="card-header">
            <h4 class="mb-0">Pde Assessment form for {{ $rank->rank }}</h4>
          </div>
          <!-- Card body -->
          <div class="card-body">
            <div class="">
              <!-- Form -->
              <form id="updatepdemaintenance" wire:submit.prevent="updatepdemaintenance">
              <div class="row">
              
                <div class="mb-3 col-md-12">
                  <label for="selectDate" class="form-label">Template</label>
                  <input type="file" class="form-control" wire:model.defer="pdeassessmentpath" accept=".pdf">
                </div>
                <h3>CREW INFORMATION</h3>
                <hr>
                <div class="mb-3 col-md-3">
                  <label for="selectDate" class="form-label">Firstname (X,Y)</label>
                  <input type="text" class="form-control text-dark" wire:model.defer="firstnamealign" placeholder="">
                </div>
                <div class="mb-3 col-md-2">
                  <label for="selectDate" class="form-label">Middlename (X,Y)</label>
                  <input type="text" class="form-control text-dark" wire:model.defer="middlenamealign" placeholder="">
                </div>
                <div class="mb-3 col-md-3">
                  <label for="selectDate" class="form-label">Surname (X,Y)</label>
                  <input type="text" class="form-control text-dark" wire:model.defer="surnamealign" placeholder="">
                </div>
                <div class="mb-3 col-md-2">
                  <label for="selectDate" class="form-label">Suffix (X,Y)</label>
                  <input type="text" class="form-control text-dark" wire:model.defer="suffixalign" placeholder="">
                </div>
                <div class="mb-3 col-md-2">
                  <label for="selectDate" class="form-label">Age (X,Y)</label>
                  <input type="text" class="form-control text-dark" wire:model.defer="agealign" placeholder="">
                </div>
  
  
                <div class="mb-3 col-md-4">
                  <label for="selectDate" class="form-label">Passport No (X,Y)</label>
                  <input type="text" class="form-control text-dark" wire:model.defer="passportalign" placeholder="">
                </div>
                <div class="mb-3 col-md-4">
                  <label for="selectDate" class="form-label">Passport Expiry (X,Y)</label>
                  <input type="text" class="form-control text-dark" wire:model.defer="passportexpiryalign" placeholder="">
                </div>
              
                <div class="mb-3 col-md-4">
                  <label for="selectDate" class="form-label">Medical Expiry (X,Y)</label>
                  <input type="text" class="form-control text-dark" wire:model.defer="medicalexpiryalign" placeholder="">
                </div>

                <div class="mb-3 col-md-12">
                  <label for="selectDate" class="form-label">Application Date (X,Y)</label>
                  <input type="text" class="form-control text-dark" wire:model.defer="applicationdatealign" placeholder="">
                </div>

  
                <div class="mb-3 col-md-6">
                  <label for="selectDate" class="form-label">Company (X , Y)</label>
                  <input type="text" class="form-control text-dark" wire:model.defer="companyalign" placeholder="">
                </div>
                <div class="mb-3 col-md-6">
                  <label for="selectDate" class="form-label">Receipt (X , Y)</label>
                  <input type="text" class="form-control text-dark" wire:model.defer="receiptalign" placeholder="">
                </div>
  
                <h3 class="mt-2">ASSESSOR SIGNATURE</h3>
                <hr>
  
                <div class="mb-3 col-md-6">
                  <label for="selectDate" class="form-label">Assessor name 1 (X,Y)</label>
                  <input type="text" class="form-control text-dark" wire:model.defer="assessornamealign1" placeholder="" >
                </div>
                <div class="mb-3 col-md-6">
                  <label for="selectDate" class="form-label">Assessor e-sign 2 (X,Y)</label>
                  <input type="text" class="form-control text-dark" wire:model.defer="assessoresignalign1" placeholder="" >
                </div>
                <div class="mb-3 col-md-6">
                  <label for="selectDate" class="form-label">Assessor name 2 (X,Y)</label>
                  <input type="text" class="form-control text-dark" wire:model.defer="assessornamealign2" placeholder="" >
                </div>
  
                <div class="mb-3 col-md-6">
                  <label for="selectDate" class="form-label">Assessor e-sign 2 (X,Y)</label>
                  <input type="text" class="form-control text-dark" wire:model.defer="assessoresignalign2" placeholder="" >
                </div>
  
            
  
                <h3>DEPARTMENT HEAD ALIGNMENT</h3>
                <hr>
                <div class="mb-3 col-md-6">
                  <label for="selectDate" class="form-label">Department Head Name  (X,Y)</label>
                  <input type="text" class="form-control text-dark" wire:model.defer="departmentnamealign" placeholder="" >
                </div>
                <div class="mb-3 col-md-6">
                  <label for="selectDate" class="form-label">Department Head e-sign (X,Y)</label>
                  <input type="text" class="form-control text-dark" wire:model.defer="departmentesignalign" placeholder="" >
                </div>
                <h3>GENERAL MANAGER ALIGNMENT</h3>
                <hr>
                <div class="mb-3 col-md-6">
                  <label for="selectDate" class="form-label">General Manager Name (X,Y)</label>
                  <input type="text" class="form-control text-dark" wire:model.defer="generalmanagernamealign" placeholder="" >
                </div>
                <div class="mb-3 col-md-6">
                  <label for="selectDate" class="form-label">General Manager e-sign  (X,Y)</label>
                  <input type="text" class="form-control text-dark" wire:model.defer="generalmanageresigns" placeholder="" >
                </div>
              </div>
                 <!-- button -->
              <button type="submit" class="btn btn-primary"> Save changes </button>
            </form>
            </div>
         
          
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-4 col-md-12 col-12">
      
        <div class="card mb-4">
          <!-- Card Header -->
          <div class="card-header d-lg-flex">
            <h4 class="mb-0">Actions</h4>
          </div>
          <!-- List group -->
          <ul class="list-group list-group-flush">
            <a href="{{route('a.massessmenttemplate', ['rankid' => $rank->rankid])}}" target="_blank">
              <li class="list-group-item d-flex justify-content-between align-items-center btn btn-secondary">
                  <span class="">Preview Certicate</span>
                  <i class="fe fe-eye fs-4"></i>
              </li>
          </a>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <span class="text-body">Delete</span>
              <a href="#"><i class="fe fe-trash text-danger fs-4"></i></a>
            </li>
          </ul>
        </div>
        <!-- Card  -->
        {{-- <div class="card">
          <!-- Card header -->
          <div class="card-header d-lg-flex">
            <h4 class="mb-0">Revision History</h4>
          </div>
          <!-- List group -->
          <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <div>
                <h5 class="mb-0">Aug 31, 12:21 PM</h5>
                <span class="text-body">Geeks Coures</span>
              </div>
              <div>
                <span class="badge bg-success badge-pill">Published</span>
              </div>
            </li>
          </ul>
        </div> --}}
      </div>
    </div>
  </section>