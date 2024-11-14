<div>
    <section class="pt-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                    <!-- Bg -->
                    <div class=" pt-16 rounded-top-md " style="
                    background: url(../assets/images/background/profile-bg.jpg) no-repeat;
                    background-size: cover;">
                    </div>
                    <div class="card rounded-0 rounded-bottom  px-4  pt-2 pb-4 ">
                        <div class="d-flex align-items-end justify-content-between  ">
                            <div class="d-flex align-items-center">
                                <div class="me-2 position-relative d-flex justify-content-end align-items-end mt-n5">
                                    @if (Auth::guard('trainee')->user()->imagepath)
                                    <img src="{{asset('storage/traineepic/'. Auth::guard('trainee')->user()->imagepath)}}" class="avatar-xl rounded-circle border border-4 border-white" alt="avatar">
                                    @else
                                    <img src="{{ asset('assets/images/avatar/avatar.jpg') }}" class="avatar-xl rounded-circle border border-4 border-white" alt="avatar">
                                    @endif
                                </div>
                                <div class="lh-1">
                                    <h2 class="mb-0">{{ Auth::guard('trainee')->user()->formal_name() }}
                                    </h2>
                                    <p class=" mb-0 d-block"><i>RANK: {{ Auth::guard('trainee')->user()->rank->rank }} -
                                            {{ Auth::guard('trainee')->user()->rank->rankacronym }}</i> </p>
                                </div>
                            </div>
                            <div>
                                <a href="{{ route('t.editprofile') }}" class="btn btn-primary btn-sm d-none d-md-block">Account
                                    Setting</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Content -->

    <section class="pb-5 py-md-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <!-- Side Navbar -->
                    <ul class="nav nav-lb-tab mb-6" id="tab" role="tablist">
                        @if (Auth::guard('trainee')->user()->company_id == 1)
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="nmc-tab" data-bs-toggle="pill" href="#nmc" role="tab" aria-controls="bookmarked" aria-selected="true">NMC</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link " id="nmcr-tab" data-bs-toggle="pill" href="#nmcr" role="tab" aria-controls="nmcr" aria-selected="true">NMCR</a>
                        </li>
                        @endif
                        <li class="nav-item" role="presentation">
                            <a class="nav-link  @if (Auth::guard('trainee')->user()->company_id != 1) active @endif" id="mandatory-tab" data-bs-toggle="pill" href="#mandatory" role="tab" aria-controls="mandatory" aria-selected="true">Mandatory</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link " id="upgrading-tab" data-bs-toggle="pill" href="#upgrading" role="tab" aria-controls="upgrading" aria-selected="true">Upgrading</a>
                        </li>
                        @if (Auth::guard('trainee')->user()->company_id == 1)
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="gov-tab" data-bs-toggle="pill" href="#gov" role="tab" aria-controls="gov" aria-selected="false">
                                Other Goverment Required Courses</a>
                        </li>
                        @endif

                        {{-- <li class="nav-item" role="presentation">
                            <a class="nav-link" id="jiss-tab" data-bs-toggle="pill" href="#jiss" role="tab"
                                aria-controls="jiss" aria-selected="false">
                                JISS</a>
                        </li> --}}
                    </ul>
                    <!-- Tab content -->
                    <div class="tab-content" id="tabContent">
                        <!-- nmc -->
                        @if (Auth::guard('trainee')->user()->company_id == 1)
                        <div class="tab-pane active" id="nmc" role="tabpanel" aria-labelledby="nmc-tab">
                            <div class="row">
                                @if ($courses_nmc->count())
                                @foreach ($courses_nmc as $course)
                                <div class="col-lg-3 col-md-6 col-12 mb-3">
                                    <!-- Card -->
                                    <div class="card card-hover h-100">
                                        <div href="#" class="card-img-top"><img src="{{ asset('assets/images/oesximg/nmc-course-card.jpg') }}" alt="course" class="card-img-top rounded-top-md"></div>
                                        <!-- Card body -->
                                        <div class="card-body">
                                            <h3 class="h4 mb-2 text-truncate-line-2 ">
                                                {{ $course->coursecode }} - <i>{{ $course->coursename }}
                                                </i>
                                            </h3>

                                            <hr>
                                            <!-- List inline -->
                                            <div class="mb-3">
                                                {{-- <p><i class="mdi mdi-account-supervisor-circle text-muted"></i>
                                                    Mode of Delivery:
                                                    {{ $course->mode->modeofdelivery }}</li>
                                                </p> --}}
                                                <p><i class="mdi mdi-map-marker-radius"></i> Location of
                                                    training: {{ $course->location->courselocation }}</p>
                                            </div>

                                            <small>Available for: <span class="badge bg-success">{{
                                                    $course->rank_level->ranklevel }}</span>
                                                <span class="badge bg-info">{{ $course->course_depart->rankdepartment }}
                                                </span></small>
                                        </div>
                                        <!-- Card footer -->
                                        <div class="card-footer">
                                            <div class="row align-items-center g-0">
                                                <a href="{{ route('t.enroll', ['course_id' => $course->courseid]) }}" class="btn btn-light-primary text-primary">Enroll
                                                    Now</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                @else
                                <div class="row  d-flex justify-content-center text-center">
                                    <div class="col-xl-5 col-lg-5 col-md-12 col-12">
                                        <div class="py-6">
                                            <img src="../assets/images/svg/padlock.svg" alt="path" class="img-fluid">
                                            <div class="mt-4 ">
                                                <h2 class="display-4 fw-bold">Access Denied</h2>
                                                <p class="mb-5">You don’t have access to view these courses.</p>
                                                <a href="{{ route('t.dashboard') }}" class="btn btn-primary">
                                                    Back To Dashboard
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="row">
                                    <div class="offset-lg-3 col-lg-6 col-md-12 col-12 text-center mt-5">
                                        <p>You’ve reached the end of the list</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- nmcr -->
                        <div class="tab-pane fade" id="nmcr" role="tabpanel" aria-labelledby="nmcr-tab">
                            <div class="row">
                                @if ($courses_nmcr->count())
                                @foreach ($courses_nmcr as $course)
                                <div class="col-lg-3 col-md-6 col-12 mb-3">
                                    <!-- Card -->
                                    <div class="card h-100 card-hover">
                                        <div href="#" class="card-img-top"><img src="{{ asset('assets/images/oesximg/nmcr-course-card.jpg') }}" alt="course" class="card-img-top rounded-top-md"></div>
                                        <!-- Card body -->
                                        <div class="card-body">
                                            <h3 class="h4 mb-2 text-truncate-line-2 ">
                                                {{ $course->coursecode }} - <i>{{ $course->coursename }}
                                                </i>
                                            </h3>

                                            <hr>
                                            <!-- List inline -->
                                            <div class="mb-3">
                                                <p><i class="mdi mdi-account-supervisor-circle text-muted"></i>
                                                    Mode of Delivery:
                                                    {{ $course->mode->modeofdelivery }}</li>
                                                </p>
                                                <p><i class="mdi mdi-map-marker-radius"></i> Location of
                                                    training: {{ $course->location->courselocation }}</p>
                                            </div>

                                            <small>Available for: <span class="badge bg-success">{{
                                                    $course->rank_level->ranklevel }}</span>
                                                <span class="badge bg-info">{{ $course->course_depart->rankdepartment }}
                                                </span></small>
                                        </div>

                                        <div class="card-footer">
                                            <div class="row align-items-center g-0">
                                                <a href="{{ route('t.enroll', ['course_id' => $course->courseid]) }}" class="btn btn-light-primary text-primary">Enroll
                                                    Now</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                <div class="row">
                                    <div class="offset-lg-3 col-lg-6 col-md-12 col-12 text-center mt-5">
                                        <p>You’ve reached the end of the list</p>
                                    </div>
                                </div>
                                @else
                                <div class="row  d-flex justify-content-center text-center">
                                    <div class="col-xl-5 col-lg-5 col-md-12 col-12">
                                        <div class="py-6">
                                            <img src="../assets/images/svg/padlock.svg" alt="path" class="img-fluid">
                                            <div class="mt-4 ">
                                                <h2 class="display-4 fw-bold">Access Denied</h2>
                                                <p class="mb-5">You don’t have access to view these courses.</p>
                                                <a href="{{ route('t.dashboard') }}" class="btn btn-primary">
                                                    Back To Dashboard
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif
                        <!-- mandatory -->
                        <div class="tab-pane @if (Auth::guard('trainee')->user()->company_id != 1) active @endif " id="mandatory" role="tabpanel" aria-labelledby="mandatory-tab">
                            <div class="row">
                                @if ($courses_man->count())
                                @foreach ($courses_man as $course)
                                @if(($course->courseid != 91 && $course->courseid != 92) || (Auth::guard('trainee')->user()->company->companyid != 1))
                                <div class="col-lg-3 col-md-6 col-12 mb-3">
                                    <!-- Card -->
                                    <div class="card card-hover h-100">
                                        <div href="#" class="card-img-top"><img src="{{ asset('assets/images/oesximg/mandatory-course-card.jpg') }}" alt="course" class="card-img-top rounded-top-md"></div>
                                        <!-- Card body -->
                                        <div class="card-body">
                                            <h3 class="h4 mb-2 text-truncate-line-2 ">
                                                {{ $course->coursecode }} - <i>{{ $course->coursename }} </i>
                                            </h3>

                                            <hr>
                                            <!-- List inline -->
                                            <div class="mb-3">
                                                <p><i class="mdi mdi-account-supervisor-circle text-muted"></i>
                                                    Mode of Delivery: {{ $course->mode->modeofdelivery }}
                                                    </li>
                                                </p>
                                                <p><i class="mdi mdi-map-marker-radius"></i> Location of
                                                    training: {{ $course->location->courselocation }}</p>
                                            </div>

                                            <small>Available for: <span class="badge bg-success">{{
                                                    $course->rank_level->ranklevel }}</span>
                                                <span class="badge bg-info">{{ $course->course_depart->rankdepartment }}
                                                </span></small>
                                        </div>
                                        <!-- Card footer -->
                                        <div class="card-footer">
                                            <div class="row align-items-center g-0">
                                                <a href="{{ route('t.enroll', ['course_id' => $course->courseid]) }}" class="btn btn-light-primary text-primary">Enroll Now</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                                <div class="row">
                                    <div class="offset-lg-3 col-lg-6 col-md-12 col-12 text-center mt-5">
                                        <p>You’ve reached the end of the list</p>
                                    </div>
                                </div>
                                @else
                                <div class="row  d-flex justify-content-center text-center">
                                    <div class="col-xl-5 col-lg-5 col-md-12 col-12">
                                        <div class="py-6">
                                            <img src="../assets/images/svg/padlock.svg" alt="path" class="img-fluid">
                                            <div class="mt-4 ">
                                                <h2 class="display-4 fw-bold">Access Denied </h2>
                                                <p class="mb-5">You don’t have access to view this courses</p>
                                                <a href="{{ route('t.dashboard') }}" class="btn btn-primary">
                                                    Back To Dashboard
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <!-- upgrading -->
                        <div class="tab-pane fade" id="upgrading" role="tabpanel" aria-labelledby="upgrading-tab">
                            <div class="row">
                                @if ($courses_up->count())
                                @foreach ($courses_up as $course)
                                <div class="col-lg-3 col-md-6 col-12 mb-3">
                                    <!-- Card -->
                                    <div class="card h-100 card-hover">
                                        <div href="#" class="card-img-top"><img src="{{ asset('assets/images/oesximg/upgrading-course-card.jpg') }}" alt="course" class="card-img-top rounded-top-md"></div>
                                        <!-- Card body -->
                                        <div class="card-body">
                                            <h3 class="h4 mb-2 text-truncate-line-2 ">
                                                {{ $course->coursecode }} - <i>{{ $course->coursename }} </i>
                                            </h3>

                                            <hr>
                                            <!-- List inline -->
                                            <div class="mb-3">
                                                <p><i class="mdi mdi-account-supervisor-circle text-muted"></i>
                                                    Mode of Delivery: {{ $course->mode->modeofdelivery }}
                                                    </li>
                                                </p>

                                                <p><i class="mdi mdi-map-marker-radius"></i> Location of
                                                    training: {{ $course->location->courselocation }}</p>
                                            </div>

                                            <small>Available for: <span class="badge bg-success">{{
                                                    $course->rank_level->ranklevel }}</span>
                                                <span class="badge bg-info">{{ $course->course_depart->rankdepartment }}
                                                </span></small>
                                        </div>
                                        <!-- Card footer -->
                                        <div class="card-footer">
                                            <div class="row align-items-center g-0">
                                                <a href="{{ route('t.enroll', ['course_id' => $course->courseid]) }}" class="btn btn-light-primary text-primary">Enroll Now</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                <div class="row">
                                    <div class="offset-lg-3 col-lg-6 col-md-12 col-12 text-center mt-5">
                                        <p>You’ve reached the end of the list</p>
                                    </div>
                                </div>
                                @else
                                <div class="row  d-flex justify-content-center text-center">
                                    <div class="col-xl-5 col-lg-5 col-md-12 col-12">
                                        <div class="py-6">
                                            <img src="../assets/images/svg/padlock.svg" alt="path" class="img-fluid">
                                            <div class="mt-4 ">
                                                <h2 class="display-4 fw-bold">Access Denied </h2>
                                                <p class="mb-5">You don’t have access to view these courses.</p>
                                                <a href="{{ route('t.dashboard') }}" class="btn btn-primary">
                                                    Back To Dashboard
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <!-- jiss -->
                        {{-- <div class="tab-pane fade" id="jiss" role="tabpanel" aria-labelledby="jiss-tab">
                            <div class="row">
                                @if ($courses_ji->count())
                                @foreach ($courses_ji as $course)
                                <div class="col-lg-3 col-md-6 col-12 mb-3">
                                    <!-- Card -->
                                    <div class="card h-100 card-hover">
                                        <div href="#" class="card-img-top"><img
                                                src="{{ asset('assets/images/oesximg/jiss-course-card.jpg') }}"
                        alt="course" class="card-img-top rounded-top-md">
                    </div>
                    <!-- Card body -->
                    <div class="card-body">
                        <h3 class="h4 mb-2 text-truncate-line-2 ">
                            {{ $course->coursecode }} - <i>{{ $course->coursename }} </i>
                        </h3>

                        <hr>
                        <!-- List inline -->
                        <div class="mb-3">
                            @if ($course->mode->id == 1)
                            <p><i class="mdi mdi-account-supervisor-circle text-muted me-1"></i>
                                Mode of Delivery: {{ $course->mode->modeofdelivery }}
                                </li>
                            </p>
                            <p><i class="mdi mdi-web"></i> Online:
                                @if ($course->numberofdayonline > 1)
                                {{ $course->numberofdayonline }} days
                                @else
                                {{ $course->numberofdayonline }} day
                                @endif
                            </p>
                            @elseif ($course->mode->id == 2)
                            <p><i class="mdi mdi-account-supervisor-circle text-muted me-1"></i>
                                Mode of Delivery: {{ $course->mode->modeofdelivery }}
                            </p>
                            @if ($course->mode->numberofdayonsite > 1)
                            <p><i class="mdi mdi-account-hard-hat"></i> Onsite:
                                {{ $course->numberofdayonsite }} days
                            </p>
                            @else
                            <p><i class="mdi mdi-account-hard-hat"></i> Onsite:
                                {{ $course->numberofdayonsite }} day
                            </p>
                            @endif
                            @elseif ($course->mode->id == 3)
                            <p><i class="mdi mdi-account-supervisor-circle text-muted me-1"></i>Mode
                                of Delivery: {{ $course->mode->modeofdelivery }}</p>
                            @if ($course->mode->numberofdayonsite > 1)
                            <p><i class="mdi mdi-account-hard-hat"></i> Onsite:
                                {{ $course->numberofdayonsite }} days
                            </p>
                            @else
                            <p><i class="mdi mdi-account-hard-hat"></i> Onsite:
                                {{ $course->numberofdayonsite }} day
                            </p>
                            @endif
                            @if ($course->numberofdayonline > 1)
                            <p><i class="mdi mdi-web"></i> Online:
                                {{ $course->numberofdayonline }} days
                            </p>
                            @else
                            <p><i class="mdi mdi-web"></i> Online:
                                {{ $course->numberofdayonline }} day
                            </p>
                            @endif
                            @endif
                            <p><i class="mdi mdi-map-marker-radius"></i> Location of
                                training: {{ $course->location->courselocation }}</p>
                        </div>

                        <small>Available for: <span class="badge bg-success">{{
                                                    $course->rank_level->ranklevel }}</span>
                            <span class="badge bg-info">{{ $course->course_depart->rankdepartment }}
                            </span></small>
                    </div>
                    <!-- Card footer -->
                    <div class="card-footer">
                        <div class="row align-items-center g-0">
                            <a href="{{ route('t.enroll', ['course_id' => $course->courseid]) }}" class="btn btn-light-primary text-primary">Enroll Now</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            <div class="row">
                <div class="offset-lg-3 col-lg-6 col-md-12 col-12 text-center mt-5">
                    <p>You’ve reached the end of the list</p>
                </div>
            </div>
            @else
            <div class="row  d-flex justify-content-center text-center">
                <div class="col-xl-5 col-lg-5 col-md-12 col-12">
                    <div class="py-6">
                        <img src="../assets/images/svg/padlock.svg" alt="path" class="img-fluid">
                        <div class="mt-4 ">
                            <h2 class="display-4 fw-bold">Access Denied </h2>
                            <p class="mb-5">You don’t have access to view these courses.</p>
                            <a href="../index.html" class="btn btn-primary">
                                Back To Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
</div> --}}
<!-- other gov. -->
<div class="tab-pane fade" id="gov" role="tabpanel" aria-labelledby="gov-tab">
    <div class="row">
        @if ($courses_gov->count())
        @foreach ($courses_gov as $course)
        <div class="col-lg-3 col-md-6 col-12 mb-3">
            <!-- Card -->
            <div class="card h-100 card-hover">
                <div href="#" class="card-img-top"><img src="{{ asset('assets/images/oesximg/card.jpg') }}" alt="course" class="card-img-top rounded-top-md"></div>
                <!-- Card body -->
                <div class="card-body">
                    <h3 class="h4 mb-2 text-truncate-line-2 ">
                        {{ $course->coursecode }} - <i>{{ $course->coursename }} </i>
                    </h3>

                    <hr>
                    <!-- List inline -->
                    <div class="mb-3">
                        <p><i class="mdi mdi-account-supervisor-circle text-muted"></i>
                            Mode of Delivery: {{ $course->mode->modeofdelivery }}
                            </li>
                        </p>

                        <p><i class="mdi mdi-map-marker-radius"></i> Location of
                            training: {{ $course->location->courselocation }}</p>
                    </div>

                    <small>Available for: <span class="badge bg-success">{{
                                                    $course->rank_level->ranklevel }}</span>
                        <span class="badge bg-info">{{ $course->course_depart->rankdepartment }}
                        </span></small>
                </div>
                <!-- Card footer -->
                <div class="card-footer">
                    <div class="row align-items-center g-0">
                        <a href="{{ route('t.enroll', ['course_id' => $course->courseid]) }}" class="btn btn-light-primary text-primary">Enroll Now</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        <div class="row">
            <div class="offset-lg-3 col-lg-6 col-md-12 col-12 text-center mt-5">
                <p>You’ve reached the end of the list</p>
            </div>
        </div>
        @else
        <div class="row  d-flex justify-content-center text-center">
            <div class="col-xl-5 col-lg-5 col-md-12 col-12">
                <div class="py-6">
                    <img src="../assets/images/svg/padlock.svg" alt="path" class="img-fluid">
                    <div class="mt-4 ">
                        <h2 class="display-4 fw-bold">Access Denied </h2>
                        <p class="mb-5">You don’t have access to view these courses.</p>
                        <a href="../index.html" class="btn btn-primary">
                            Back To Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
</div>
</div>
</div>
</div>
</section>
</div>