<section class="container p-4">
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
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                    <div class="col-xxl-10 col-xl-9 col-12">
                        <div>
                            <!-- card header -->
                            <div class="card-header">
                                <div class="d-md-flex justify-content-between
                                            align-items-center">
                                    <div class="d-flex mb-3 mb-md-0">
                                        <div>
                                            <a href="{{route('t.certificates')}}" class="btn btn-outline-secondary btn-sm fs-5" data-bs-toggle="tooltip" data-bs-placement="top" title="Back to inbox"><i class=" fe fe-arrow-left "></i></a>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <span># <b><i>{{$certificate->certificatehistoryid}}</i></b> </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- card body -->
                            <div class="card-body">
                                <div class="d-xl-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center mb-3 mb-xl-0">
                                        <!-- img -->
                                        <div>
                                            <img src="{{asset('assets/images/avatar/avatar.jpg')}}"class="rounded-circle avatar-md" alt="avatar">
                                        </div>
                                        <!-- sidebar -->
                                        <div class="ms-3 lh-1">
                                            <h5 class="mb-1"> <span class="text-uppercase"> <i>{{$certificate->trainee->formal_name()}}</i> </span> | This certificate are printed {{date("F j, Y", strtotime($certificate->dateprinted))}}</h5>
                                            <p class="mb-0 fs-6">{{$trainee->email}}</p>
                                        </div>

                                    </div>
                                    <!-- text -->
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <small class="text-muted">{{ date("F j, Y, g:i A", strtotime($certificate->dateprinted)) }}</small>
                                        </div>
                                    </div>
                                </div>
                                <!-- text -->
                                <div class="mt-6">
                                    <h3 class="mb-3 text-dark">
                                        Training information:
                                    </h3>
                                    <p><b>Course Training: </b> {{$certificate->course->coursecode}} - {{$certificate->course->coursename}}</p>
                                    <p><b>Training Schedule: </b> {{date("F j, Y", strtotime($enroled->schedule->startdateformat))}} - {{date("F j, Y", strtotime($enroled->schedule->enddateformat))}}</p>

                                    <div class="mt-6">
                                        <p class="mb-0">Issued by:</p>
                                        <p class="text-dark font-weight-bold mb-0">{{$certificate->issued_by}}</p>
                                    </div>
                                    <div class="border-top py-4 mt-6">
                                        <p><i class="mdi mdi-attachment me-2 align-middle"></i>1
                                            Attachment</p>
                                        <div class="d-flex">
                                            <a href="{{route('t.solocertificates')}}" target="_blank">
                                            <div class="d-flex align-items-center">
                                                <div class="icon-shape icon-md bg-danger text-white
                                                    rounded">
                                                    <small>PDF</small>
                                                </div>
                                                <div class="ms-2">
                                                    <p class="mb-0 fs-6">{{$enroled->enroledid}}.pdf</p>
                                                </div>
                                            </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>