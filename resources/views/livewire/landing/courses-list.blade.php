<main>
    <!-- Page header -->
    <section class="pt-lg-8 pb-lg-16 pt-7 pb-12 bg-primary"
        style="background-image: url('{{asset('assets/images/oesximg/sample-bg.png')}}'); background-size: cover; background-position: center; background-color: rgba(0, 0, 0, 0.5);">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-7 col-lg-7 col-md-12">
                    <div>
                        <h1 class="text-white display-4 fw-semibold">
                            {{ $coursestype->coursetypeid == 12 ? "" : "Training" }} Schedule for
                            {{$coursestype->coursetype}}
                        </h1>
                        <p class="text-white mb-6 lead">
                            {{$coursestype->description}}
                        </p>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Page content -->
    <section class="pb-10">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-md-12 col-12 mt-n15 mb-4 mb-lg-0">
                    <!-- Card -->
                    <div class="card rounded-3">
                        <!-- Card header -->
                        <div class="card-header border-bottom-0 p-0">
                            <div>
                                <!-- Nav -->
                                <ul class="nav nav-lb-tab" id="tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="table-tab" data-bs-toggle="pill" href="#table"
                                            role="tab" aria-controls="table" aria-selected="true">Lists</a>
                                    </li>

                                </ul>
                            </div>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="tab-content" id="tabContent">
                                <div class="tab-pane fade show active" id="table" role="tabpanel"
                                    aria-labelledby="table-tab">
                                    <!-- Card -->
                                    <div class="accordion" id="courseAccordion">
                                        <div>
                                            <!-- List group -->
                                            <ul class="list-group list-group-flush">
                                                @foreach ($tblcourses as $course)
                                                <!-- List group item -->
                                                <li class="list-group-item px-0">
                                                    <!-- Toggle -->

                                                    <a class="h4 mb-0 d-flex align-items-center text-inherit text-decoration-none"
                                                        data-bs-toggle="collapse" href="#{{$course->courseid}}"
                                                        aria-expanded="false" aria-controls="{{$course->courseid}}">
                                                        <div class="me-auto">
                                                            <!-- Title -->
                                                            <span class="badge bg-primary me-2">{{ $loop->index +
                                                                1}}</span> {{$course->coursetypeid == 12 ? '' :
                                                            $course->coursecode.' - '}}
                                                            {{$course->coursename}}
                                                        </div>
                                                        <!-- Chevron -->
                                                        <span class="chevron-arrow ms-4">
                                                            <i class="fe fe-chevron-down fs-4"></i>
                                                        </span>
                                                    </a>

                                                    <!-- Row -->
                                                    <!-- Collapse -->
                                                    <div class="collapse" id="{{$course->courseid}}"
                                                        data-bs-parent="#courseAccordion">
                                                        <div class="pt-1 mt-0 pb-2">
                                                            @if ($course->traininingdescription)
                                                            <span class="ms-2" style="color: black;">{!!
                                                                $course->traininingdescription !!}</span>
                                                            <hr>
                                                            @endif
                                                            {{-- <h6>1232wdiohanlsjk.fewhedjb.n</h6> --}}
                                                            <!-- Foreach start -->
                                                            @if ($course->schedules->count() > 0)
                                                            @foreach ($course->schedules as $schedule)

                                                            <div
                                                                class="d-flex flex-row justify-content-between mb-2 px-sm-5 mt-2">
                                                                <a href="{{ route('registration') }}"
                                                                    class="text-inherit text-decoration-none">
                                                                    <div
                                                                        class="d-flex flex-row justify-content-between text-center align-items-center gap-sm-3">
                                                                        <span>
                                                                            {{
                                                                            \Carbon\Carbon::parse($schedule->startdateformat)->format('F
                                                                            j, Y') }}
                                                                            to {{
                                                                            \Carbon\Carbon::parse($schedule->enddateformat)->format('F
                                                                            j, Y') }}
                                                                        </span>

                                                                    </div>
                                                                    @if ($schedule->course->type->coursetypeid != 7)
                                                                    <span>
                                                                        <a href="{{ route('registration') }}"
                                                                            class="btn btn-sm btn-outline-primary">Click
                                                                            here to enroll</a>
                                                                    </span>
                                                                    @endif
                                                                </a>
                                                            </div>





                                                            @endforeach

                                                            @else
                                                            <div
                                                                class="mb-2 d-flex justify-content-between align-items-center text-inherit text-decoration-none">
                                                                <div class="text-truncate">
                                                                    <span
                                                                        class="icon-shape bg-light icon-sm rounded-circle me-2 text-danger">
                                                                        <i class="mdi mdi-checkbox-blank-circle"></i>
                                                                    </span>
                                                                    @if ($coursestype->coursetypeid == 12)
                                                                    <span class="text-danger">Pls. contact the Registrar
                                                                        for schedules (09989668854).</span>
                                                                    @else
                                                                    <span class="text-danger">-----No training schedules
                                                                        are available-----</span>
                                                                    @endif
                                                                </div>
                                                                <div class="text-truncate">
                                                                    <span></span>
                                                                </div>
                                                            </div>
                                                            @endif
                                                            <!-- Foreach end -->
                                                        </div>

                                                    </div>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-12 col-12 mt-lg-n22">
                    <!-- Card -->
                    <div class="card mb-3 mb-4">
                        <div class="p-1">
                            <!-- Crossfade -->
                            <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <img src="{{asset('assets/images/oesximg/courselandingpage/bridge-3.png')}}"
                                            class="d-block w-100 " alt="...">
                                    </div>
                                    <div class="carousel-item">
                                        <img src="{{asset('assets/images/oesximg/courselandingpage/landing-4-bg.JPG')}}"
                                            class="d-block w-100 " alt="...">
                                    </div>
                                    <div class="carousel-item">
                                        <img src="{{asset('assets/images/oesximg/courselandingpage/landing-1-bg.png')}}"
                                            class="d-block w-100 " alt="...">
                                    </div>
                                </div>
                                <a class="carousel-control-prev" href="#carouselExampleFade" role="button"
                                    data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#carouselExampleFade" role="button"
                                    data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </a>
                            </div>

                        </div>
                        <!-- Card body -->
                        {{-- <div class="card-body">
                            <!-- Price single page -->
                            <div class="mb-3">
                                <span class="text-dark fw-bold h2">Downloads</span>

                            </div>
                            <div class="d-grid">
                                <a href="{{ asset($coursestype->brochure) }}" class="btn btn-outline-primary"
                                    download="downloaded-image.jpg">Click to Download</a>
                            </div>
                        </div> --}}
                    </div>
                    <!-- Card -->
                    <div class="card mb-4">
                        <div>
                            <!-- Card header -->
                            <div class="card-header">
                                <h4 class="mb-0">Courses</h4>
                            </div>
                            <ul class="list-group list-group-flush">

                                @foreach ($Coursetype1 as $Coursetypes)


                                <li class="list-group-item bg-transparent">
                                    <i class="fe fe-calendar align-middle me-2 text-info"></i><a
                                        href="{{ route('courseslist', ['hashid' => $Coursetypes->hash_id]) }}">
                                        {{ $Coursetypes->coursetype }} </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</main>