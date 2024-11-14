<section class="container-fluid p-4">
    <div class="row">
        <!-- Page Header -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-flex justify-content-between align-items-center">
                <div class="mb-2 mb-lg-0">
                    <h1 class="mb-1 h2 fw-bold">
                        List of Special Training Schedule
                    </h1>
                    <!-- Breadcrumb  -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{route('a.dashboard')}}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item">Training Calendar<a href="#"></a></li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Special Training Schedule
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- basic table -->
        <div class="col-md-12 col-12 mb-5">
            <div class="card">
                <!-- card header  -->
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-5">
                            <h4 class="mb-1">List of Special Training Schedule</h4>
                        </div>
                        <div class="col-lg-3 text-end">
                            <label for="" class="form-label pt-2">Search:</label>
                        </div>
                        <div class="col-lg-4 float-end">
                            <input type="text" placeholder="search in code & name .." wire:model="search" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <!-- text align -->
                    <table class="table table-sm text-nowrap mb-0 table-centered" width="100%" wire:loading.table>
                        <thead>
                            <tr>
                                <th>Course Type</th>
                                <th>Course Code</th>
                                <th>Course Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody class="" style="font-size: 11px;">
                            <tr class="text-center">
                                <td colspan="6">-----Processing data-----</td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="table table-sm text-nowrap mb-0 table-centered" width="100%" wire:loading.remove>
                        <thead>
                            <tr>
                                <th>Course Type</th>
                                <th>Course Code</th>
                                <th>Course Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody class="" style="font-size: 11px;">
                            @if ($courses->count())
                            @foreach ($courses as $course)
                            <tr>
                                @if ($course->type->coursetype)
                                <td>{{ strtoupper($course->type->coursetype)}}</td>
                                @else
                                <td>--------------</td>
                                @endif
                                @if ($course->coursecode)
                                <td>{{ strtoupper($course->coursecode)}}</td>
                                @else
                                <td>--------------</td>
                                @endif
                                @if ($course->coursename)
                                <td>{{strtoupper($course->coursename)}}</td>
                                @else
                                <td>--------------</td>
                                @endif
                                <td>
                                    <a href="{{ route('a.specialcalendarshow', ['course_id' => $course->courseid]) }}" class="btn btn-warning btn-sm">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr class="text-center">
                                <td colspan="6">-----No Records Found-----</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="card-footer">
                        <div class="row">
                            {{ $courses->links('livewire.components.customized-pagination-link')}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>