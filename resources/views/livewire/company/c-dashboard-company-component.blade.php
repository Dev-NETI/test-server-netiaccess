<section class="container-fluid p-4">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-lg-flex justify-content-between align-items-center">
                <div class="mb-3 mb-lg-0">
                    <h1 class="mb-0 h2 fw-bold">WELCOME TO NYK-FIL MARITIME E-TRAINING, INC.</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-12 col-12">
            <!-- Card -->
            <div class="card mb-4">
                <!-- Card body -->
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3 lh-1">
                        <div>
                            <span class="fs-6 text-uppercase fw-semibold">Trainees</span>
                        </div>
                        <div>
                            <span class=" fe fe-users fs-3 text-primary"></span>
                        </div>
                    </div>
                    <h2 class="fw-bold mb-1">
                        {{number_format($trainees->count())}}
                    </h2>
                    <!-- <span class="text-success fw-semibold"><i class="fe fe-trending-up me-1"></i>+1200</span>
                    <span class="ms-1 fw-medium">Students</span> -->
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-6 col-md-12 col-12">
            <!-- Card -->
            <div class="card mb-4">
                <!-- Card body -->
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3 lh-1">
                        <div>
                            <span class="fs-6 text-uppercase fw-semibold">Courses</span>
                        </div>
                        <div>
                            <span class=" fe fe-book-open fs-3 text-primary"></span>
                        </div>
                    </div>
                    <h2 class="fw-bold mb-1">
                        {{$courses->count()}}
                    </h2>
                    <!-- <span class="text-danger fw-semibold">120+</span>
                    <span class="ms-1 fw-medium">Number of pending</span> -->
                </div>
            </div>
        </div>

        <section class="container-fluid p-4">
            <div class="row">
                <!-- Page Header -->
                <div class="col-lg-12 col-md-12 col-12">
                    <div class="border-bottom pb-3 mb-3 d-md-flex align-items-center justify-content-between">
                        <div class="mb-3 mb-md-0">
                            <h1 class="mb-1 h2 fw-bold">
                                Available courses
                                <span class="fs-5">({{$courses->count()}} Courses)</span>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-12">
                    <!-- Card -->
                    <div class="card mb-4">
                        <!-- Card Header -->
                        <div class="card-header border-bottom-0">
                            <form class="d-flex align-items-center">
                                @csrf
                                <span class="position-absolute ps-3 search-icon">
                                    <i class="fe fe-search"></i>
                                </span>
                                <input type="search" class="form-control ps-6" placeholder="Search Courses">
                            </form>
                        </div>
                        <!-- Table  -->
                        <div class="table-responsive border-0 overflow-y-hidden">
                            <table class="table mb-0 text-nowrap table-hover table-centered">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">COURSES</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($courses as $course)
                                    <tr>
                                        <td>
                                            <a href="{{route('c.calendarshow',['course_id' => $course->course->courseid])}}" class="text-inherit">
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <img src="{{ asset('assets/images/oesximg/nmc-course-card.jpg') }}" alt="" class="img-4by3-lg rounded">
                                                    </div>
                                                    <div class="ms-3">
                                                        <h4 class="mb-1 text-primary-hover">
                                                            {{$course->course->coursecode}} - {{$course->course->coursename}}
                                                        </h4>
                                                        <span class="text-muted">Added on 7 July, 2023</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </td>

                                        <td>
                                            <span class="dropdown dropstart">
                                                <a class="btn-icon btn btn-ghost btn-sm rounded-circle" href="#" role="button" id="courseDropdown1" data-bs-toggle="dropdown" data-bs-offset="-20,20" aria-expanded="false">
                                                    <i class="fe fe-more-vertical"></i>
                                                </a>
                                                <span class="dropdown-menu" aria-labelledby="courseDropdown1">
                                                    <span class="dropdown-header">Settings</span>
                                                    <a class="dropdown-item" href="{{route('c.calendarshow',['course_id' => $course->course->courseid])}}"><i class="fe fe-layers dropdown-item-icon"></i>View Schedule</a>
                                                </span>
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
</section>