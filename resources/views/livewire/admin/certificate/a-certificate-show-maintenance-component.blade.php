<section class="container-fluid p-4">
    <div class="row">
        <!-- Page Header -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-flex justify-content-between align-items-center">
                <div class="mb-2 mb-lg-0">
                    <h1 class="mb-1 h2 fw-bold">
                        Certificate Maintenance
                    </h1>
                    <!-- Breadcrumb  -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{route('a.dashboard')}}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item">Maintenance</li>
                            <li class="breadcrumb-item">
                                Certificate Maintenance
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                View
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    @if ($errors->any())
    <div class="col-lg-12 col-md-12 col-12">
        <div wire:ignore class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif


    <div class="row">
        <div class="col-xl-9 col-lg-8 col-md-12 col-12">
            <div class="card border-0 mb-3">
                <div class="card-body">
                    <div class="col-xl-12 col-lg-8 col-md-12 col-12">
                        <form wire:submit.prevent="upload" enctype="multipart/form-data">
                            @csrf
                            <div class="mt-1">
                                <p class="mb-1 text-dark">Upload the template here: <small style="color: red;"><i>(It only accept pdf.)</i></small></p>
                                <div class="input-group mb-1">
                                    <input type="file" class="form-control" wire:model="file" wire:loading.attr="disabled" accept="application/pdf" wire:target="file">
                                    <button class="input-group-text" type="submit">Upload</button>
                                </div>
                                <small><i>Certificate Path: {{$course->certificatepath}}</i></small>
                                @error('file') <small class="text-muted">{{$message}} </small>@enderror
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Card -->
            <div class="card border-0 mb-4">
                <!-- Card header -->
                <div class="card-header">
                    <h4 class="mb-0">Edit Certificate ({{$coursename}})</h4>
                </div>

                @if ($course->courseid == 66 || $course->courseid == 113)
                <div class="row  d-flex justify-content-center text-center">
                    <div class="col-xl-5 col-lg-5 col-md-12 col-12">
                        <div class="py-6">
                            <img src="{{asset('assets/images/svg/path-img.svg')}}" alt="path" class="img-fluid">
                            <div class="mt-4 ">
                                <h2 class="display-4 fw-bold">Contact the Developer</h2>
                                <p class="mb-5">If you want to adjust the design of PDOS template, call administrator.
                                    we release it soon for you.</p>
                                <a href="{{route('a.certmain')}}" class="btn btn-primary">
                                    Back To Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                @else
                <form wire:submit.prevent="save" enctype="multipart/form-data">
                    @csrf
                    <!-- Card body -->
                    <div class="card-body">
                        <div class="">
                            <!-- Form -->
                            <div class="row">
                                <div class="mb-3 col-md-12">
                                    <label for="selectDate" class="form-label">Last Certificate Number</label>
                                    <input type="text" class="form-control text-dark" wire:model="last_num" placeholder="Last Certificate number..">
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="selectDate" class="form-label">Cert/Reg # Font</label>
                                    <select class="form-select" wire:model="certF" data-width="100%">
                                        <option value="1">Arial</option>
                                        <option value="2">Arial Black</option>
                                        <option value="3">Times New Roman</option>
                                    </select>
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="selectDate" class="form-label">Cert/Reg # Font Size</label>
                                    <input type="number" class="form-control text-dark" wire:model="certFSize" placeholder="">
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="selectDate" class="form-label">Cert/Reg # Font Style</label>
                                    <select class="form-select" wire:model="certFStyle" data-width="100%">
                                        <option value="5">None</option>
                                        <option value="1">Bold</option>
                                        <option value="2">Italic</option>
                                        <option value="4">Underline</option>
                                    </select>
                                </div>

                                <div class="mb-3 col-md-4">
                                    <label for="selectDate" class="form-label">Crew name Font</label>
                                    <select class="form-select" data-width="100%" wire:model="crewF">
                                        <option value="1">Arial</option>
                                        <option value="2">Arial Black</option>
                                        <option value="3">Times New Roman</option>
                                    </select>
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="selectDate" class="form-label">Crew name Font Size</label>
                                    <input type="number" class="form-control text-dark" placeholder="" wire:model="crewFSize">
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="selectDate" class="form-label">Crew name Font Style</label>
                                    <select class="form-select" data-width="100%" wire:model="crewFStyle">
                                        <option value="5">None</option>
                                        <option value="1">Bold</option>
                                        <option value="2">Italic</option>
                                        <option value="4">Underline</option>
                                    </select>
                                </div>

                                <div class="mb-3 col-md-4">
                                    <label for="selectDate" class="form-label">Birthday Font</label>
                                    <select class="form-select" data-width="100%" wire:model="bdayF">
                                        <option value="1">Arial</option>
                                        <option value="2">Arial Black</option>
                                        <option value="3">Times New Roman</option>
                                    </select>
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="selectDate" class="form-label">Birthday Font Size</label>
                                    <input type="number" class="form-control text-dark" placeholder="" wire:model="bdayFSize">
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="selectDate" class="form-label">Birthday Font Style</label>
                                    <select class="form-select" data-width="100%" wire:model="bdayFStyle">
                                        <option value="5">None</option>
                                        <option value="1">Bold</option>
                                        <option value="2">Italic</option>
                                        <option value="4">Underline</option>
                                    </select>
                                </div>

                                <div class="mb-3 col-md-4">
                                    <label for="selectDate" class="form-label">Remarks Font</label>
                                    <select class="form-select" data-width="100%" wire:model="remarksF">
                                        <option value="1">Arial</option>
                                        <option value="2">Arial Black</option>
                                        <option value="3">Times New Roman</option>
                                    </select>
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="selectDate" class="form-label">Remarks Font Size</label>
                                    <input type="number" class="form-control text-dark" placeholder="" wire:model="remarksFSize">
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="selectDate" class="form-label">Remarks Font Style</label>
                                    <select class="form-select" data-width="100%" wire:model="remarksFStyle">
                                        <option value="5">None</option>
                                        <option value="1">Bold</option>
                                        <option value="2">Italic</option>
                                        <option value="4">Underline</option>
                                    </select>
                                </div>

                                <!-- Editor -->
                                <div class="mt-2 mb-4">
                                    <div wire:ignore>
                                        <label for="" class="form-label">Certificate Remarks <small class="text-danger"><i>(add "trainingdate" to get the value of training date of schedule)</i></small></label>
                                        <textarea id="description" wire:model.defer="remarks_desc"></textarea>
                                    </div>
                                </div>

                                <h3>ALIGNMENT</h3>
                                <hr>

                                <div class="mb-3 col-md-6">
                                    <label for="selectDate" class="form-label">Certification Number (X,Y)</label>
                                    <input type="text" class="form-control text-dark" placeholder="" wire:model="cert_a">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="selectDate" class="form-label">Registration Number (X,Y)</label>
                                    <input type="text" class="form-control text-dark" placeholder="" wire:model="reg_a">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="selectDate" class="form-label">Crew Name (X,Y)</label>
                                    <input type="text" class="form-control text-dark" placeholder="" wire:model="crew_a">
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="selectDate" class="form-label">Bithday (X,Y)</label>
                                    <input type="text" class="form-control text-dark" placeholder="" wire:model="bday_a">
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="selectDate" class="form-label">QR Code (X,Y)</label>
                                    <input type="text" class="form-control text-dark" placeholder="" wire:model="qr_a">
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="selectDate" class="form-label">Picture (X,Y)</label>
                                    <input type="text" class="form-control text-dark" placeholder="" wire:model="pic_a">
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="selectDate" class="form-label">Remarks (X,Y)</label>
                                    <input type="text" class="form-control text-dark" placeholder="" wire:model="remarks_a">
                                </div>

                                <h3>E-SIGNATURE ALIGNMENT</h3>
                                <hr>
                                <div class="mb-3 col-md-6">
                                    <label for="selectDate" class="form-label">COC gm e-sign (X)</label>
                                    <input type="text" class="form-control text-dark" placeholder="" wire:model="coc_x">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="selectDate" class="form-label">COC gm e-sign (Y)</label>
                                    <input type="text" class="form-control text-dark" placeholder="" wire:model="coc_y">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="selectDate" class="form-label">COA gm e-sign (X)</label>
                                    <input type="text" class="form-control text-dark" placeholder="" wire:model="coa_x">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="selectDate" class="form-label">COA gm e-sign (Y)</label>
                                    <input type="text" class="form-control text-dark" placeholder="" wire:model="coa_y">
                                </div>
                            </div>
                        </div>
                        <!-- button -->
                        <button type="submit" class="btn btn-primary"> Save changes </button>
                    </div>
                </form>
                @endif
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-12 col-12">
            <!-- Card -->
            <div class="card mt-4 mt-lg-0 mb-4">
                <!-- Card Header -->
                <div class="card-header d-lg-flex">
                    <h4 class="mb-0">Course Info</h4>
                </div>
                <!-- List Group -->
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <span class="text-body">Course ID</span>
                        <h5>{{$course->courseid}}</h5>
                    </li>
                    <li class="list-group-item">
                        <span class="text-body">Status</span>
                        <h5>
                            <span class="badge-dot bg-success d-inline-block me-1"></span>Published
                        </h5>
                    </li>
                    <li class="list-group-item">
                        <span class="text-body">Created at</span>
                        @if ($course->created_at)
                        <h5>{{$course->created_at}}</h5>
                        @else
                        <h5>Prev. data in OES</h5>
                        @endif
                    </li>
                    <li class="list-group-item">
                        <span class="text-body">Last update</span>
                        @if ($course->updated_at)
                        <h5>{{date('d F, Y', strtotime($course->updated_at))}}</h5>
                        @else
                        <h5>Prev. data in OES</h5>
                        @endif
                    </li>
                </ul>
                <!-- Card -->
            </div>
            <div class="card mb-4">
                <!-- Card Header -->
                <div class="card-header d-lg-flex">
                    <h4 class="mb-0">Actions</h4>
                </div>
                <!-- List group -->
                <ul class="list-group list-group-flush">

                    @if ($course->courseid == 66 || $course->courseid == 113)
                    <a href="{{route('a.mpdoscertificates', ['course_id' => $course->courseid])}}" target="_blank">
                        <li class="list-group-item d-flex justify-content-between align-items-center btn btn-secondary">
                            <span class="">Preview Certicate</span>
                            <i class="fe fe-eye fs-4"></i>
                        </li>
                    </a>
                    @else
                    <a href="{{route('a.mcertificates', ['course_id' => $course->courseid])}}" target="_blank">
                        <li class="list-group-item d-flex justify-content-between align-items-center btn btn-secondary">
                            <span class="">Preview Certicate</span>
                            <i class="fe fe-eye fs-4"></i>
                        </li>
                    </a>
                    @endif
                    <li class="list-group-item d-flex justify-content-between align-items-center btn btn-secondary">
                        <span class="">Delete</span>
                        <a href="#"><i class="fe fe-trash text-danger fs-4"></i></a>
                    </li>
                </ul>
            </div>
            <!-- Card  -->
            <div class="card">
                <!-- Card header -->
                <div class="card-header d-lg-flex">
                    <h4 class="mb-0">Revision History</h4>
                </div>
                <!-- List group -->
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">{{date('d F, Y', strtotime($course->updated_at))}}</h5>
                            <span class="text-body">{{$course->course_name}}</span>
                        </div>
                        <div>
                            <span class="badge bg-success badge-pill">Published</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
$(document).ready(function(){
    $('#description').summernote({
            height: 300, // Set your preferred height
            callbacks: {
                // Update Livewire property when Summernote content changes
                onChange: function(contents) {
                    @this.set('remarks_desc', contents);
                }
            }
        });
});
</script>
@endpush