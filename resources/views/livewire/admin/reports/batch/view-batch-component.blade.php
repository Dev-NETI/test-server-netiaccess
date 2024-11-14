<section class="container-fluid p-4">

    <span wire:loading>
        <livewire:components.loading-screen-component />
    </span>

    <div class="row">
        <!-- Page Header -->
        @if (Auth::user()->u_type == 1)
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-flex justify-content-between align-items-center">
                <div class="mb-2 mb-lg-0">
                    <h1 class="mb-1 h2 fw-bold">
                        Generate Batch report
                        <span class="fs-5 text-muted"></span>
                    </h1>
                    <!-- Breadcrumb  -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('a.dashboard') }}">Administrator</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('a.report-dashboard') }}">Reports</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('a.report-batch') }}">Generate Batch report</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ $schedule->course->coursename }}
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        @else
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-flex justify-content-between align-items-center">
                <div class="mb-2 mb-lg-0">
                    <h1 class="mb-1 h2 fw-bold">
                        My class
                        <span class="fs-5 text-muted"></span>
                    </h1>
                    <!-- Breadcrumb  -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('i.dashboard') }}">Instructor</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('i.dashboard') }}">My class</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ $schedule->course->coursename }}
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        @endif

        <div class="col-lg-12 col-md-12 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="float-left"><i> SCHEDULE # : {{$schedule->scheduleid}}</i></h5>
                            </div>

                            <div class="col-md-6">
                                <h5 class="float-end"><i> HANDOUT PASSWORD : {{ ($schedule->course->handout_password) ? $schedule->course->handout_password : 'N/A' }}</i></h5>
                            </div>

                            <div class="col-md-6">
                                <a class="float-left " href="       
                                @if($schedule->course->coursetypeid == 1)
                                https://drive.google.com/drive/folders/1HPmYbISrnPFfsxm7MXK1YHDW5JkRvNOv
                                @elseif($schedule->course->coursetypeid == 2 || $schedule->course->coursetypeid == 7) 
                                https://drive.google.com/drive/folders/1ZOBR3O8bPotkIblHm1eXe3ER4zIf2zV4
                                @elseif($schedule->course->coursetypeid == 5)
                                https://drive.google.com/drive/folders/11kCDpDR7II-E0zVriQZHwnqT08M5VUgM
                                @elseif($schedule->course->coursetypeid == 3 || $schedule->course->coursetypeid == 4)
                                https://drive.google.com/drive/folders/1Hc55bJ7k_jzh07HZc4P6F3WZKqRyk2x1
                                @endif" target="_blank"><b> TRAINING DOCS</b></a>
                            </div>
                            <div class="col-md-6">
                                <a class="float-end" href="https://sites.google.com/neti.com.ph/netitrainingportal/home" target="_blank"><b> NETI PORTAL</b></a>
                            </div>
                        </div>

                    </div>
                    <div class="text-center">
                        @if($schedule->course->modeofdeliveryid === 1 || $schedule->course->modeofdeliveryid === 3)
                        <a href="{{$schedule->course->meetingcredentials}}" class="float-center text-secondary" target="_blank">
                            Click the <img src="{{asset('assets/images/oesximg/zoom.png')}}" width="80px" height="50px" alt="Zoom Icon" class="img-fluid"> Link
                        </a>
                        @endif
                        <h5>{{ $schedule->course->type->coursetype }}</h5>
                        <h2> {{ $schedule->course->coursecode }} - {{ $schedule->course->coursename }}</h2>
                        <h5> {{ date('d F, Y', strtotime($schedule->startdateformat)) }} -
                            {{ date('d, F, Y', strtotime($schedule->enddateformat)) }}
                        </h5>

                        <h5 class="text-uppercase">
                            <i>
                                @if ($schedule->instructor && $schedule->instructor->user &&
                                $schedule->instructor->user->user_id != 93)
                                INSTRUCTOR: {{ $schedule->instructor->user->formal_name() }}
                                @endif

                                @if ($schedule->assessor && $schedule->assessor->user &&
                                $schedule->assessor->user->user_id != 93)
                                |
                                ASSESSOR: {{ $schedule->assessor->user->instructor_name }}
                                @endif
                            </i>
                        </h5>
                    </div>
                </div>
            </div>
        </div>
        @if (Auth::user()->u_type == 1)
        <div class="col-lg-12 col-12 gap-2  mt-4">
            @if ($schedule->course->coursetypeid == 1)
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Training Info</h4>
                </div>

                <div class="card-body ">
                    <div class="row">
                        <div class="col-lg-4 col-4 gap-2">
                            <div class="d-flex justify-content-between  text-center ">

                                <label class="form-label" for="">Class Number</label>

                                <x-action-message on="updatedClassNumber" />

                            </div>

                            <input type="text" class="form-control mb-2" placeholder="Enter the Class No." wire:model.lazy="class_number">
                        </div>

                        <div class="col-4 col-md-4 col-lg-4">
                            <div class="d-flex justify-content-between  text-center ">

                                <label class="form-label" for="">Practical Date</label>

                                <x-action-message on="updatedPracticumDate" />

                            </div>

                            @props([
                            'options' => "{ mode:'range',
                            dateFormat:'d-M-Y',
                            altInput:true, }",
                            ])

                            <div wire:ignore>
                                <input x-data x-init="flatpickr($refs.input, {{ $options }});" x-ref="input" class="form-control mb-2" type="text" data-input wire:model.debounce.1000ms="practicum_date" />
                            </div>
                        </div>

                        <div class="col-4 col-md-4 col-lg-4">

                            <div class="d-flex justify-content-between  text-center">

                                <label class="form-label" for="">Practicum Site</label>

                                <x-action-message on="updatedPracticumSite" />

                            </div>

                            <input type="text" class="form-control mb-2" placeholder="Enter practicum site" wire:model.lazy="practicum_site">
                        </div>
                    </div>

                </div>

            </div>
            @endif
        </div>
        @endif
        <div class="row">
            <div class=" col-12 col-lg-12  gap-2 d-flex flex-wrap justify-content-center align-items-center   mt-4">
                <x-error-message />

                @if (Auth::user()->u_type == 1)

                @if ($schedule->course->coursetypeid != 1)
                <button wire:click.prevent="generateTPER({{$schedule->scheduleid}})" class="btn btn-info mt-1 mb-1">
                    <i class="bi bi-filetype-pdf"></i> F-NETI-104: TPER
                </button>

                <button class="btn btn-success mt-1 mb-1" data-bs-toggle="modal" data-bs-target="#trainingreport">
                    <i class="bi bi-file-earmark-text"></i> F-NETI-100: TRAINING REPORT
                </button>
                @endif

                <div x-data="{ url: '{{ route('a.viewattendance', ['scheduleid' => $schedule->scheduleid]) }}' }">
                    <button @click="window.open(url, '_blank')" wire:click.prevent="log('attendance')" class="btn btn-primary mt-1 mb-1">
                        <i class="bi bi-filetype-pdf"></i> F-NETI-026: ATTENDANCE
                    </button>
                </div>

                <div x-data="{ url: '{{ route('a.atd-slaf', ['scheduleid' => $schedule->scheduleid]) }}' }">
                    <button @click="window.open(url, '_blank')" wire:click.prevent="log('atd_slaf')" class="btn btn-secondary mt-1 mb-1">
                        <i class="bi bi-filetype-pdf"></i> F-NETI-067B: ATD/SLAF
                    </button>
                </div>

                <a class="btn btn-info mt-1 mb-1" href="{{ route('a.grades') }}" target="_blank"><i class="bi bi-filetype-pdf"></i> E-GRADES (TEMPLATE)</a>

                <div class="btn-group" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        CERTIFICATE
                    </button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        @if ($schedule->course->courseid == 66 || $schedule->course->courseid == 113)

                        <div x-data="{ url: '{{ route('a.pdoscertificates', ['scheduleid' => $schedule->scheduleid]) }}' }">
                            <button @click="window.open(url, '_blank')" wire:click.prevent="log('certificate_of_completion')" class="btn btn-danger mt-1 mb-1">
                                CERTIFICATE OF COMPLETION
                            </button>
                        </div>
                        @else

                        <div x-data="{ url: '{{ route('a.certificates', ['scheduleid' => $schedule->scheduleid]) }}' }">
                            <button @click="generateCertificate" wire:click.prevent="log('certificate_of_completion')" class="dropdown-item">
                                CERTIFICATE OF COMPLETION
                            </button>
                        </div>


                        @endif
                        <div x-data>
                            <button @click="sendApproval({{$schedule->scheduleid}})" class="dropdown-item">
                                SEND APPROVAL TO GM
                            </button>
                        </div>
                    </div>

                </div>



                @if ($schedule->course->coursetypeid == 1)

                <div x-data="{ url: '{{ route('a.er', ['scheduleid' => $schedule->scheduleid]) }}' }">
                    <button @click="window.open(url, '_blank')" wire:click.prevent="log('enrollment_report')" class="btn btn-primary mt-1 mb-1">
                        <i class="bi bi-filetype-pdf"></i>F-NETI-433: ER
                    </button>
                </div>

                <div x-data="{ url: '{{ route('a.ccr', ['scheduleid' => $schedule->scheduleid]) }}' }">
                    <button @click="window.open(url, '_blank')" wire:click.prevent="log('course_completion_report')" class="btn btn-warning mt-1 mb-1">
                        <i class="bi bi-filetype-pdf"></i> CCR
                    </button>
                </div>

                @if ($schedule->course->tcroapath && Auth::user()->u_type != 2)
                <a class="btn btn-success mt-1 mb-1" href="{{ route('a.tcroa') }}" target="_blank"><i class="bi bi-filetype-pdf"></i> TCROA</a>
                @endif

                @endif

                @else
                <div x-data="{ url: '{{ route('i.viewattendance', ['scheduleid' => $schedule->scheduleid]) }}' }">
                    <button @click="window.open(url, '_blank')" wire:click.prevent="log('attendance')" class="btn btn-primary mt-1 mb-1">
                        <i class="bi bi-filetype-pdf"></i> F-NETI-026: ATTENDANCE
                    </button>
                </div>


                @if ($schedule->course->coursetypeid != 1)
                <button wire:click.prevent="generateTPER({{$schedule->scheduleid}})" class="btn btn-info mt-1 mb-1">
                    <i class="bi bi-filetype-pdf"></i> F-NETI-104: TPER
                </button>

                <button class="btn btn-success mt-1 mb-1" data-bs-toggle="modal" data-bs-target="#trainingreport">
                    <i class="bi bi-file-earmark-text"></i> F-NETI-100: TRAINING REPORT
                </button>
                @endif


                @if(Auth::user()->u_type == 1 || $schedule->assessorid == Auth::user()->user_id)
                <a class="btn btn-success mt-1 mb-1" href="{{ route('i.tcroa') }}" target="_blank"><i class="bi bi-filetype-pdf"></i> TCROA</a>
                @endif

                @if (Auth::user()->u_type == 2)
                <a class="btn btn-info mt-1 mb-1" href="{{ route('i.grades') }}" target="_blank"><i class="bi bi-filetype-pdf"></i> E-GRADES (TEMPLATE)</a>
                @endif

                @endif


                <div wire:ignore.self class="modal fade" id="trainingreport" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title" id="exampleModalLabel">Training Report</h3>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                </button>
                            </div>
                            <div class="modal-body">
                                @if ($checkalreadyaddreport != 1)
                                <form id="trainingreportform" action="" wire:submit.prevent="addtrainingreport">
                                    @else
                                    <form id="trainingreportform" action="" wire:submit.prevent="updatetrainingreport">
                                        @endif
                                        @csrf
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <input class="form-control" wire:model.defer="idschedule" hidden type="text">
                                                <label for="">
                                                    <h4>1. NMC No.:</h4>
                                                </label>
                                                <input class="form-control" wire:model.defer="coursecode" disabled type="text">
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="">
                                                    <h4>2. Name of Training Course:</h4>
                                                </label>
                                                <input class="form-control" wire:model.defer="coursename" disabled type="text">
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="">
                                                    <h4>3. Date of Training:</h4>
                                                </label>
                                                <input class="form-control" wire:model.defer="dateoftraining" disabled type="text">
                                            </div>
                                            <div class="col-lg-6 mt-1">
                                                <label for="">
                                                    <h4>4. Name of Instructor(s):</h4>
                                                </label>
                                                <input class="form-control" wire:model.defer="instructorname" disabled type="text">
                                            </div>
                                            <div class="col-lg-6 mt-1">
                                                <label for="">
                                                    <h4>5. Training Center:</h4>
                                                </label>
                                                <input class="form-control" wire:model.defer="trainingcenter" disabled type="text">
                                            </div>
                                            <div class="col-lg-12 mt-1">
                                                <label for="">
                                                    <h4>6. Name/Rank of Trainess:</h4>
                                                </label>
                                                <textarea class="form-control" wire:model.defer="arraytraineename" disabled type="text" rows="4">
                                        </textarea>
                                            </div>
                                            <hr class="mt-5">
                                            <div class="col-lg-12 mt-1">
                                                <label for="">
                                                    <h4>7. Summary for Trainee’s Comments: <br>I. What about the seminar
                                                        has been the most helpful for your learning thus far?</h4>
                                                </label>
                                                <textarea class="form-control" wire:model.defer="q1a" type="text" rows="4" autofocus placeholder="Leave something e.g. (n/a)..." required oninput="limitTextarea(this, 3500)"></textarea>
                                            </div>
                                            <div class="col-lg-12 mt-1">
                                                <label for="">
                                                    <h4>II. What about the course has caused you the most difficulty in
                                                        terms of learning thus far? </h4>
                                                </label>
                                                <textarea class="form-control" wire:model.defer="q1b" type="text" rows="4" placeholder="Leave something e.g. (n/a)..." required oninput="limitTextarea2(this, 3500)"></textarea>
                                            </div>
                                            <div class="col-lg-12 mt-1">
                                                <label for="">
                                                    <h4>8. Instructor’s Comment: </h4>
                                                </label>
                                                <textarea class="form-control" wire:model.defer="q2" type="text" rows="4" placeholder="Leave something e.g. (n/a)..." required oninput="limitTextarea3(this, 3000)"></textarea>
                                            </div>
                                            <div class="col-lg-12 mt-1">
                                                <label for="">
                                                    <h4>9. Opinion for Improvement of Training: </h4>
                                                </label>
                                                <textarea class="form-control" wire:model.defer="q3" type="text" rows="4" placeholder="Leave something e.g. (n/a)..." required oninput="limitTextarea4(this, 1900)"></textarea>
                                            </div>
                                            <hr class="mt-3">
                                            <h3>Attachments:</h3>
                                            <div class="col-lg-12 mt-1">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h4 class="mb-0">Training Feedback Form</h4>
                                                    <div class="form-check form-switch">
                                                        <input type="checkbox" wire:model.defer="ttf" class="form-check-input" id="googleSwitch">
                                                        <label class="form-check-label" for="googleSwitch"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 mt-1">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h4 class="mb-0">Candidate Attendance Sheet</h4>
                                                    <div class="form-check form-switch">
                                                        <input type="checkbox" wire:model.defer="cas" class="form-check-input" id="googleSwitch">
                                                        <label class="form-check-label" for="googleSwitch"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 mt-1">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h4 class="mb-0">Student Performance Evaluation Report</h4>
                                                    <div class="form-check form-switch">
                                                        <input type="checkbox" wire:model.defer="sper" class="form-check-input" id="googleSwitch">
                                                        <label class="form-check-label" for="googleSwitch"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 mt-1">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h4 class="mb-0">Test Results</h4>
                                                    <div class="form-check form-switch">
                                                        <input type="checkbox" wire:model.defer="tr" class="form-check-input" id="googleSwitch">
                                                        <label class="form-check-label" for="googleSwitch"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 mt-1">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h4 class="mb-0">Others, Pls Specify (Seat
                                                        works/Exercises/Assessment etc.)</h4>
                                                    <div class="form-check form-switch">
                                                        <input type="checkbox" wire:model="others" class="form-check-input" id="googleSwitch">
                                                        <input type="text" hidden wire:model="trid" class="form-control" id="googleSwitch">
                                                        <label class="form-check-label" for="googleSwitch"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($others == 1)
                                            <div class="col-lg-12 mt-1" id="inputcontainer">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <input type="text" class="form-control" required wire:model.defer="otherforms" placeholder="Please Specify">
                                                </div>
                                            </div>
                                            @endif
                                        </div>

                                        <script>
                                            function limitTextarea(element, maxLength) {
                                                if (element.value.length > maxLength) {
                                                    element.value = element.value.substring(0, maxLength);
                                                }
                                            }

                                            function limitTextarea2(element, maxLength) {
                                                if (element.value.length > maxLength) {
                                                    element.value = element.value.substring(0, maxLength);
                                                }
                                            }

                                            function limitTextarea3(element, maxLength) {
                                                if (element.value.length > maxLength) {
                                                    element.value = element.value.substring(0, maxLength);
                                                }
                                            }

                                            function limitTextarea4(element, maxLength) {
                                                if (element.value.length > maxLength) {
                                                    element.value = element.value.substring(0, maxLength);
                                                }
                                            }
                                        </script>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                @if ($checkalreadyaddreport != 1)
                                <button type="submit" form="trainingreportform" class="btn btn-primary">Submit</button>
                                @else
                                <button type="submit" form="trainingreportform" class="btn btn-primary">Update</button>
                                <a wire:click.prevent="generatetrainingreport" target="_blank" class="btn btn-danger"><i class="bi bi-file-earmark-pdf-fill"></i> Generate PDF <i class="bi bi-box-arrow-in-right"></i></a>
                                @endif
                            </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

            {{-- @endif --}}

            {{-- @else
            <a href="{{ route('i.viewattendance', ['scheduleid' => $schedule->scheduleid]) }}"
            wire:click="log('attendance')" target="_blank" class="btn btn-primary mt-1 mb-1"><i class="bi bi-filetype-pdf"></i>F-NETI-026: ATTENDANCE</a>
            @endif --}}

            {{-- TPER --}}


        </div>

        <div class="row">
            <div class="border-bottom pb-3 mb-3 d-flex justify-content-between align-items-end mt-4">
                <div class="mb-2 mb-lg-0">
                    <h1 class="mb-1 h2 fw-bold">
                        Trainees Profile
                    </h1>
                </div>

                <div class="nav btn-group" role="tablist">
                    <button class="btn btn-outline-secondary " data-bs-toggle="tab" data-bs-target="#tabPaneGrid" role="tab" aria-controls="tabPaneGrid" aria-selected="true">
                        <span class="fe fe-grid"></span>
                    </button>
                    <button class="btn btn-outline-secondary active" data-bs-toggle="tab" data-bs-target="#tabPaneList" role="tab" aria-controls="tabPaneList" aria-selected="false" tabindex="-1">
                        <span class="fe fe-list"></span>
                    </button>
                </div>
            </div>

            <div class="alert alert-info d-flex align-items-center" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-info-circle-fill me-2" viewBox="0 0 16 16">
                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
                </svg>
                <div>
                    Always ensure the accuracy of trainee names and their birthdays, as this information is crucial for
                    generating precise reports throughout our system. Verify that names are in sentence case and
                    carefully check for typographic issues.
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-12 mt-3">
                <div class="tab-content">
                    <div class="tab-pane fade " id="tabPaneGrid" role="tabpanel" aria-labelledby="tabPaneGrid">
                        <div class="row">
                            @if ($trainees->count())
                            @foreach ($trainees as $trainee)
                            <div class="col-xl-3 col-lg-6 col-md-6 col-12 mb-3">
                                <!-- Card -->
                                <div class="card mb-3 h-100">
                                    <!-- Card body -->
                                    <div class="card-body">
                                        <div class="text-center">
                                            <div class="position-relative">
                                                <img src="{{ asset('assets/images/avatar/avatar.jpg') }}" class="rounded-circle avatar-xl mb-3" alt="">
                                                <a href="#" class="position-absolute mt-10 ms-n5">
                                                    <span class="status bg-secondary"></span>
                                                </a>
                                            </div>
                                            <h4 class="mb-0 text-uppercase @if ($trainee->attendance_status == 1) text-danger @endif @if ($trainee->IsRemedial == 1) text-warning @endif">
                                                {{ $trainee->trainee->formal_name() }} @if ($trainee->IsRemedial == 1)
                                                (Remedial) @endif
                                            </h4>
                                            @if ($trainee->address)
                                            <small>YOUR CURRENT ADDRESS: </small>
                                            <p class="mb-0">
                                                <i class="fe fe-map-pin me-1 fs-6"></i>{{ $trainee->address }}
                                            </p>
                                            @elseif ($trainee->brgyCode)
                                            <p class="mb-0">
                                                <i class="text-uppercase">{{ $trainee->trainee->street }}
                                                    , {{ $trainee->trainee->brgy->brgyDesc }},
                                                    {{ $trainee->trainee->city->citymunDesc }},
                                                    {{ $trainee->trainee->prov->provDesc }},
                                                    {{ $trainee->trainee->reg->regDesc }},
                                                    {{ $trainee->trainee->postal }}</i>
                                            </p>
                                            @else
                                            <p class="mb-0">
                                                <i class="fe fe-map-pin me-1 fs-6"></i><i class="text-danger">Update
                                                    Profile Required</i>
                                            </p>
                                            @endif

                                        </div>
                                        <div class="d-flex justify-content-between border-bottom py-2 mt-6">
                                            <span>Rank</span>
                                            <span class="text-dark">{{ $trainee->trainee->rank->rankacronym }}
                                                - {{ $trainee->trainee->rank->rank }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between border-bottom py-2">
                                            <span>Enrolled at</span>
                                            <span class="text-dark">
                                                {{ date('d F, Y', strtotime($trainee->dateconfirmed)) }}
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-between pt-2">
                                            <span>Company</span>
                                            <span class="text-dark">{{ $trainee->trainee->company->company }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @else
                            <div class="text-center">
                                <h3>NO TRAINEES FOUND</h3>
                            </div>
                            @endif

                            <div class="col-lg-12 col-md-12 col-12">
                                <!-- Pagination -->
                                @if ($trainees)
                                {{ $trainees->links('livewire.components.customized-pagination-link') }}
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade active show" id="tabPaneList" role="tabpanel" aria-labelledby="tabPaneList">
                        <div class="card">
                            <div class="table-responsive">
                                <table class="table table-sm text-nowrap mb-0 table-centered">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Profile</th>
                                            <th scope="col">First Name</th>
                                            <th scope="col">Middle Name</th>
                                            <th scope="col">Last Name</th>
                                            <th scope="col">Suffix</th>
                                            @if (Auth::user()->u_type == 1)
                                            <th scope="col">CLN &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                                &nbsp; &nbsp; </th>
                                            @endif
                                            @if (Auth::user()->u_type == 1 && $schedule->course->courseid == 77)
                                            <th scope="col">TEMPLATE &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                                &nbsp; &nbsp; </th>
                                            @endif
                                            @if (Auth::user()->u_type == 1)
                                            @if($schedule->course->coursetypeid != 3 && $schedule->course->coursetypeid != 4)
                                            <th scope="col">CONTROL NUMBER</th>
                                            @endif
                                            @endif
                                            @if ($schedule->course->courseid == 66 || $schedule->course->courseid ==
                                            113)
                                            <th scope="col">Country Destination <small class="text-danger">(for
                                                    PDOS)</small></th>
                                            <th scope="col">Foreign Principal <small class="text-danger">(for
                                                    PDOS)</small></th>
                                            <th scope="col">Foreign Employer <small class="text-danger">(for
                                                    PDOS)</small></th>
                                            <th scope="col">Expiry Date <small class="text-danger">(for
                                                    PDOS)</small></th>
                                            @endif
                                            <th scope="col">Rank</th>
                                            @if (Auth::user()->u_type == 2)
                                            <th scope="col">Email Address</th>
                                            @endif
                                            <th scope="col">Birthday</th>
                                            <th scope="col">Company</th>
                                            @if (Auth::user()->u_type == 1)
                                            <th scope="col">Mode of Payment</th>
                                            @endif
                                            <th scope="col">Action</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($trainees->count())
                                        @foreach ($trainees as $trainee)
                                        <tr style="@if($trainee->is_generated_tper == 1)background-color: #493323; color: white; @endif">
                                            <td>
                                                <span>
                                                    <small>
                                                        <i>#{{ $trainee->enroledid }}</i>
                                                    </small>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="position-relative">
                                                        @if (optional($trainee->trainee)->imagepath)
                                                        <a href="{{asset('storage/traineepic/'. $trainee->trainee->imagepath)}}" target="_blank" data-lightbox="image-1" data-title="Trainee Image">
                                                            <img src="{{asset('storage/traineepic/'. $trainee->trainee->imagepath)}}" alt="" height="200" class="avatar-md me-2">
                                                        </a>
                                                        @else
                                                        <a href="{{asset('assets/images/avatar/noimageavailable.jpg')}}" data-lightbox="image-1" data-title="Trainee Image">
                                                            <img src="{{asset('assets/images/avatar/noimageavailable.jpg')}}" alt="" height="200" class="avatar-md me-2">
                                                        </a>
                                                        @endif
                                                    </div>
                                                    <!-- <h5 class="mb-0 text-uppercase @if ($trainee->attendance_status == 1) text-danger @endif @if ($trainee->IsRemedial == 1) text-warning @endif">
                                                        {{ $trainee->trainee->formal_name() }} @if ($trainee->IsRemedial == 1) (Remedial) @endif
                                                    </h5> -->
                                                </div>
                                            </td>

                                            <td>
                                                @if($editingTraineeId == $trainee->traineeid)
                                                <input type="text" class="form-control form-control-sm text-dark" wire:model.defer="f_name" placeholder="insert here.." wire:loading.remove>
                                                @else
                                                <small class="fs-4">{{ $trainee->f_name }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($editingTraineeId == $trainee->traineeid)
                                                <input type="text" class="form-control form-control-sm text-dark" wire:model.defer="m_name" placeholder="insert here.." wire:loading.remove>
                                                @else
                                                <small class="fs-4">{{ $trainee->m_name }}</small>
                                                @endif
                                            </td>

                                            <td>
                                                @if($editingTraineeId == $trainee->traineeid)
                                                <input type="text" class="form-control form-control-sm text-dark" wire:model.defer="l_name" placeholder="insert here.." wire:loading.remove>
                                                @else
                                                <small class="fs-4">{{ $trainee->l_name }} @if ($trainee->IsRemedial ==
                                                    1)
                                                    <i style="color: maroon"> (Remedial) </i>
                                                    @endif</small>
                                                @endif
                                            </td>

                                            <td>
                                                @if($editingTraineeId == $trainee->traineeid)
                                                <input type="text" class="form-control form-control-sm text-dark" wire:model.defer="suffix" placeholder="insert here.." wire:loading.remove>
                                                @else
                                                <small class="fs-4">{{ $trainee->suffix }}</small>
                                                @endif
                                            </td>

                                            @if (Auth::user()->u_type == 1)
                                            <td>
                                                @if ($trainee->cln)
                                                <small class="fs-6">{{ $trainee->cln->cln_type }}</small>
                                                @endif
                                                <select class="form-select form-select-sm text-dark" wire:model.lazy="array_cln.{{ $trainee->enroledid }}" wire:loading.remove>
                                                    <option selected>Enter CLN</option>
                                                    @foreach ($cln as $type)
                                                    <option value="{{ $type->id }}">
                                                        {{ $type->cln_type }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            @endif

                                            @if (Auth::user()->u_type == 1 && $schedule->course->courseid == 77)
                                            <td>
                                                @if(optional($trainee)->certificate_template_id === 0)
                                                <small class="fs-6 ">
                                                    {{optional($trainee)->template}}
                                                </small>
                                                @endif
                                                @if(optional($trainee)->certificate_template_id)
                                                <small class="fs-6 ">
                                                    @if(optional($trainee)->certificate_template_id === 0)
                                                    BELOW
                                                    @else
                                                    ABOVE
                                                    @endif
                                                </small>
                                                @endif
                                                <select class="form-select form-select-sm text-dark" wire:model.lazy="array_template.{{ $trainee->enroledid }}" wire:loading.remove>
                                                    <option value="0">BELOW</option>
                                                    <option value="1">ABOVE</option>
                                                </select>
                                            </td>
                                            @endif

                                            @if (Auth::user()->u_type == 1)
                                            @if($schedule->course->coursetypeid != 3 && $schedule->course->coursetypeid != 4)
                                            <td>
                                                @if ($trainee->controlnumber)
                                                <small class="fs-6 text-white">{{ $trainee->controlnumber }}</small>
                                                @endif
                                                <input type="number" class="form-control form-control-sm text-dark" wire:model.lazy="array_control.{{ $trainee->enroledid }}" placeholder="insert here.." wire:loading.remove oninput="javascript: if (this.value.length > 5) this.value = this.value.slice(0, 5);">
                                            </td>
                                            @endif
                                            @endif
                                            @if ($schedule->course->courseid == 66 || $schedule->course->courseid == 113)
                                            <td>
                                                <small class="fs-6 text-white">{{ $trainee->pdos_destination }}</small>
                                                <input type="number" wire:model.lazy="array_country.{{ $trainee->enroledid }}" class="form-control form-control-sm text-dark" placeholder="insert here.." wire:loading.remove>
                                            </td>
                                            <td>
                                                <small class="fs-6 text-white">{{ $trainee->pdos_principal }}</small>
                                                <input type="text" wire:model.lazy="array_principal.{{ $trainee->enroledid }}" class="form-control form-control-sm text-dark" placeholder="insert here.." wire:loading.remove>
                                            </td>
                                            <td>
                                                <small class="fs-6 text-white">{{ $trainee->pdos_employer }}</small>
                                                <input type="text" wire:model.lazy="array_employer.{{ $trainee->enroledid }}" class="form-control form-control-sm text-dark" placeholder="insert here.." wire:loading.remove>
                                            </td>
                                            <td>
                                                <small class="fs-6 text-white">{{ $trainee->pdos_expiry }}</small>
                                                <input type="date" wire:model.lazy="array_expiry.{{ $trainee->enroledid }}" class="form-control form-control-sm text-dark" placeholder="insert here.." wire:loading.remove>
                                            </td>
                                            @endif
                                            <td>
                                                {{ $trainee->trainee->rank->rankacronym }}
                                            </td>
                                            @if (Auth::user()->u_type == 2)
                                            <td>
                                                @if(optional($trainee->trainee)->email)
                                                {{$trainee->trainee->email}}
                                                @else
                                                N/A
                                                @endif
                                            </td>
                                            @endif
                                            <td>
                                                @if($editingTraineeId == $trainee->traineeid)
                                                <input type="text" class="form-control form-control-sm text-dark" wire:model.defer="birthday" wire:loading.remove>
                                                @else
                                                {{ date('F d, Y', strtotime($trainee->birthday)) }}
                                                @endif
                                            </td>
                                            <td>
                                                {{ $trainee->trainee->company->company }}
                                            </td>
                                            @if (Auth::user()->u_type == 1)
                                            <td>
                                                @if($trainee->paymentmodeid == 1)
                                                <small class="fs-4">Company Sponsored</small>
                                                @elseif($trainee->paymentmodeid == 2)
                                                <small class="fs-4">Own Pay</small>
                                                @elseif($trainee->paymentmodeid == 3)
                                                <small class="fs-4">Salary Deduction</small>
                                                @elseif($trainee->paymentmodeid == 4)
                                                <small class="fs-4">NTIF Boarding loan</small>
                                                @endif
                                            </td>
                                            @endif
                                            <td>
                                                @if($editingTraineeId == $trainee->traineeid)
                                                <button class="btn btn-sm btn-success" wire:click="save_edit({{ $trainee->traineeid }})">UPDATE</button>
                                                @else
                                                <button class="btn btn-sm btn-warning" wire:click="edit({{ $trainee->traineeid }})">EDIT</button>
                                                @endif
                                                @if (Auth::user()->u_type == 1)
                                                @if (Auth::user()->dep_type == 7 || Auth::user()->dep_type == 1)
                                                <button class="btn btn-sm btn-dark" data-bs-toggle="modal" wire:click="edit_enroll({{$trainee->enroledid}})" data-bs-target="#editenrollment"><i class="bi bi-person-check"></i>&nbsp;ENROLLMENT</button>
                                                @endif
                                                <a class="btn btn-sm btn-info" href="{{ route('a.viewadmission', ['enrol_id' => $trainee->enroledid]) }}" target="_blank">ADMISSION SLIP</a>

                                                @if($trainee->paymentmodeid === 3 || $trainee->paymentmodeid === 4)
                                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" wire:click="editPackage({{ $trainee->enroledid }})" data-bs-target="#editpackagemodal">PACKAGE</button>
                                                @endif

                                                @if($trainee->passid == 0)
                                                <button class="btn btn-sm btn-danger" wire:click.prevent="confirmdrop({{ $trainee->enroledid }})" data-bs-toggle="modal" data-bs-target="#generateModalDrop">DROP</button>

                                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#archiveModal" wire:click="confirmdelete({{ $trainee->enroledid }})">
                                                    <i class="bi bi-archive"></i> Delete request
                                                </button>
                                                @elseif($trainee->pendingid == 1)
                                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#archiveModal" wire:click="confirmdelete({{ $trainee->enroledid }})">
                                                    <i class="bi bi-archive"></i> Delete request
                                                </button>
                                                @endif
                                                @endif

                                                @if (Auth::user()->u_type == 2)
                                                @if ($trainee->passid == 1)
                                                <button class="btn btn-sm btn-info" wire:click="TPER_form({{$trainee->enroledid}})">TPER</button>
                                                @endif
                                                @endif
                                            </td>

                                            @endforeach
                                            @else
                                            <td colspan="9" class="text-center">
                                                <p>--- NO TRAINEES FOUND ---</p>
                                            </td>
                                            @endif
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="card-footer">
                                    @if ($trainees)
                                    {{ $trainees->links('livewire.components.customized-pagination-link') }}
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                    @if (Auth::user()->u_type == 1)
                    <br>
                    <h3>Pending Trainees</h1>
                        <hr>
                        <div class="card">
                            <div class="table-responsive">
                                <table class="table table-sm text-nowrap mb-0 table-centered">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Profile</th>
                                            <th scope="col">First Name</th>
                                            <th scope="col">Middle Name</th>
                                            <th scope="col">Last Name</th>
                                            <th scope="col">Suffix</th>
                                            <th scope="col">Rank</th>
                                            <th scope="col">Birthday</th>
                                            <th scope="col">Company</th>
                                            @if (Auth::user()->u_type == 1)
                                            <th scope="col">Mode of Payment</th>
                                            @endif
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($pending_trainees->count())
                                        @foreach ($pending_trainees as $trainee)
                                        <tr>
                                            <td>
                                                <span>
                                                    <small>
                                                        <i>#{{ $trainee->enroledid }}</i>
                                                    </small>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="position-relative">
                                                        <img src="{{ asset('assets/images/avatar/avatar.jpg') }}" alt="" class="rounded-circle avatar-md me-2">
                                                        <a href="#" class="position-absolute mt-5 ms-n4">
                                                            <span class="status bg-success"></span>
                                                        </a>
                                                    </div>
                                                    <!-- <h5 class="mb-0 text-uppercase @if ($trainee->attendance_status == 1) text-danger @endif @if ($trainee->IsRemedial == 1) text-warning @endif">
                                                        {{ $trainee->trainee->formal_name() }} @if ($trainee->IsRemedial == 1) (Remedial) @endif
                                                    </h5> -->
                                                </div>
                                            </td>

                                            <td>
                                                <small class="fs-4">{{ $trainee->f_name }}</small>
                                            </td>
                                            <td>
                                                <small class="fs-4">{{ $trainee->m_name }}</small>
                                            </td>

                                            <td>
                                                <small class="fs-4">{{ $trainee->l_name }}</small>
                                            </td>

                                            <td>
                                                <small class="fs-4">{{ $trainee->suffix }}</small>
                                            </td>

                                            <td>
                                                {{ $trainee->trainee->rank->rankacronym }}
                                            </td>
                                            <td>
                                                {{ date('F d, Y', strtotime($trainee->birthday)) }}
                                            </td>
                                            <td>
                                                {{ $trainee->trainee->company->company }}
                                            </td>

                                            @if (Auth::user()->u_type == 1)
                                            <td>
                                                @if($trainee->paymentmodeid == 1)
                                                <small class="fs-4">Company Sponsored</small>
                                                @elseif($trainee->paymentmodeid == 2)
                                                <small class="fs-4">Own Pay</small>
                                                @elseif($trainee->paymentmodeid == 3)
                                                <small class="fs-4">Salary Deduction</small>
                                                @elseif($trainee->paymentmodeid == 4)
                                                <small class="fs-4">NTIF Boarding loan</small>
                                                @endif
                                            </td>
                                            @endif

                                            <td>
                                                @if (Auth::user()->u_type == 1)
                                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#archiveModal" wire:click="confirmdelete({{ $trainee->enroledid }})">
                                                    <i class="bi bi-archive"></i> Delete request
                                                </button>
                                                @endif
                                            </td>

                                            @endforeach
                                            @else
                                            <td colspan="9" class="text-center">
                                                <p>--- NO TRAINEES FOUND ---</p>
                                            </td>
                                            @endif
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="card-footer">
                                    @if ($trainees)
                                    {{ $trainees->links('livewire.components.customized-pagination-link') }}
                                    @endif
                                </div>

                            </div>
                        </div>
                        @endif
                </div>

                @if (Auth::user()->u_type == 1)

                <livewire:admin.certificate.a-certificate-status-component training_id="{{ $schedule->scheduleid}}">

                    <livewire:admin.widget.a-display-last-serial-number-component />

                    @endif

                    <div class="col-lg-12 col-md-12 col-12">
                        <livewire:admin.reports.batch.attendance training_id="{{ $schedule->scheduleid }}" />
                    </div>
            </div>
        </div>
        <div wire:ignore.self class="modal fade" id="editpackagemodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel" wire:model.defer="title">EDIT PACKAGE</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="setPassed" id="setPassed">
                            @csrf
                            <div class="row gx-3">
                                <div class="alert alert-success d-flex align-items-center" role="alert">
                                    <div>
                                        Check and verified the price of the package.
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Select Package: </label>
                                        <select class="form-select form-select text-dark" wire:model.lazy="t_fee_package">
                                            @foreach ($this->packages as $key => $package)
                                            <option value="{{ $key }}">
                                                Package {{ $key }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Select Room: </label>
                                        <select class="form-select form-select text-dark" wire:model.lazy="selected_dorm">
                                            @foreach ($this->room_type as $d)
                                            <option value="{{ $d->dormid }}">
                                                {{ $d->dorm }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Total Price: </label>
                                        <input type="text" class="form-control form-control text-dark" wire:model="total" disabled>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="generateModalDrop" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel" wire:model.defer="title">Confirmation of Drop</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="drop" id="drop">
                            <div class="row gx-3">
                                <div class="col-md-12 col-12">
                                    <div class="alert alert-danger">
                                        Are you sure you want to mark the record as dropped? Click "Proceed" to confirm and
                                        process the drop action.
                                    </div>
                                </div>
                                <div class="col-md-12 col-12">
                                    <label for="">Add a reason why you drop the trainee:</label>
                                    <textarea class="form-control" name="" id="" cols="30" rows="10" wire:model.defer="reason"></textarea>
                                    @error('reason')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" form="drop" class="btn btn-primary">Proceed</button>
                    </div>
                </div>
            </div>
        </div>

        @include('livewire.admin.enrollment.a-edit-enrollment-modal');
    </div>
</section>

<script>
    function generateCertificate() {
        if (confirm('Are you sure you want to generate it?')) {
            window.open(this.url, '_blank');
            // If using Livewire, you might call Livewire method here
            // Example: Livewire.emit('generateCertificate', this.url);
        }
    }

    function sendApproval(scheduleid) {
        if (confirm('Are you sure you want to send it?')) {
            Livewire.emit('sendApproval', scheduleid);
        }
    }
</script>