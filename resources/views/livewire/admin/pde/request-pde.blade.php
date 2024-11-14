<section class="container-fluid p-4">
<div class="row ">
  <span wire:loading>
    <livewire:components.loading-screen-component />
  </span>
  <div class="col-lg-12 col-md-12 col-12">
    <div class="border-bottom pb-3 mb-3 d-lg-flex align-items-center justify-content-between">
      <div class="mb-2 mb-lg-0">
        <h1 class="mb-0 h2 fw-bold">PDE Application Form </h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item active">PDE</li>
            <li class="breadcrumb-item active" aria-current="page"> Application Form</li>
          </ol>
        </nav>
      </div>
      <div>
        <a href="{{ route( Auth::user()->pde_status_route) }}" class="btn btn-primary me-2">View My Requests</a>
        <a href="#" class="btn btn-primary me-2" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight">PDE List
          Requirements</a>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-12 col-md-12 col-12">
    <div class="card rounded-3">
      <div class="card-header border-bottom-0 p-0">
      </div>
      <div wire:ignore.self class="card-body">
        <h4 class="mb-4">Add Crew Information</h4>
        <hr/>
        <form wire:submit.prevent="" class="row mt-2" >
          @csrf
          <div class="row gx-3">
            <div class="mb-3 col-md-12">
              @if($photoPreview)
              <img src="{{ $photoPreview }}" alt="Preview" width="150">
            @else
              <img src="{{ asset('assets/images/oesximg/no-photo.png') }}" class="img-4by3-lg rounded" alt="avatar">
              @endif
              <input type="file" wire:model="photo">
              @error('photo') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3 col-md-3">
              <label class="form-label" for="Surname"><text style="color:red">*</text>  Surname</label>
              <input type="text" class="form-control" wire:model.lazy="surname" placeholder="Enter Surname" id="surname" >
              @error('surname') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3 col-md-3">
              <label class="form-label" for="Firstname"><text style="color:red">*</text> First Name</label>
              <input type="text" class="form-control" wire:model.lazy="firstname" placeholder="First Name" id="firstname" >
              @error('firstname') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3 col-md-3">
              <label class="form-label" for="Middlename">Middle Name</label>
              <input type="text" class="form-control" wire:model.lazy="middlename" placeholder="Middle Name" id="middlename" >
            </div>
            <div class="mb-3 col-md-3">
              <label class="form-label" for="Suffix">Suffix</label>
              <input type="text" class="form-control" wire:model.lazy="suffix" placeholder="Suffix" id="suffix">
            </div>
            <div class="mb-3 col-md-3">
                <label class="form-label" for="selectedPosition"> <text style="color:red">*</text> Position</label>
                <select class="form-select text-black" data-width="100%" wire:model.lazy="selectedPosition"
                  id="selectedPosition" >
                  <option value="">--Select option--</option>
                  @foreach ($retrieverank as $retrieveranks)
                  <option value="{{ $retrieveranks->rankid }}">{{ $retrieveranks->rank }}</option>
                  @endforeach
                </select>
                @error('selectedPosition') <span class="text-danger">{{ $message }}</span> @enderror
              </div>

            <div class="mb-3 col-md-3">
                <label class="form-label" for="vessels">Vessels</label>
                <input type="text" class="form-control" wire:model.lazy="vessels" placeholder="Enter vessels" id="vessels" >
                @error('vessels') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3 col-md-3">
              <label class  ="form-label" for="dob">Date of Birth</label>
              <input wire:model.lazy="dateOfBirth" type="text" class="form-control flatpickr" placeholder="--Not Set--" id="dateOfBirth" >
                @error('dateOfBirth') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            {{-- <div class="col-md-3" id="divage">
              <label>Age</label>
              <input wire:model="age" type="number" min="18" max="100"
                class="form-control sb-form-control-solid rounded-pill" id="txtaddage" required readonly>
            </div> --}}
            <div class="mb-3 col-md-4">
              <label class="form-label" for="passport">Passport No</label>
              <input type="text" class="form-control" wire:model.lazy="passport" placeholder="Enter passport no." id="passport" >
              @error('passport') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3 col-md-4">
              <label class="form-label" for="passportexpirydate">Passport Expiry Date</label>
              <input type="text" id="passportexpirydate" wire:model.lazy="passportexpirydate" class="form-control flatpickr" placeholder="--Not Set--">
              @error('passportexpirydate') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3 col-md-4">
              <label class="form-label" for="medicalexpirydate">Medical Expiry Date</label>
              <input type="text" id="medicalexpirydate" class="form-control flatpickr" wire:model.lazy="medicalexpirydate" placeholder="--Not Set--">
              @error('medicalexpirydate') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            </div>
            <div class="mb-3">
              <button wire:click="addRow" class="btn btn-primary float-left mr-3">Add</button>
            </div>
        </form>
      </div>
    </div>

    <div class="card mt-2">
      <div class="card-header border-bottom-0">
        <h4 class="mb-1"></h4>
      </div>

      <div class="card-body table-responsive overflow-auto">
        <table class="table table-bordered table-striped table-hover table-sm w-100">
          <thead class="center">
            <tr>
              <th>Action</th>
              <th>Attachments<mark style="background-color:yellow;"> </br>
                  <font class="text-red bold">(Please ZIP your attachments!)</font> </mark></th>
              <th>Image</th>
              <th>Name</th>
              <th>Employment Details</th>
          
            </tr>
          </thead>
          @foreach($rows as $index => $row)
          <tbody>

            <form id="formrequestpde" wire:submit.prevent="formrequestpde" enctype="multipart/form-data">
              @csrf
              <tr>
                    <td> <button wire:click="removeRow({{ $index }})" class="btn btn-danger btn-sm">X Remove</button> </td>
                    <td> <input type="file" required wire:model.defer="rows.{{ $index }}.fileattachment" size="10"
                                wire:change="setRowIndex({{ $index }})" accept=".zip, .rar">
                    </td> 
                    <td> <img src="{{ $rows[$index]['imagepath'] }}" wire:model.defer="rows.{{ $index }}.photoattachment" alt="Preview" class="img-4by3-lg rounded" > </td>
                    <td>
                        <small>
                                <strong class="text-muted fw-bold mt-2">Surname : </strong> <input type="text" wire:model.defer="rows.{{ $index }}.surname" size="10" class="mt-2"> <br />
                                <strong class="text-muted fw-bold mt-2">Firstname: </strong> <input type="text" wire:model.defer="rows.{{ $index }}.firstname" size="10" class="mt-2"> <br />
                                <strong class="text-muted fw-bold mt-2">Middlename: </strong> <input type="text" wire:model.defer="rows.{{ $index }}.middlename" size="10" class="mt-2"> <br />
                                <strong class="text-muted fw-bold mt-2">Suffix: </strong> <input type="text" wire:model.defer="rows.{{ $index }}.suffix" size="10" class="mt-2"> <br />
                        </small>
                    </td>
                    <td>
                        <small> 
                                <strong class="text-muted fw-bold mt-2">Positon  : </strong> <input type="text" wire:model.defer="rows.{{ $index }}.selectedPosition" class="mt-2">  <br />
                                <strong class="text-muted fw-bold mt-2">Vessels : </strong> <input type="text" wire:model.defer="rows.{{ $index }}.vessels" class="mt-2">  <br />
                                <strong class="text-muted fw-bold mt-2">Birthday:  </strong> <input type="text" wire:model.defer="rows.{{ $index }}.dateOfBirth" class="mt-2">  <br />
                                <strong class="text-muted fw-bold mt-2">Age: </strong> <input type="text" wire:model.defer="rows.{{ $index }}.age" class="mt-2"> <br />
                                <strong class="text-muted fw-bold mt-2 ">Passport No: </strong> <input type="text" wire:model.defer="rows.{{ $index }}.passport" class="mt-2 ">  <br />
                                <strong class="text-muted fw-bold mt-2 ">Passport Expiry Date: </strong> <input type="text" wire:model.defer="rows.{{ $index }}.passportexpirydate" class="mt-2"> <br />
                                <strong class="text-muted fw-bold mt-2 ">Medical Expiry Date: </strong> <input type="text" wire:model.defer="rows.{{ $index }}.medicalexpirydate" class="mt-2"> <br />
                        </small>
                    </td> 
                   

                    {{-- <input type="file" required type="file" wire:model.defer="rows.{{ $index }}.fileattachment"
                      accept=".zip,.rar" size="10" wire:change="setRowIndex({{ $index }})"> --}}
              </tr>
            </form>
            @endforeach
          </tbody>
        </table>

      </div>

      <button type="button" id="adddocubtn" class="btn btn-primary btn-block mt-2" data-bs-toggle="modal"
        data-bs-target="#requestpdemodal">
        Apply
      </button>
    </div>
  </div>

</div>

<div wire:ignore.self class="modal fade" id="exampleModal-2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel"></h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <table class="table table-centered table-hover">
                  <thead class="table-light">
                      <tr>
                          <th>No</th> 
                          <th>Requirements</th>
                      </tr>
                  </thead>
                  <tbody>
                   @foreach ($retrievepderequirements as $pderequirements)
                    <tr>
                      <td>{{ $loop->index + 1 }}</td> 
                      <td>{{ $pderequirements->pderequirements }}</td>

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


<!-- Modal -->
<div wire:ignore.self class="modal fade" id="requestpdemodal" tabindex="-1" role="dialog"
  aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Request PDE</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        </button>
      </div>
      <div class="modal-body">
        Are you sure you want to request PDE?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" form="formrequestpde" class="btn btn-primary">Yes</button>
      </div>
    </div>
  </div>
</div>

<!-- Offcanvas -->
<div class="offcanvas offcanvas-end " tabindex="-1" id="offcanvasRight" style="width: 600px;">
  <div class="offcanvas-body bg-white" data-simplebar>
    <div class="offcanvas-header px-2 pt-0">
      <h3 class="offcanvas-title" id="offcanvasExampleLabel">PDE List Requirements</h3>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="container">
      <div class="row">
        <div class="mb-3 col-12">
          <div class="list-group static" style="font-size: 12px;">
            <a href="#" class="list-group-item list-group-item-action active">
              PDE List of Requirements
            </a>
                  @foreach ($retrieverank as $retrieveranks )
                  <a href="" class="list-group-item list-group-item-action" wire:click="pderequirements({{  $retrieveranks->rankid }})" data-bs-toggle="modal"
                    data-bs-target="#exampleModal-2"><i class="bi bi-circle-fill mb-2"></i> {{ $retrieveranks->rank }}</a>
                  @endforeach
          </div>
        </div>

        <div class="col-md-8"></div>

      </div>

    </div>
  </div>
</div>

<script>
  $(document).ready(function(){
        $("#fileattachment").on('input', function() {
            $("#birthday").val('');

        });

        $("#birthday").on('input', function() {
            $("#adddocubtn").removeAttr('disabled');
            $(this).removeClass('is-invalid').addClass('is-valid');
        });

        $("#resetadddocu").click(function(){
            $("#birthday").removeClass().addClass('form-control flatpickr is-invalid');
            $("#adddocubtn").attr('disabled', 'disabled');
        });
    });
</script>


</section>