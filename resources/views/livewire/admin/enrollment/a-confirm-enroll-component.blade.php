<section class="container-fluid p-4">
    <span wire:loading>
        <livewire:components.loading-screen-component />
    </span>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <!-- Page Header -->
            <div class="border-bottom pb-3 mb-3 d-md-flex align-items-center justify-content-between">
                <div class="mb-3 mb-md-0">
                    <h1 class="mb-1 h2 fw-bold">Confirm Enrollment Application</h1>
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{route('a.confirmenroll')}}">Enrollment</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Confirm Enrollment Application
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
        <div class="col-md-6 col-xl-4 col-12">
            <!-- card  -->
            <div class="card mb-4">
                <!-- card body  -->
                <div class="card-body">
                    <div class="d-flex justify-content-between
                    align-items-center">
                        <div>
                            <h4 class="mb-0">ENROLLED</h4>
                        </div>
                        <!-- dropdown  -->
                        <div>
                            <span class="dropdown dropstart">
                                <a class="btn-icon btn btn-ghost btn-sm rounded-circle" href="#" role="button" id="DropdownNine" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fe fe-more-vertical"></i>
                                </a>
                            </span>
                        </div>
                    </div>
                    <div class="text-center my-4">
                        <h1 class="display-3 text-success mb-0 fw-bold">{{number_format($count_enrolled)}}</h1>
                        <p class="mb-0">In this week</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-4 col-12">
            <!-- card  -->
            <div class="card mb-4">
                <!-- card body  -->
                <div class="card-body">
                    <div class="d-flex justify-content-between
                    align-items-center">
                        <div>
                            <h4 class="mb-0">PENDING</h4>
                        </div>
                        <!-- dropdown  -->
                        <div>
                            <span class="dropdown dropstart">
                                <a class="btn-icon btn btn-ghost btn-sm rounded-circle" href="#" role="button" id="DropdownNine" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fe fe-more-vertical"></i>
                                </a>
                            </span>
                        </div>
                    </div>
                    <!-- text center  -->
                    <div class="text-center my-4">
                        <h1 class="display-3 text-info mb-0 fw-bold">{{number_format($count_pending)}}</h1>
                        <p class="mb-0">In this week</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-4 col-12">
            <!-- card  -->
            <div class="card mb-4">
                <!-- card body  -->
                <div class="card-body">
                    <div class="d-flex justify-content-between
                    align-items-center">
                        <div>
                            <h4 class="mb-0">DROPPED</h4>
                        </div>
                        <!-- dropdown  -->
                        <div>
                            <span class="dropdown dropstart">
                                <a class="btn-icon btn btn-ghost btn-sm rounded-circle" href="#" role="button" id="DropdownNine" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fe fe-more-vertical"></i>
                                </a>
                            </span>
                        </div>
                    </div>
                    <!-- text center  -->
                    <div class="text-center my-4">
                        <h1 class="display-3 text-danger mb-0 fw-bold">{{number_format($count_dropped)}}</h1>
                        <p class="mb-0">In this week</p>
                    </div>
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
                                <option value="{{$course->courseid}}">{{optional($course)->coursecode}} - {{optional($course)->coursename}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <select name="selected_stat" class="form-select" wire:model="selected_stat">
                                <option value="">Select a status</option>
                                <option value="1">Pending</option>
                                <option value="0">Approved</option>
                                <option value="2">Drop</option>
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
                            <button type="button" class="btn btn-success float-end me-3" wire:click.prevent="performAction">Accept Selected</button>
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
                                            <th> SELECT </th>
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
                                                <div class="form-check">
                                                    <input class="form-check-input ms-2" type="checkbox" wire:model.defer="selectedItems" value="{{ $enroll->enroledid }}">
                                                </div>
                                            </td>
                                            <td>
                                                <a class="text-inherit">
                                                    <div class="d-flex align-items-center">
                                                        <div class="ms-3">
                                                            <h4 class="mb-1 text-primary-hover">
                                                                <i class="mdi mdi-file-document"></i> {{optional($enroll->course)->coursecode}} - {{optional($enroll->course)->coursename}}
                                                            </h4>
                                                            @if ($enroll->schedule)
                                                            <small class="text-muted">Training schedule: {{date('d F, Y', strtotime($enroll->schedule->startdateformat))}} - {{date('d F, Y', strtotime($enroll->schedule->enddateformat))}}</small>
                                                            @endif
                                                            <br>
                                                            <small class="text-muted"> Payment type: {{$enroll->payment->paymentmode}}</small>
                                                            <br>
                                                            @if ($enroll->created_at)
                                                            <small class="text-muted"> Added on: {{date('d F, Y', strtotime($enroll->created_at))}}</small>
                                                            @else
                                                            <small class="text-muted"> Added on: <span class="text-danger">Undefined</span></small>
                                                            @endif
                                                            <br>
                                                            @if (optional($enroll->trainee)->company)
                                                            <small class="text-muted"> Company: {{$enroll->trainee->company->company}}</small>
                                                            @else
                                                            <small class="text-muted"> Company: <span class="text-danger">Undefined</span></small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </a>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if (optional($enroll->trainee)->imagepath)
                                                    <img src="{{asset('storage/traineepic/'. $enroll->trainee->imagepath)}}" alt="" class="rounded-circle avatar-xs me-2">
                                                    @else
                                                    <img src="{{asset('assets/images/avatar/avatar.jpg')}}" alt="" class="rounded-circle avatar-xs me-2">
                                                    @endif

                                                    @if ($enroll->trainee)
                                                    <h5 class="mb-0" style="text-transform: uppercase;" nonce={{Vite::useCspNonce()}}>{{$enroll->trainee->f_name}} {{$enroll->trainee->l_name}}</h5>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if ($enroll->pendingid == 2)
                                                <span class="badge-dot bg-danger me-1 d-inline-block align-middle"></span><small> DROP ({{ date('d F, Y', strtotime($enroll->datedrop)) }})</small>
                                                @elseif ($enroll->pendingid == 1)
                                                <span class="badge-dot bg-warning me-1 d-inline-block align-middle"></span><small>Pending</small>
                                                @elseif ($enroll->pendingid == 0)
                                                <span class="badge-dot bg-success me-1 d-inline-block align-middle"></span><small>Approved</small>
                                                @elseif ($enroll->pendingid == 3)
                                                <span class="badge-dot bg-info me-1 d-inline-block align-middle"></span><small>Remedial</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($enroll->pendingid == 1)
                                                <button wire:click.prevent="reject_enroll({{$enroll->enroledid}})" class="btn btn-outline-secondary btn-sm">Reject</button>
                                                <button wire:click.prevent="approved_enroll({{$enroll->enroledid}})" class="btn btn-success btn-sm">Accept</button>
                                                @endif

                                            </td>
                                            <td>
                                                <span class="dropdown dropstart">
                                                    <a class="btn-icon btn btn-ghost btn-sm rounded-circle" href="#" role="button" id="courseDropdown1" data-bs-toggle="dropdown" data-bs-offset="-20,20" aria-expanded="false">
                                                        <i class="fe fe-more-vertical"></i>
                                                    </a>
                                                    <span class="dropdown-menu" aria-labelledby="courseDropdown1">
                                                        <span class="dropdown-header">Settings</span>
                                                        @if ($enroll->pendingid == 0)
                                                        <a class="dropdown-item" href="{{ route('a.viewadmission', ['enrol_id' => $enroll->enroledid]) }}" target="_blank">
                                                            <i class="fe fe-file-text dropdown-item-icon"></i>Generate Admission Slip
                                                        </a>
                                                        @endif

                                                        <a class="dropdown-item" href="{{route('a.view-batch',['training_id' => $enroll->scheduleid])}}">
                                                            <i class="fe fe-eye dropdown-item-icon"></i>View Details
                                                        </a>

                                                        @if($enroll->pendingid == 1)
                                                        <button class="dropdown-item" wire:click.prevent="confirmdelete({{$enroll->enroledid}})">
                                                            <i class="fe fe-trash dropdown-item-icon"></i>Delete
                                                        </button>
                                                        @endif

                                                        @if($enroll->pendingid == 0)
                                                        <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#generateModalDrop" wire:click="confirmdrop('{{$enroll->enroledid}}')">
                                                            <i class="fe fe-x dropdown-item-icon"></i>Drop</button>
                                                        </button>
                                                        @endif

                                                    </span>
                                                </span>
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
<script nonce="{{Vite::CspNonce()}}">
    function refreshPage() {
        window.location.reload();
    };
</script>
@endpush