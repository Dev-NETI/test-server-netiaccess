<section class="mt-3">
    <div class="container my-6">
        <div class="row">
            <!-- Page Header -->
            <div class="col-lg-12 col-md-12 col-12">
                <div class="border-bottom pb-3 mb-3 d-flex justify-content-between align-items-center">
                    <div class="mb-2 mb-lg-0">
                        <h1 class="mb-1 h2 fw-bold">
                            My courses
                        </h1>
                        <!-- Breadcrumb  -->
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="../dashboard/admin-dashboard.html">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    My Courses
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="alert alert-info">
                    <h4>BANK DETAILS: <i>(For own payment purpose)</i></h4>
                    Bank Name: {{$billing->bankname}} <br>
                    Account Holder's Name: {{$billing->accountname}} <br>
                    Account Number: {{$billing->accountnumber}} <br>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-12">
                <!-- Card -->
                <div class="card mb-4">
                    <!-- Card body -->
                    <div class="card-body">
                        <!-- Form -->
                        <form class="row gx-3">
                            @csrf
                            <div class="col-lg-7 col-md-7 col-12 mb-lg-0 mb-2">
                            </div>
                            <div class="col-lg-5 float-end">
                                <label for="" class="form-label pt-2">Search:</label>
                                <input type="text" placeholder="Search course .." wire:model="search" class="form-control">
                            </div>
                        </form>
                    </div>
                    <!-- Table -->
                    <div class="table-responsive overflow-y-hidden">
                        <table class="table mb-0 text-nowrap table-hover table-centered text-nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Courses</th>
                                    <th scope="col">Training Date</th>
                                    <th scope="col">Payment Mode</th>
                                    <th scope="col">Status</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($my_courses->count())
                                @foreach ($my_courses as $course)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <a href="{{ route('t.coursedetails', ['regis' => $course->enroledid]) }}">

                                                    @if ($course->coursetypeid == 1)
                                                    <img src="{{ asset('assets/images/oesximg/mandatory-course-card.jpg') }}" alt="course" class="rounded img-4by3-lg">
                                                    @elseif ($course->coursetypeid == 2)
                                                    <img src="{{ asset('assets/images/oesximg/upgrading-course-card.jpg') }}" alt="course" class="rounded img-4by3-lg">
                                                    @elseif ($course->coursetypeid == 3)
                                                    <img src="{{ asset('assets/images/oesximg/nmc-course-card.jpg') }}" alt="course" class="rounded img-4by3-lg">
                                                    @elseif ($course->coursetypeid == 4)
                                                    <img src="{{ asset('assets/images/oesximg/nmcr-course-card.jpg') }}" alt="course" class="rounded img-4by3-lg">
                                                    @elseif ($course->coursetypeid == 5)
                                                    <img src="{{ asset('assets/images/oesximg/jiss-course-card.jpg') }}" alt="course" class="rounded img-4by3-lg">
                                                    @elseif ($course->coursetypeid == 7)
                                                    <img src="{{ asset('assets/images/oesximg/pjmcc-course-card.jpg') }}" alt="course" class="rounded img-4by3-lg">
                                                    @else
                                                    <!-- Add a default image or handle other cases as needed -->
                                                    @endif
                                            </div>
                                            <div class="ms-3">
                                                <h4 class="mb-1 h5">
                                                    <a href="{{ route('t.coursedetails', ['regis' => $course->enroledid]) }}" class="text-inherit">
                                                        {{$course->course->coursecode}} - {{$course->course->coursename}}
                                                    </a>
                                                </h4>
                                                <ul class="list-inline fs-6 mb-0">
                                                    <li class="list-inline-item">
                                                        <i>Registration #: {{$course->enroledid}}</i>
                                                    </li>
                                                    <li class="list-inline-item">
                                                        <i class="mdi mdi-clock-time-four-outline text-muted me-1">Mode of Delivery: {{$course->course->mode->modeofdelivery}}</i>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {{date('d F Y', strtotime($course->schedule->startdateformat))}} - {{date('d F Y', strtotime($course->schedule->enddateformat))}}
                                    </td>
                                    <td>
                                        {{$course->payment->paymentmode}}
                                    </td>
                                    <td>
                                        @if ($course->pendingid == 1)
                                        <span class="badge bg-warning">Pending</span>
                                        @elseif ($course->pendingid == 0 && $course->passid == 1)
                                        <span class="badge bg-success">Completed</span>
                                        @elseif ($course->pendingid == 0 && $course->passid == 2)
                                        <span class="badge bg-danger">Failed</span>
                                        @elseif ($course->pendingid == 0)
                                        <span class="badge bg-success">Enrolled</span>
                                        @elseif ($course->pendingid == 2)
                                        <span class="badge bg-danger">Dropped</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="dropdown dropstart">
                                            <a class="btn-icon btn btn-ghost btn-sm rounded-circle " href="#" role="button" id="courseDropdown" data-bs-toggle="dropdown" data-bs-offset="-20,20" aria-expanded="false">
                                                <i class="fe fe-more-vertical"></i>
                                            </a>
                                            <span class="dropdown-menu" aria-labelledby="courseDropdown">
                                                <span class="dropdown-header">Setting </span>
                                                @if ($course->pendingid == 0 && $course->passid == 0)
                                                <a class="dropdown-item" wire:click.prevent="goToLMS({{$course->schedule->scheduleid}})"><i class="fe fe-book dropdown-item-icon"></i>Go to LMS</a>

                                                @if ($course->paymentmodeid == 3)
                                                {{-- <a class="dropdown-item" href="{{ route('view-atd', ['registration' => $course->enroledid]) }}" target="_blank"><i class="fe fe-file dropdown-item-icon"></i>Generate ATD/SLAF</a> --}}
                                                @elseif($course->paymentmodeid == 4)
                                                {{-- <a class="dropdown-item" href="{{ route('view-sd', ['registration' => $course->enroledid]) }}" target="_blank"><i class="fe fe-file dropdown-item-icon"></i>Generate ATD/SLAF</a> --}}
                                                @endif
                                                @if ($course->pendingid == 0)
                                                <a class="dropdown-item" href="{{ route('t.viewadmission', ['enrol_id' => $course->enroledid]) }}" target="_blank"><i class="fe fe-file dropdown-item-icon"></i>Generate Admission slip</a>
                                                @endif
                                                @endif
                                                <a class="dropdown-item" href="{{ route('t.coursedetails', ['regis' => $course->enroledid]) }}"><i class="fe fe-edit dropdown-item-icon"></i>View Course Details</a>
                                                <!-- <a class="dropdown-item" wire:click.prevent="confirm_delete_attendance({{$course->enroledid}})"><i class="fe fe-trash dropdown-item-icon"></i>Remove</a> -->
                                            </span>
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr class="text-center">
                                    <td colspan="8">-----No Record Found-----</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="float-end pt-3">
                                {{ $my_courses->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>