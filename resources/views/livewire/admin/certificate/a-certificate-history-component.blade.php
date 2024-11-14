<section class="container-fluid p-4">
    <span wire:loading>
        <livewire:components.loading-screen-component />
    </span>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3">
                <div class="mb-3 mb-lg-0">
                    <h1 class="mb-0 h2 fw-bold">Certificate History</h1>
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{route('a.dashboard')}}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{route('a.report-dashboard')}}">Report</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Certificate History
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-12">
            <!-- Card -->
            <div class="card">
                <!-- Card body -->
                <div class="row g-0">
                    <div class="col-xxl-2 col-xl-3 border-end">
                        <nav class="navbar navbar-expand p-4 navbar-mail">
                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul class="navbar-nav flex-column w-100">
                                    <li class="d-grid mb-4">
                                        <!-- <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#composeMailModal">
                                            Compose New Email
                                        </a> -->
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link active" aria-current="page" href="{{route('a.cert-history')}}">
                                            <span class="d-flex align-items-center justify-content-between">
                                                <span class="d-flex align-items-center"><i class="fe fe-inbox me-2"></i>All certificates
                                                </span>
                                                <span class="badge bg-info">{{$count_cert}}</span>
                                            </span>
                                        </a>
                                    </li>
                                    <!-- <li class="nav-item">
                                        <a class="nav-link" href="#">
                                            <span class="d-flex align-items-center justify-content-between">
                                                <span class="d-flex align-items-center"><i class="fe fe-send me-2"></i>Sent
                                                </span>
                                                <span>5</span>
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="mail-draft.html">
                                            <span class="d-flex align-items-center"><i class="fe fe-mail me-2"></i>Drafts
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">
                                            <span class="d-flex align-items-center justify-content-between">
                                                <span class="d-flex align-items-center"><i class="fe fe-alert-circle me-2"></i>Spam
                                                </span>
                                                <span>1</span>
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">
                                            <span class="d-flex align-items-center"><i class="fe fe-trash-2 me-2"></i>Trash
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">
                                            <span class="d-flex align-items-center"><i class="fe fe-archive me-2"></i>Archive
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">
                                            <span class="d-flex align-items-center"><i class="fe fe-star me-2"></i>Starred
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">
                                            <span class="d-flex align-items-center"><i class="mdi mdi-label-variant me-2"></i>Important</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">
                                            <span class="d-flex align-items-center"><i class="mdi mdi-label me-2 text-success"></i>Personal</span>
                                        </a>
                                    </li> -->
                                </ul>
                            </div>
                        </nav>
                    </div>
                    <div class="col-xxl-10 col-xl-9 col-12">
                        <div class="card-header p-4">
                            <div class="d-md-flex justify-content-between align-items-center">
                                <div class="d-flex flex-wrap gap-2 mb-2 mb-md-0">
                                    <button class="btn btn-outline-secondary btn-icon" id="excel" type="button" data-bs-toggle="modal" data-bs-target="#ExportCertificateModal">
                                        <i class="bi bi-explicit-fill"></i> </i>
                                    </button>
                                </div>
                                <div>
                                    <form>
                                        @csrf
                                        <input type="search" class="form-control" wire:model.debounce.3000ms="search" placeholder="Search name..">
                                        <select class="form-select mt-3" wire:model="selected_course">
                                            <option value="">Select a course</option>
                                            @foreach ($courses as $course)
                                            <option value="{{$course->courseid}}">{{$course->coursecode}} - {{$course->coursename}}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div>
                            <!-- list group -->
                            <div class="list-group list-group-flush list-group-mail">
                                <!-- list group item -->
                                @foreach ($certificates as $certificate)
                                <div class="list-group-item list-group-item-action px-4 list-mail bg-light">
                                    <div class="d-flex align-items-center">
                                        <div class="d-flex align-items-center">
                                            <!-- title -->
                                            <div class="list-title">
                                                <span class="fw-bold">{{$certificate->course->coursecode}} - {{$certificate->course->coursename}} </span>
                                            </div>
                                            <!-- text -->
                                            <div class="me-6 w-xxl-100 w-lg-50 w-md-50 w-5">
                                                <button type="button" class="btn btn-link" wire:click="redirectToCertHistoryDetails({{$certificate->certificatehistoryid}})">
                                                    <p class="mb-0 list-text">
                                                        This certificate are printed {{date("F j, Y", strtotime($certificate->dateprinted))}} | <span class="text-uppercase"> <i>{{$certificate->trainee->formal_name()}}</i> </span>
                                                    </p>
                                                </button>
                                            </div>

                                            <!-- time -->
                                            <div class="list-time">
                                                <p class="mb-0">{{date("h:i A", strtotime(optional($certificate)->created_at))}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex align-items-center justify-content-between">
                                {{ $certificates->links('livewire.components.customized-pagination-link')}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="ExportCertificateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel" wire:model.defer="title">Export Certificate History</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="exportExcel" id="setPassed">
                        @csrf
                        <div class="row gx-3">
                            <div class="mb-3">
                                <label for="year" class="form-label"> Please select course type</label>
                                <select class="form-select" wire:model.defer="course_type_id">
                                    <option value="">Select a course</option>
                                    @foreach($course_type as $course)
                                    <option value="{{$course->coursetypeid}}">{{$course->coursetype}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="year" class="form-label"> Please select year to export <i> (.xlsx)</i></label>
                                <select class="form-select" wire:model.defer="year">
                                    <option value="">Select year</option>
                                    <option value="2024">2024</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="setPassed" class="btn btn-primary">Proceed</button>
                </div>
            </div>
        </div>
    </div>
</section>