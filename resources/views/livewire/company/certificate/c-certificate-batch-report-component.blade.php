<section class="container-fluid p-4">
    <span wire:loading>
        <livewire:components.loading-screen-component />
    </span>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <!-- Page Header -->
            <div class="border-bottom pb-3 mb-3 d-md-flex align-items-center justify-content-between">
                <div class="mb-3 mb-md-0">
                    <h1 class="mb-1 h2 fw-bold">Certificate Viewer</h1>
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                Certificate
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                View
                            </li>
                        </ol>
                    </nav>
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
                        @if (Auth::user()->company_id != 1)
                        <div class="col-md-3 mb-3">
                            <select class="form-select" wire:model="selected_course_type">
                                <option value="">Select a course type</option>
                                @foreach ($course_type as $type)
                                <option value="{{$type->coursetypeid}}">{{optional($type)->coursetype}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <select class="form-select" wire:model="selected_course">
                                <option value="">Select a course</option>
                                @foreach ($courses as $course)
                                <option value="{{$course->courseid}}">{{optional($course)->coursecode}} - {{optional($course)->coursename}}</option>
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
                        @else
                        <div class="col-md-3 mb-3">
                            <select name="selected_stat" class="form-select" wire:model="selected_fleet">
                                <option value="0">Select a by Fleet</option>
                                @foreach ($loadfleet as $fleet)
                                <option value="{{$fleet->fleetid}}">{{$fleet->fleet}}</option>
                                @endforeach
                                <option value="1">Others</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <select class="form-select" wire:model="selected_course_type">
                                <option value="">Select a course type</option>
                                @foreach ($course_type as $type)
                                <option value="{{$type->coursetypeid}}">{{optional($type)->coursetype}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <select class="form-select" wire:model="selected_course">
                                <option value="">Select a course</option>
                                @foreach ($courses as $course)
                                <option value="{{$course->courseid}}">{{optional($course)->coursecode}} - {{optional($course)->coursename}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <select name="selected_stat" class="form-select" wire:model="selected_batch">
                                <option value="">Select a Batch Week</option>
                                @foreach ($batchWeeks as $week)
                                <option value="{{$week->batchno}}">{{$week->batchno}}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
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
                                                NAME
                                            </th>
                                            @if (Auth::user()->company_id == 1)
                                            <th scope="col">
                                                FLEET
                                            </th>
                                            @endif
                                            <th scope="col">
                                                STATUS
                                            </th>
                                            <th scope="col">
                                                ACTION
                                            </th>
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
                                                                <i class="mdi mdi-file-document"></i>{{optional($enroll->course)->coursecode}} - {{optional($enroll->course)->coursename}}
                                                            </h4>
                                                            @if ($enroll->schedule)
                                                            <small class="text-muted">Training schedule: {{date('d F, Y', strtotime($enroll->schedule->startdateformat))}} - {{date('d F, Y', strtotime($enroll->schedule->enddateformat))}}</small>
                                                            @endif
                                                            <br>
                                                            <small class="text-muted"> Payment type: {{$enroll->payment->paymentmode}}</small>
                                                            <br>
                                                            @if (optional($enroll->trainee)->company)
                                                            <small class="text-muted"> Company: {{$enroll->trainee->company->company}}</small>
                                                            @else
                                                            <small class="text-muted"> Company: <span class="text-danger">Undefined</span></small>
                                                            @endif
                                                            <br>
                                                            @if(optional($enroll->certificate_history)->certificatehistoryid)
                                                            <small class="text-muted"> Reference #: <span class="text-muted"> {{$enroll->enroledid}}-{{optional($enroll->certificate_history)->certificatehistoryid ? $enroll->certificate_history->certificatehistoryid : ''}}</span></small>
                                                            @else
                                                            <small class="text-muted"> Reference #: <span class="text-muted"> -----------</span></small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </a>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{asset('assets/images/avatar/avatar.jpg')}}" alt="" class="rounded-circle avatar-xs me-2">
                                                    @if ($enroll->trainee)
                                                    <h5 class="mb-0" style="text-transform: uppercase;">{{$enroll->trainee->f_name}} {{$enroll->trainee->l_name}}</h5>
                                                    @endif
                                                </div>
                                            </td>
                                            @if (Auth::user()->company_id == 1)
                                            <td>
                                                <h4 class="mb-1 text-primary-hover">
                                                    {{ optional($enroll->fleet)->fleet}}
                                                </h4>
                                            </td>
                                            @endif
                                            <td>
                                                @if ($enroll->passid == 1 && optional($enroll->certificate_history)->is_approve == 1)
                                                <span class="badge-dot bg-success me-1 d-inline-block align-middle"></span><small>APPROVED</small>
                                                @elseif ($enroll->passid == 1 && optional($enroll->certificate_history)->is_approve == 0 && optional($enroll->certificate_history)->certificate_path)
                                                <span class="badge-dot bg-warning me-1 d-inline-block align-middle"></span><small>PENDING (Need to export) </small>
                                                @elseif ($enroll->passid == 1 && optional($enroll->certificate_history)->is_approve == 0)
                                                <span class="badge-dot bg-warning me-1 d-inline-block align-middle"></span><small>PENDING</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if(optional($enroll->course->type)->coursetypeid === 3 || optional($enroll->course->type)->coursetypeid === 4)
                                                @if($enroll->passid == 1 && $enroll->certificate_history && optional($enroll->certificate_history)->is_approve === 1 && optional($enroll->certificate_history)->is_released === 1)
                                                <button class="btn btn-info btn-sm" wire:click='certificate({{$enroll->enroledid}})'> <i class="fe fe-file-text text-white"></i>View Certificate</button>
                                                @endif
                                                @endif

                                                @php
                                                $createdAt = \Carbon\Carbon::parse($enroll->schedule->enddateformat);
                                                $startDate = $createdAt->copy()->startOfDay();
                                                $endDate = $startDate->copy()->addYear();
                                                $currentDate = \Carbon\Carbon::now();
                                                $startDisplayDate = \Carbon\Carbon::create(2024, 8, 1)->startOfDay();

                                                $implementeDate = $createdAt->GreaterThanOrEqualTo($startDisplayDate) ;
                                                $shouldDisplay = $currentDate->between($startDate, $endDate);

                                                @endphp

                                                @if(optional(optional($enroll->course)->type)->coursetypeid == 7 || optional(optional($enroll->course)->type)->coursetypeid == 2)
                                                @if($enroll->passid == 1 && $enroll->certificate_history && optional($enroll->certificate_history)->is_approve === 1 && optional($enroll->certificate_history)->is_released === 1)
                                                <button class="btn btn-info btn-sm" wire:click='certificate({{$enroll->enroledid}})'>View Certificate</button>
                                                @endif
                                                @endif
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
                                                            Are you sure you want to mark the record as dropped? Click "Proceed" to confirm and process the drop action.
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 col-12">
                                                        <label for="">Add a reason why you drop the trainee:</label>
                                                        <textarea class="form-control" name="" id="" cols="30" rows="10" wire:model.defer="reason"></textarea>
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