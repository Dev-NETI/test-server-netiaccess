<section class="pt-5">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                <!-- Bg -->
                <div class=" pt-16 rounded-top-md " style="
                    background: url({{asset('assets/images/background/profile-bg.jpg')}}) no-repeat;
                    background-size: cover;">
                </div>
                <div class="card rounded-0 rounded-bottom  px-4  pt-2 pb-4 ">
                    <div class="d-flex align-items-end justify-content-between  ">
                        <div class="d-flex align-items-center">
                            <div class="me-2 position-relative d-flex justify-content-end align-items-end mt-n5">
                                @if ($trainee->imagepath)
                                <img src="{{asset('storage/traineepic/'.$trainee->imagepath)}}"
                                    class="avatar-xl rounded-circle border border-4 border-white" alt="avatar">
                                @else
                                <img src="{{asset('assets/images/avatar/avatar.jpg')}}"
                                    class="avatar-xl rounded-circle border border-4 border-white" alt="avatar">
                                @endif
                            </div>
                            <div class="lh-1">
                                <h2 class="mb-0">{{$trainee->formal_name()}}
                                </h2>
                                <p class=" mb-0 d-block"><i>RANK: {{$trainee->rank->rank}} -
                                        {{$trainee->rank->rankacronym}}</i> </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row mt-0 mt-md-4">
            <div class="col-lg-3 col-md-4 col-12 ">
                <!-- Side navbar -->
                <nav class="navbar navbar-expand-md navbar-light shadow-sm mb-4 mb-lg-0 sidenav">
                    <!-- Menu -->
                    <a class="d-xl-none d-lg-none d-md-none text-inherit fw-bold" href="#">Menu</a>
                    <!-- Button -->
                    <button class="navbar-toggler d-md-none icon-shape icon-sm rounded bg-primary text-light"
                        type="button" data-bs-toggle="collapse" data-bs-target="#sidenav" aria-controls="sidenav"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="fe fe-menu"></span>
                    </button>
                    <!-- Collapse navbar -->
                    <div class="collapse navbar-collapse" id="sidenav">
                        <div class="navbar-nav flex-column">
                            <span class="navbar-header">Account Settings</span>

                            <!-- List -->
                            @if (Auth::user()->u_type == 1)
                            <ul class="list-unstyled ms-n2 mb-0">
                                <!-- Nav item -->
                                <li class="nav-item active">
                                    <a class="nav-link"
                                        href="{{route('a.history', ['traineeid' => $trainee->traineeid])}}"><i
                                            class="fe fe-user nav-icon"></i>View History</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link"
                                        href="{{route('a.editprofile', ['traineeid' => $trainee->traineeid])}}"><i
                                            class="fe fe-settings nav-icon"></i>Edit Profile</a>
                                </li>
                                <!-- Nav item -->
                                <li class="nav-item">
                                    <a class="nav-link"
                                        href="{{route('a.editsecurity', ['traineeid' => $trainee->traineeid])}}"><i
                                            class="fe fe-user nav-icon"></i>Security</a>
                                </li>
                            </ul>
                            @elseif (Auth::user()->u_type == 4)
                            <ul class="list-unstyled ms-n2 mb-0">
                                <!-- Nav item -->
                                <li class="nav-item active">
                                    <a class="nav-link active"
                                        href="{{route('te.history', ['traineeid' => $trainee->traineeid])}}"><i
                                            class="fe fe-user nav-icon"></i>View History</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link"
                                        href="{{route('te.editprofile', ['traineeid' => $trainee->traineeid])}}"><i
                                            class="fe fe-settings nav-icon"></i>Edit Profile</a>
                                </li>
                                <!-- Nav item -->
                                <li class="nav-item">
                                    <a class="nav-link"
                                        href="{{route('te.editsecurity', ['traineeid' => $trainee->traineeid])}}"><i
                                            class="fe fe-user nav-icon"></i>Security</a>
                                </li>
                            </ul>
                            @else
                            <ul class="list-unstyled ms-n2 mb-0">
                                <li class="nav-item active">
                                    <a class="nav-link"
                                        href="{{route('c.history', ['traineeid' => $trainee->traineeid])}}"><i
                                            class="fe fe-user nav-icon"></i>View History</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link"
                                        href="{{route('c.editprofile', ['traineeid' => $trainee->traineeid])}}"><i
                                            class="fe fe-settings nav-icon"></i>Edit Profile</a>
                                </li>
                                <!-- Nav item -->
                                <li class="nav-item">
                                    <a class="nav-link"
                                        href="{{route('c.editsecurity', ['traineeid' => $trainee->traineeid])}}"><i
                                            class="fe fe-user nav-icon"></i>Security</a>
                                </li>
                            </ul>
                            @endif

                        </div>
                    </div>
                </nav>
            </div>
            <div class="col-lg-9 col-md-8 col-12 mb-5">

                <div class="row">
                    <div class="col-lg-6 col-md-12 col-12">
                        <!-- Card -->
                        <div class="card mb-4 h-100">
                            <div class="p-4">
                                <span class="fs-6 text-uppercase fw-semibold">Enrolled courses</span>
                                <h2 class="mt-4 fw-bold mb-1 d-flex align-items-center h1 lh-1">
                                    {{$total_enroll}}
                                </h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-12 col-12">
                        <!-- Card -->
                        <div class="card mb-4 h-100">
                            <div class="p-4">
                                <span class="fs-6 text-uppercase fw-semibold">Pending Courses</span>
                                <h2 class="mt-4 fw-bold mb-1 d-flex align-items-center h1 lh-1">
                                    {{$total_pending}}
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12 md-6">
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header">
                        <h3 class="mb-0">View Course History</h3>
                        <p class="mb-0">
                            Allows users to access a comprehensive record of their past courses, providing valuable
                            insights into their learning journey.
                        </p>
                    </div>
                    <!-- Card body -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm fs-6 text-nowrap mb-0 table-centered">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Course</th>
                                        <th>Training Schedule</th>
                                        <th>Bus</th>
                                        <th>Payment mode</th>
                                        <th>Dorm</th>
                                        <th>Reservation Status</th>
                                        <th>Tshirt</th>
                                        <th>Status</th>
                                        <th>Date Applied</th>
                                        <th>Date Confirmed</th>
                                        @if(Auth::user()->u_type == 1 || Auth::user()->u_type == 3)
                                        <th>Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($view_enroled as $i => $enroled)
                                    <tr>
                                        <td class="fw-bold">{{$enroled->enroledid}}</td>
                                        <td class="fw-bold">{{optional($enroled->course)->coursecode}} -
                                            <i>{{optional($enroled->course)->coursename}}</i>
                                        </td>
                                        <td>{{$enroled->schedule->startdateformat}} -
                                            {{$enroled->schedule->enddateformat}}
                                        </td>

                                        <td>{{optional($enroled->bus)->busmode ?? 'N/A'}}</td>

                                        <td>{{$enroled->payment->paymentmode}}</td>
                                        <td>
                                            @if ($enroled->dorm !== null)
                                            {{optional($enroled->dorm)->dorm}}
                                            @else
                                            None
                                            @endif
                                        </td>
                                        @if ($enroled->dormid != 1 && $enroled->dormid != 0 && $enroled->dormid != null
                                        && $enroled->dormid != "")
                                        <td>{{ $enroled->reservationstatus->status }}</td>
                                        @else
                                        <td>( - )</td>
                                        @endif
                                        <td>{{$enroled->tshirt->tshirt}}</td>
                                        @if ($enroled->pendingid == 1)
                                        <td class="text-danger">
                                            <span class="badge bg-warning">Pending</span>
                                        </td>
                                        @elseif ($enroled->pendingid == 0)
                                        <td class="text-success">
                                            <span class="badge bg-success">Enrolled</span>
                                        </td>
                                        @elseif ($enroled->dropid == 1)
                                        <td class="text-success">
                                            <span class="badge bg-danger">Dropped</span>
                                        </td>
                                        @endif
                                        <th>{{$enroled->created_at}}</th>
                                        <th>{{$enroled->dateconfirmed}}</th>
                                        @if(Auth::user()->u_type == 1)
                                        <th>
                                            <a class="btn btn-primary btn-sm"
                                                href="{{route('a.view-batch',['training_id' => $enroled->scheduleid])}}">View
                                                Details</a>
                                            <a class="btn btn-primary btn-sm"
                                                href="{{ route('a.viewadmission', ['enrol_id' => $enroled->enroledid]) }}"
                                                target="_blank">
                                                Generate Admission Slip
                                            </a>
                                            @if($enroled->pendingid == 1)
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#archiveModal"
                                                wire:click="confirmdelete({{ $enroled->enroledid }})">
                                                <i class="bi bi-archive"></i> Delete request
                                            </button>
                                            @elseif($enroled->pendingid == 0 && $enroled->passid == 0)
                                            <button class="btn btn-sm btn-danger"
                                                wire:click.prevent="confirmdrop({{ $enroled->enroledid }})"
                                                data-bs-toggle="modal" data-bs-target="#generateModalDrop">Drop</button>
                                            @endif

                                            <button class="btn btn-info btn-sm"
                                                wire:click='reservationActive({{$enroled->enroledid}})'>Update
                                                Dorm</button>

                                            <div wire:ignore.self class="modal fade" id="reservationActiveModal"
                                                tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalCenterTitle">Select
                                                                Room Type</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close">
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <select class="form-select" name="" wire:model="roomType"
                                                                id="">
                                                                <option value="" selected>Select</option>
                                                                @foreach ($roomTypes as $item)
                                                                <option value="{{$item->dormid}}">{{$item->dorm}}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button wire:click="updateDorm" type="button"
                                                                class="btn btn-primary">Update</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <button class="btn btn-info btn-sm"
                                                wire:click='busActive({{$enroled->enroledid}})'>Update Bus</button>

                                            <div wire:ignore.self class="modal fade" id="busActiveModal" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalCenterTitle"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalCenterTitle">Select
                                                                Bus Mode</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close">
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <select class="form-select" name="" wire:model="busType"
                                                                id="">
                                                                <option value="" selected>Select</option>
                                                                @foreach ($busTypes as $item)
                                                                <option value="{{$item->id}}">{{$item->busmode}}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button wire:click="updateBus" type="button"
                                                                class="btn btn-primary">Update</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @if($enroled->passid == 1 && $enroled->certificate_history &&
                                            optional($enroled->certificate_history)->is_approve === 1)
                                            <button class="btn btn-info btn-sm"
                                                wire:click='certificate({{$enroled->enroledid}})'>Certificate</button>
                                            @if(Auth::user()->userid === 404 || Auth::user()->userid === 428 ||
                                            Auth::user()->userid === 65)
                                            <button class="btn btn-warning btn-sm"
                                                wire:click='secondc_certificate({{$enroled->enroledid}})'>Second Copy
                                                Certificate</button>
                                            @endif
                                            @endif

                                        </th>
                                        @elseif (Auth::user()->u_type == 3)
                                        <th>
                                            @if(optional($enroled->course->type)->coursetypeid === 3 ||
                                            optional($enroled->course->type)->coursetypeid === 4)
                                            @if($enroled->passid == 1 && $enroled->certificate_history &&
                                            optional($enroled->certificate_history)->is_approve === 1 && optional($enroled->certificate_history)->is_released === 1)
                                            <button class="btn btn-info btn-sm"
                                                wire:click='certificate({{$enroled->enroledid}})'>Certificate</button>
                                            @endif
                                            @endif

                                            @php
                                            $createdAt = \Carbon\Carbon::parse($enroled->schedule->enddateformat);
                                            $startDate = $createdAt->copy()->startOfDay();
                                            $endDate = $startDate->copy()->addYear();
                                            $currentDate = \Carbon\Carbon::now();
                                            $startDisplayDate = \Carbon\Carbon::create(2024, 8, 19)->startOfDay();

                                            $implementeDate = $createdAt->GreaterThanOrEqualTo($startDisplayDate) ;
                                            $shouldDisplay = $currentDate->between($startDate, $endDate);

                                            @endphp

                                            @if(optional(optional($enroled->course)->type)->coursetypeid == 7)
                                            @if($enroled->passid == 1 && $enroled->certificate_history &&
                                            optional($enroled->certificate_history)->is_approve === 1 && optional($enroled->certificate_history)->is_released === 1)
                                            @if($implementeDate)
                                            <button class="btn btn-info btn-sm"
                                                wire:click='certificate({{$enroled->enroledid}})'>Certificate</button>
                                            @endif
                                            @endif
                                            @endif
                                        </th>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="generateModalDrop" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                <textarea class="form-control" name="" id="" cols="30" rows="10"
                                    wire:model.defer="reason"></textarea>
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
</section>