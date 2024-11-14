<section class="container-fluid p-6">
    <div class="row">
        <!-- Page Header -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-flex justify-content-between align-items-center">
                <div class="mb-2 mb-lg-0">
                    <h1 class="mb-1 h2 fw-bold">
                        Report Dashboard
                        <span class="fs-5 text-muted"></span>
                    </h1>
                    <!-- Breadcrumb  -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="../admin/dashboard">Administrator</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Report
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="row gx-3">
        <div class="col-xl-6 col-md-6 col-6 mb-3 ">
            <!-- card card-borderd  -->
            <a href="{{ route('a.report-batch') }}" class="card h-100 card-hover border"
                style="background-image: url('{{ asset('assets/images/oesximg/widget.jpg') }}');
    background-size: cover;
    background-position: center;">
                <!-- card body  -->
                <div class="card-body">
                    <i class="bi bi-calendar-range-fill fs-1 mb-3 text-white"></i>
                    <br>
                    <br>
                    <small class="text-white">GENERATE</small>
                    <h3 class="text-white">TRAINING REPORT</h3>
                    <p class="mb-0 text-white">Generate a training schedule batch report and display the list of
                        trainees for each scheduled session.</p>
                </div>

            </a>
        </div>
        <div class="col-xl-6 col-md-6 col-6 mb-3 ">
            <!-- card card-borderd  -->
            <a href="{{ route('a.cert-history') }}" class="card h-100 card-hover border"
                style="background-image: url('{{ asset('assets/images/oesximg/widget.jpg') }}');
    background-size: cover;
    background-position: center;">
                <!-- card body  -->
                <div class="card-body">
                    <i class="bi bi-file-earmark-check-fill fs-1 mb-3 text-white"></i>
                    <br>
                    <br>
                    <small class="text-white">GENERATE</small>
                    <h3 class="text-white">CERTIFICATE HISTORY</h3>
                    <p class="mb-0 text-white">See all certicates of trainees based on training.</p>
                </div>
            </a>
        </div>
        <div class="col-xl-6 col-md-6 col-6 mb-3 ">
            <!-- card card-borderd  -->
            <a href="{{ route('a.view-trainee-batch') }}" class="card h-100 card-hover border"
                style="background-image: url('{{ asset('assets/images/oesximg/widget.jpg') }}');
    background-size: cover;
    background-position: center;">
                <!-- card body  -->
                <div class="card-body">
                    <i class="bi bi-file-earmark-ruled fs-1 mb-3 text-white"></i>
                    <br>
                    <br>
                    <small class="text-white">GENERATE</small>
                    <h3 class="text-white">TRAINEE BATCH REPORT</h3>
                    <p class="mb-0 text-white">Generate a detailed trainee
                        batch report to view individual trainee details.</p>
                </div>

            </a>
        </div>

        <div class="col-xl-6 col-md-6 col-6 mb-3 ">
            <!-- card card-borderd  -->
            <a href="{{ route('a.view-training-schedule') }}" class="card h-100 card-hover border"
                style="background-image: url('{{ asset('assets/images/oesximg/widget.jpg') }}');
    background-size: cover;
    background-position: center;">
                <!-- card body  -->
                <div class="card-body">
                    <i class="bi bi-calendar2-week-fill fs-1 mb-3 text-white"></i>
                    <br>
                    <br>
                    <small class="text-white">GENERATE</small>
                    <h3 class="text-white">WEEKLY TRAINING CALENDAR</h3>
                    <p class="mb-0 text-white">Generate a weekly training schedule report, displaying all conducted
                        training sessions for each week.
                    </p>
                </div>
            </a>
        </div>

        <div class="col-xl-6 col-md-6 col-6 mb-3 ">
            <!-- card card-borderd  -->
            <a href="{{ route('a.pending-trainee') }}" class="card h-100 card-hover border"
                style="background-image: url('{{ asset('assets/images/oesximg/widget.jpg') }}');
    background-size: cover;
    background-position: center;">
                <!-- card body  -->
                <div class="card-body">
                    <i class="bi bi-calendar2-week-fill fs-1 mb-3 text-white"></i>
                    <br>
                    <br>
                    <small class="text-white">GENERATE</small>
                    <h3 class="text-white">PENDING TRAINEE</h3>
                    <p class="mb-0 text-white">
                        Generate a weekly report listing all pending enrollments for trainees.
                    </p>
                </div>
            </a>
        </div>
        <div class="col-xl-6 col-md-6 col-6 mb-3 ">
            <!-- card card-borderd  -->
            <a href="{{ route('a.avail-course') }}" class="card h-100 card-hover border"
                style="background-image: url('{{ asset('assets/images/oesximg/widget.jpg') }}');
    background-size: cover;
    background-position: center;">
                <!-- card body  -->
                <div class="card-body">
                    <i class="bi bi-calendar2-week-fill fs-1 mb-3 text-white"></i>
                    <br>
                    <br>
                    <small class="text-white">GENERATE</small>
                    <h3 class="text-white">AVAILABLE COURSE</h3>
                    <p class="mb-0 text-white">
                        Generate a weekly available course.
                    </p>
                </div>
            </a>
        </div>
        <div class="col-xl-6 col-md-6 col-6 mb-3 ">
            <!-- card card-borderd  -->
            <a href="{{ route('a.view-list-enrolees') }}" class="card h-100 card-hover border"
                style="background-image: url('{{ asset('assets/images/oesximg/widget.jpg') }}');
    background-size: cover;
    background-position: center;">
                <!-- card body  -->
                <div class="card-body">
                    <i class="bi bi-calendar2-week-fill fs-1 mb-3 text-white"></i>
                    <br>
                    <br>
                    <small class="text-white">GENERATE</small>
                    <h3 class="text-white">WEEKLY LIST OF ENROLEES</h3>
                    <p class="mb-0 text-white">
                        Generate a weekly list of enrolees.
                    </p>
                </div>
            </a>
        </div>
        <div class="col-xl-6 col-md-6 col-6 mb-3 ">
            <!-- card card-borderd  -->
            <a href="{{ route('a.enrollment-stats') }}" class="card h-100 card-hover border"
                style="background-image: url('{{ asset('assets/images/oesximg/widget.jpg') }}');
    background-size: cover;
    background-position: center;">
                <!-- card body  -->
                <div class="card-body">
                    <i class="bi bi-calendar2-week-fill fs-1 mb-3 text-white"></i>
                    <br>
                    <br>
                    <small class="text-white">GENERATE</small>
                    <h3 class="text-white">ENROLLMENT STATISTICS</h3>
                    <p class="mb-0 text-white">
                        Generate a monthly / yearly trainee statistics.
                    </p>
                </div>
            </a>
        </div>
        <div class="col-xl-6 col-md-6 col-6 mb-3 ">
            <!-- card card-borderd  -->
            <a wire:click="navigate()"  class="card h-100 card-hover border"
                style="background-image: url('{{ asset('assets/images/oesximg/widget.jpg') }}');
    background-size: cover;
    background-position: center;">
                <!-- card body  -->
                <div class="card-body">
                    <i class="bi bi-calendar2-week-fill fs-1 mb-3 text-white"></i>
                    <br>
                    <br>
                    <small class="text-white">EXPORT</small>
                    <h3 class="text-white">TRAINEE's Wi-Fi</h3>
                    <p class="mb-0 text-white">
                        Export trainee's Wi-Fi password here.
                    </p>
                </div>
            </a >
        </div>
    </div>
</section>
