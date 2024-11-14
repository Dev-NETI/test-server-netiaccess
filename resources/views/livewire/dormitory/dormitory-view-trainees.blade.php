<section class="container-fluid p-4">
    <span wire:loading>
        <livewire:components.loading-screen-component />
    </span>
    <div class="row">
        <!-- Page Header -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-flex justify-content-between align-items-center">
                <div class="mb-2 mb-lg-0">
                    <h1 class="mb-1 h2 fw-bold">
                        Trainee List
                    </h1>
                    <!-- Breadcrumb  -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="#">Dormitory</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Trainees Info
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Select Batch</h4>
                </div>
                <div class="card-body">
                    <select class="form-select" name="" wire:model="selected_batch" id="">
                        <option value="">--Please Select a batch--</option>
                        @foreach ($loadbatch as $item)
                        <option value="{{$item  ->batchno}}">{{$item->batchno}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="card">
                <!-- card header  -->
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-5">
                            <h4 class="mb-1">List of Training Schedule</h4>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm text-nowrap mb-0 table-centered" width="100%">
                        <thead>
                            <tr>
                                <!-- <th>#</th> -->
                                <th>BATCH #</th>
                                <th>TRAINING DATE</th>
                                <th>COURSE NAME</th>
                                <th># OF PENDING</th>
                                <th># OF ENROLED</th>
                                <th>INSTRUCTOR</th>
                                <th>ASSESSOR</th>
                                <th>ROOM</th>
                                <th>View Trainees</th>
                            </tr>
                        </thead>

                        <tbody class="" style="font-size: 11px;">
                            @if ($training_schedules)
                            @if ($training_schedules->count())
                            @foreach ($training_schedules as $training_schedule)
                            <!-- inactive if 1 -->
                            <tr @if ($training_schedule->cutoffid == 1) style="background-color: #f8d7da;" @endif>
                                <!-- <td>{{ $training_schedule->scheduleid}}</td> -->

                                <td>
                                    <b>{{$training_schedule->batchno}}</b>
                                </td>


                                @if ($training_schedule->startdateformat)
                                <td><b> {{ date('d, F, Y', strtotime($training_schedule->startdateformat)) }} to {{
                                        date('d,
                                        F, Y', strtotime($training_schedule->enddateformat)) }} </b></td>
                                @else
                                <td>--------------</td>
                                @endif

                                @if ($training_schedule->course)
                                <td> <b>{{$training_schedule->course->coursecode}}</b> -
                                    {{$training_schedule->course->coursename}}</td>
                                @else
                                <td>--------------</td>
                                @endif

                                @if ($training_schedule->slot_pending_count)
                                <td>{{ $training_schedule->slot_pending_count }}</td>
                                @else
                                <td>--------------</td>
                                @endif

                                @if ($training_schedule->enrolled_pending_count)
                                <td>{{ $training_schedule->enrolled_pending_count }}</td>
                                @else
                                <td>--------------</td>
                                @endif

                                @if ($training_schedule->instructor)
                                @if ($training_schedule->instructor->userid === 93)
                                <td>TBA</td>
                                @else
                                <td>{{$training_schedule->instructor->user->formal_name()}}</td>
                                @endif
                                @endif

                                @if ($training_schedule->assessor)

                                @if ($training_schedule->assessor->userid === 93)
                                <td>TBA</td>
                                @else
                                <td>{{$training_schedule->assessor->user->formal_name()}}</td>
                                @endif

                                @endif

                                @if ($training_schedule->room)
                                <td>{{$training_schedule->room->room}}</td>
                                @else
                                <td>--------------</td>
                                @endif
                                <td>
                                    @if (Auth::user()->u_type == 1)
                                    <a title="View" class="btn btn-success btn-sm"
                                        wire:click="showtrainessmodal({{$training_schedule->scheduleid}})"><i
                                            class="bi bi-eye"></i></a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr class="text-center">
                                <td colspan="9">-----No Records Found-----</td>
                            </tr>
                            @endif
                            @endif

                        </tbody>
                    </table>
                    <div class="card-footer">
                        <div class="row">
                            @if ($training_schedules)
                            {{ $training_schedules->links('livewire.components.customized-pagination-link')}}
                            @endif
                        </div>
                    </div>

                    <!-- Modal -->
                    <div wire:ignore.self class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalCenterTitle">Trainees</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover text-nowrap mb-0 table-centered">
                                            <thead>
                                                <tr>
                                                    <th>EnroledID#</th>
                                                    @can('authorizeAdminComponents', 123)
                                                    <th>Action</th>
                                                    @endcan
                                                    <th>Status</th>
                                                    <th>Fullname</th>
                                                    <th>Dorm</th>
                                                    <th>Dorm Status</th>
                                                    <th>Check In Date</th>
                                                    <th>Check Out Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($enroledata as $data)
                                                <tr>
                                                    <td> {{ $data->enroledid }} </td>
                                                    @can('authorizeAdminComponents', 123)
                                                    @if ($data->dormid == 0 && $data->dropid == 0 || $data->dormid == 1
                                                    && $data->dropid == 0)
                                                    <td><button class="btn btn-sm btn-success"
                                                            wire:click="changeDorm({{$data->enroledid}})"
                                                            data-bs-target="#exampleModalToggle2" data-bs-toggle="modal"
                                                            data-bs-dismiss="modal">Change</button> </td>
                                                    @else
                                                    <td><button class="btn btn-sm btn-success disabled">Change</button>
                                                    </td>
                                                    @endif
                                                    @endcan
                                                    <td>
                                                        @if ($data->dropid == 1)
                                                        <small style="font-size: 10px;"
                                                            class="badge text-bg-danger">Dropped | {{
                                                            $data->droplogs->droppedby }}</small>
                                                        @else
                                                        <small style="font-size: 10px;"
                                                            class="badge text-bg-info">Enrolled | {{
                                                            $data->enrolledby }}</small>
                                                        @endif
                                                    </td>
                                                    <td> {{ optional($data->trainee)->l_name }}, {{
                                                        optional($data->trainee)->f_name }} {{
                                                        optional($data->trainee)->m_name }} </td>

                                                    @if ($data->dormid != 0 && $data->dormid != 1 && $data->dormid !=
                                                    null)
                                                    <td class="text-success">
                                                        Availed Dorm
                                                        @else
                                                    <td class="text-danger">
                                                        Not Availed Dorm
                                                        @endif
                                                    </td>
                                                    @if ($data->dormid != 1 && $data->reservationstatusid == 0 &&
                                                    $data->dormid != 0 && $data->reservationstatusid == 0)
                                                    <td class="text-warning">
                                                        Pending
                                                        @elseif ($data->dormid != 1 && $data->reservationstatusid == 1
                                                        && $data->dormid != 0 && $data->reservationstatusid == 1)
                                                    <td class="text-success">
                                                        Checked In
                                                        @elseif ($data->dormid != 1 && $data->reservationstatusid == 2
                                                        && $data->dormid != 0 && $data->reservationstatusid == 2)
                                                    <td class="text-danger">
                                                        Checked Out
                                                        @elseif ( $data->dormid != 1 && $data->reservationstatusid == 3
                                                        &&$data->dormid != 0 && $data->reservationstatusid == 3)
                                                    <td class="text-info">
                                                        Reserved
                                                        @elseif ($data->dormid != 1 && $data->reservationstatusid == 4
                                                        && $data->dormid != 0 && $data->reservationstatusid == 4)
                                                    <td class="text-light-danger">
                                                        No Show
                                                        @elseif ($data->dormid != 1 && $data->reservationstatusid == 5
                                                        && $data->dormid != 0 && $data->reservationstatusid == 5)
                                                    <td class="text-danger">
                                                        Cancelled
                                                        @else
                                                    <td class="">
                                                        ----
                                                        @endif
                                                    </td>
                                                    @if ($data->dormid != 0 && $data->reservationstatusid == 1 ||
                                                    $data->reservationstatusid == 2)
                                                    <td>
                                                        @if ($data->dormitory != null)
                                                        {{ date('F d, Y', strtotime($data->dormitory->checkindate)) }}
                                                        {{ date('h:i a', strtotime($data->dormitory->checkintime)) }}
                                                        @else
                                                        No Data
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($data->dormitory != null && $data->dormitory->checkoutdate
                                                        != NULL && $data->dormitory->checkouttime != NULL)
                                                        {{ date('F d, Y', strtotime($data->dormitory->checkoutdate)) }}
                                                        {{ date('h:i a', strtotime($data->dormitory->checkouttime)) }}
                                                        @else
                                                        No Data
                                                        @endif
                                                    </td>
                                                    @else
                                                    <td>
                                                        ---
                                                    </td>
                                                    @endif
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="exampleModalToggle2" aria-hidden="true"
        aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalToggleLabel2">Dorm Change for - {{ $fullname }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <label for="roomtype">Select Room Type</label>
                            <select id="roomtype" name="" id="" required wire:model='dormtype' class="form-select">
                                <option value="" selected>Select Room Type</option>
                                @foreach ($dorm as $type)
                                <option value="{{$type->dormid}}">{{ $type->dorm }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-bs-target="#exampleModalCenter" wire:click="resetVar()"
                        data-bs-toggle="modal" data-bs-dismiss="modal">Back</button>
                </div>
            </div>
        </div>
    </div>
</section>