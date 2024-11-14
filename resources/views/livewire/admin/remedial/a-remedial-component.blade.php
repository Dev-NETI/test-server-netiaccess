<section class="container-fluid p-4">
    <span wire:loading>
        <livewire:components.loading-screen-component />
    </span>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <!-- Page Header -->
            <div class="border-bottom pb-3 mb-3 d-md-flex align-items-center justify-content-between">
                <div class="mb-3 mb-md-0">
                    <h1 class="mb-1 h2 fw-bold">Assign Remedial Schedule</h1>
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{route('a.remedial')}}">Enrollment</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Assign remedial Schedule
                            </li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <!-- <a href="../add-course.html" class="btn btn-primary">Add New Courses</a> -->
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <!-- Card -->
            <div class="card rounded-3">
                <!-- Card header -->
                <div class="card-header p-0">
                    <div>
                        <!-- Nav -->
                        <ul class="nav nav-lb-tab  border-bottom-0 " id="tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="courses-tab" data-bs-toggle="pill" href="#courses" role="tab" aria-controls="courses" aria-selected="true">All ({{number_format($count_enroll)}})</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="p-4 row">
                    <!-- Form -->
                    <div class="row">
                        <div class="d-flex align-items-center col-12 col-md-12 col-lg-12 mb-3">
                            <span class="position-absolute ps-3 search-icon"><i class="fe fe-search"></i></span>
                            <input type="search" class="form-control ps-6" wire:model.debounce.3000ms="search" placeholder="Search Name..">

                        </div>
                        <div class="col-md-4 mb-3">
                            <select class="form-select" wire:model="selected_course">
                                <option value="">Select a course</option>
                                @foreach ($courses as $course)
                                <option value="{{$course->courseid}}">{{$course->coursecode}} - {{$course->coursename}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <select name="selected_stat" class="form-select" wire:model="selected_batch">
                                <option value="">Select a Batch Week</option>
                                @foreach ($batchWeeks as $week)
                                <option value="{{$week->batchno}}">{{$week->batchno}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 md-3">
                            <button type="button" class="btn btn-primary float-end" onclick="refreshPage()">Refresh</button>
                        </div>
                    </div>

                </div>
                <div>
                    <!-- Table -->
                    <div class="tab-content" id="tabContent">
                        <!--Tab pane -->
                        <div class="tab-pane fade show active" id="courses" role="tabpanel" aria-labelledby="courses-tab">
                            <div class="table-responsive border-0 overflow-y-hidden">
                                <table class="table mb-0 text-nowrap table-centered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">
                                                ENROLLMENT APPLICATION
                                            </th>
                                            <th scope="col">
                                                REQUESTED BY
                                            </th>
                                            <th scope="col">
                                                STATUS
                                            </th>
                                            <th scope="col">
                                                ACTION
                                            </th>
                                            <th scope="col"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($all_enroll->count())
                                        @foreach ($all_enroll as $enroll)
                                        <tr wire:key="enroll-{{ $enroll->enroledid }}">
                                            <td>
                                                <a class="text-inherit">
                                                    <div class="d-flex align-items-center">
                                                        <div class="ms-3">
                                                            <h4 class="mb-1 text-primary-hover">
                                                                <i class="mdi mdi-file-document" id="{{ $enroll->enroledid }}"></i> {{$enroll->course->coursecode}} - {{$enroll->course->coursename}}
                                                            </h4>
                                                            <small class="text-muted">Prev. Training schedule:
                                                                @if ($enroll->old_schedule)
                                                                {{date('d F, Y', strtotime($enroll->old_schedule->schedule->startdateformat))}} - {{date('d F, Y', strtotime($enroll->old_schedule->schedule->enddateformat))}}
                                                                @else
                                                                <small class="text-danger">Not specified</small>
                                                                @endif
                                                            </small>
                                                            <br>
                                                            <small class="text-muted"> Payment type: {{$enroll->payment->paymentmode}}</small>
                                                            <br>
                                                            <small class="text-muted"> Added on {{date('d F, Y', strtotime($enroll->created_at))}}</small>
                                                        </div>
                                                    </div>
                                                </a>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{asset('assets/images/avatar/avatar.jpg')}}" alt="" class="rounded-circle avatar-xs me-2">
                                                    <h5 class="mb-0" style="text-transform: uppercase;">{{$enroll->trainee->formal_name()}}</h5>
                                                </div>
                                            </td>
                                            <td>
                                                @if($enroll->passid == 0 && $enroll->IsRemedial == 1)
                                                <span class="badge-dot bg-warning me-1 d-inline-block align-middle"></span><small>Remedial</small>
                                                @elseif($enroll->passid == 1)
                                                <span class="badge-dot bg-success me-1 d-inline-block align-middle"></span><small>Passed</small>
                                                @else
                                                <span class="badge-dot bg-failed me-1 d-inline-block align-middle"></span><small>Passed</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($enroll->passid != 1)
                                                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal_file" wire:click="assign({{$enroll->enroledid}})">ASSIGN</button>
                                                @endif
                                                <a href="{{route('a.view-batch', $enroll->scheduleid)}}" class="btn btn-info btn-sm">VIEW</a>
                                                @if ($enroll->course->type->coursetypeid == 1)
                                                <a href="{{route('a.solo-ccr-pdf', $enroll->enroledid)}}" target="_blank" class="btn btn-warning btn-sm">CCR</a>
                                                <a href="{{route('a.tcroa-solo', $enroll->enroledid)}}" target="_blank" class="btn btn-danger btn-sm">TCROA</a>
                                                @endif

                                                @if($enroll->passid == 1)
                                                <a href="{{route('a.cert-solo', $enroll->enroledid)}}" target="_blank" class="btn btn-primary btn-sm">CERTIFICATE</a>
                                                @endif
                                                <!-- <a href="{{route('a.r.attendance',['enrol_id' => $enroll->enroledid])}}" target="_blank" class="btn btn-secondary btn-sm">REMEDIAL REPORT</a> -->

                                                <div wire:ignore.self class="modal fade" id="modal_file" tabindex="-1" role="dialog" aria-labelledby="modal_file" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="modal_file">ASSIGN TRAINING DATE</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form wire:submit.prevent="save_assign" enctype="multipart/form-data">
                                                                    @csrf
                                                                    <div class="mb-3">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <p class="mb-1 text-dark">The selected course is: </p>
                                                                            </div>
                                                                            <div class="col-12 mb-3">
                                                                                <small style="color: red;"><i>({{$ass_course}})</i></small>
                                                                            </div>
                                                                        </div>
                                                                        <div class="input-group mb-1">
                                                                            <div class="mb-3 col-12 col-md-12">
                                                                                <select class="form-control" wire:model="selected_schedule">
                                                                                    <option value="">Select Schedule</option>
                                                                                    @if($schedules)
                                                                                    @foreach ($schedules as $schedule)
                                                                                    <option value="{{ $schedule->scheduleid }}">
                                                                                        {{ $schedule->batchno }} :
                                                                                        {{ date('F d, Y', strtotime($schedule->startdateformat)) }} -
                                                                                        {{ date('F d, Y', strtotime($schedule->enddateformat)) }}
                                                                                    </option>
                                                                                    @endforeach
                                                                                    @endif
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-12 col-md-12">

                                                                            </div>
                                                                        </div>

                                                                        @error('file') <small class="text-muted">{{$message}} </small>@enderror
                                                                    </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <div class="float-end">
                                                                    <button class="btn btn-success" wire:click="save_assign">Update the schedule</button>
                                                                </div>
                                                                <button type=" button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            </div>
                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <!-- <span class="dropdown dropstart">
                                                    <a class="btn-icon btn btn-ghost btn-sm rounded-circle" href="#" role="button" id="courseDropdown1" data-bs-toggle="dropdown" data-bs-offset="-20,20" aria-expanded="false">
                                                        <i class="fe fe-more-vertical"></i>
                                                    </a>
                                                    <span class="dropdown-menu" aria-labelledby="courseDropdown1">
                                                        <span class="dropdown-header">Settings</span>
                                                        <button class="dropdown-item" wire:click.prevent="confirmdelete({{$enroll->enroledid}})">
                                                            <i class="fe fe-trash dropdown-item-icon"></i>Delete
                                                        </button>

                                                        <button class="dropdown-item" wire:click.prevent="confirmdrop({{$enroll->enroledid}})">
                                                            <i class="fe fe-x dropdown-item-icon"></i>Drop
                                                        </button>
                                                    </span>
                                                </span> -->
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td class="text-center" colspan="6">
                                                ----NO RECORD FOUND----
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer">
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination justify-content-center mb-0">
                                        {{$all_enroll->links()}}
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    function refreshPage() {
        window.location.reload();
    };
</script>
@endpush