<section class="container-fluid p-4">
    <span wire:loading>
        <livewire:components.loading-screen-component />
    </span>
    <div class="row">
        <!-- Page Header -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-flex justify-content-between align-items-center">
                <div class="mb-2 mb-lg-0">
                    @if (Auth::user()->u_type == 1)
                    <h1 class="mb-1 h2 fw-bold">
                        Generate Batch report
                        <span class="fs-5 text-muted"></span>
                    </h1>
                    <!-- Breadcrumb  -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{route('a.dashboard')}}">Administrator</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{route('a.report-dashboard')}}">Reports</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Generate Batch report
                            </li>
                        </ol>
                    </nav>
                    @else
                    <h1 class="mb-1 h2 fw-bold">
                        Course Schedule
                        <span class="fs-5 text-muted"></span>
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="#">Technical</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="#">Course</a>
                            </li>
                            <li class="breadcrumb-item active">
                                <a href="#">Course Schedule</a>
                            </li>
                        </ol>
                    </nav>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 col-md-4 col-12">
            <div class="mb-3">
                <label class="form-label" for="">Batch Week<span class="text-danger">*</span></label>
                <select class="form-select " wire:model="selected_batch" placeholder="Click to Batch Week">
                    <option value="">Select Batch</option>
                    @foreach ($batchWeeks as $week)
                    <option value="{{$week->batchno}}">{{$week->batchno}}</option>
                    @endforeach
                </select>
                @error('selected_batch')
                <small class="text-danger">{{$message}}</small>
                @enderror
            </div>
        </div>

        @if (Auth::user()->u_type == 1)
        <div class="col-lg-12 col-md-4 col-12">
            <div class="text-right mb-3">
                @if ($selected_batch)
                <a class="btn btn-dark d-inline ms-3" href="{{route('a.avail-course-generate-pdf', ['selected_batch' => $selected_batch])}}" target="_blank">WEEKLY AVAILABLE COURSE</a>
                <a class="btn btn-dark d-inline ms-3" href="{{ route('a.pending-trainee-pdf', ['selected_batch' => $selected_batch]) }}" target="_blank">WEEKLY PENDING TRAINEE</a>
                <a class="btn btn-dark d-inline ms-3" href="{{ route('a.view-trainee-batch-excel', ['selected_batch' => $selected_batch]) }}" target="_blank">WEEKLY TRAINEE BATCH REPORT</a>
                <a class="btn btn-dark d-inline ms-3" href="{{ route('a.export-list-enrolees-excel', ['selected_batch' => $selected_batch]) }}" target="_blank">WEEKY LIST OF ENROLEES</a>
                <a class="btn btn-dark d-inline ms-3" href="{{ route('a.view-training-schedule-excel', ['selected_batch' => $selected_batch]) }}" target="_blank">WEEKLY TRAINING SCHEDULE</a>
                <a class="btn btn-dark d-inline ms-3" href="{{ route('a.download-attendance', ['selected_batch' => $selected_batch]) }}" target="_blank">ATTTENDANCE</a>
                <button class="btn btn-dark d-inline ms-3" wire:click.prevent="uncutoff_all" @if (!$training_schedules->count())
                    disabled
                    @endif>UNCUTOFF SCHEDULE</button>
                <button class="btn btn-dark d-inline ms-3" wire:click.prevent="cutoff_all" @if (!$training_schedules->count())
                    disabled
                    @endif>CUTOFF SCHEDULE</button>
                @endif
            </div>
        </div>
        @endif
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
                        <div class="col-lg-7">
                            <div class="float-end">
                                @if ($selected_batch)
                                <input type="text" class="form-control" placeholder="Search Course Name" wire:model="search">
                                @endif
                            </div>
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
                                <th></th>
                            </tr>
                        </thead>

                        <tbody class="" style="font-size: 11px;">
                            @if ($training_schedules)
                            @if ($training_schedules->count())
                            @foreach ($training_schedules as $training_schedule)
                            <!-- inactive if 1 -->
                            <tr @if ($training_schedule->cutoffid == 1 && $training_schedule->printedid == 1)
                                style="background-color: rgba(253, 203, 110,1.0); color: black;"
                                @elseif ($training_schedule->cutoffid == 1)
                                style="background-color: rgba(192, 57, 43,0.9); color: white;"
                                @endif
                                >
                                <!-- <td>{{ $training_schedule->scheduleid}}</td> -->

                                <td>
                                    <b>{{$training_schedule->batchno}}</b>
                                </td>


                                @if ($training_schedule->startdateformat)
                                <td><b> {{ date('d, F, Y', strtotime($training_schedule->startdateformat)) }} to {{ date('d,
                                    F, Y', strtotime($training_schedule->enddateformat)) }} </b></td>
                                @else
                                <td>--------------</td>
                                @endif

                                @if ($training_schedule->course)
                                <td> <b>{{$training_schedule->course->coursecode}}</b> - {{$training_schedule->course->coursename}}</td>
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
                                    <a title="id_card" href="{{route('a.trainee-id',['training_id' => $training_schedule->scheduleid])}}" target="_blank" class="btn btn-success btn-sm"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill-down" viewBox="0 0 16 16">
                                            <path d="M12.5 9a3.5 3.5 0 1 1 0 7 3.5 3.5 0 0 1 0-7m.354 5.854 1.5-1.5a.5.5 0 0 0-.708-.708l-.646.647V10.5a.5.5 0 0 0-1 0v2.793l-.646-.647a.5.5 0 0 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                            <path d="M2 13c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4" />
                                        </svg></a>
                                    <button class="btn btn-warning btn-sm" title="Assign" wire:click="show({{$training_schedule->scheduleid}})" data-bs-toggle="modal" data-bs-target=".assign"><i class="bi bi-arrow-return-left"></i></button>
                                    <a title="View" href="{{route('a.view-batch',['training_id' => $training_schedule->scheduleid])}}" target="_blank" class="btn btn-success btn-sm"><i class="bi bi-eye"></i></a>
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
                </div>
            </div>
        </div>
    </div>
    @include('livewire.admin.reports.training-schedule.a-assign-instructor-modal')
</section>