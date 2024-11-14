<section class="container-fluid p-4">
    <div class="row">
        <section class="container-fluid p-4">
            <div class="row">
                <!-- Page Header -->
                <div class="col-lg-12 col-md-12 col-12">
                    <div class="border-bottom pb-3 mb-3 d-md-flex align-items-center justify-content-between">
                        <div class="mb-3 mb-md-0">
                            <h1 class="mb-1 h2 fw-bold">
                                My Class
                                <!-- <span class="fs-5">({{$courses->count()}})</span> -->
                            </h1>
                            <!-- Breadcrumb -->
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('i.dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Dashboard
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <div>
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
                            <!-- <form class="d-flex align-items-center">
                                <span class="position-absolute ps-3 search-icon">
                                    <i class="fe fe-search"></i>
                                </span>
                                <input type="search" class="form-control ps-6" placeholder="Search Courses">
                            </form> -->
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
                                            <a href="{{route('i.view-batch',['training_id' => $course->scheduleid])}}" class="text-inherit">
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        @if ($course->course->coursetypeid == 1)
                                                        <img src="{{ asset('assets/images/oesximg/mandatory-course-card.jpg') }}" alt="course" class="img-4by3-lg rounded">
                                                        @elseif ($course->course->coursetypeid == 2)
                                                        <img src="{{ asset('assets/images/oesximg/upgrading-course-card.jpg') }}" alt="course" class="img-4by3-lg rounded">
                                                        @elseif ($course->course->coursetypeid == 3)
                                                        <img src="{{ asset('assets/images/oesximg/nmc-course-card.jpg') }}" alt="course" class="img-4by3-lg rounded">
                                                        @elseif ($course->course->coursetypeid == 4)
                                                        <img src="{{ asset('assets/images/oesximg/nmcr-course-card.jpg') }}" alt="course" class="img-4by3-lg rounded">
                                                        @elseif ($course->course->coursetypeid == 5)
                                                        <img src="{{ asset('assets/images/oesximg/jiss-course-card.jpg') }}" alt="course" class="img-4by3-lg rounded">
                                                        @elseif ($course->course->coursetypeid == 7)
                                                        <img src="{{ asset('assets/images/oesximg/pjmcc-course-card.jpg') }}" alt="course" class="img-4by3-lg rounded">
                                                        @else
                                                        @endif
                                                    </div>
                                                    <div class="ms-3">
                                                        <h4 class="mb-1 text-primary-hover">
                                                            {{$course->course->coursecode}} - {{$course->course->coursename}}
                                                        </h4>
                                                        <span class="text-muted">{{ date('M d, Y', strtotime($course->startdateformat)) }} - {{ date('M d, Y', strtotime($course->enddateformat)) }}</span>
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
                                                    <a class="dropdown-item" href="{{route('i.view-batch',['training_id' => $course->scheduleid])}}"><i class="fe fe-layers dropdown-item-icon"></i>View Course Details</a>
                                                </span>
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="row">
                                        @if ($courses)
                                        {{ $courses->links('livewire.components.customized-pagination-link')}}
                                        @endif
                                    </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
</section>