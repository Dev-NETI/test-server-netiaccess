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
                        Generate Weekly Training Schedule
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

        <div class="col-lg-4 col-md-4 col-12">
            <label class="form-label" for="selectOne"><span class="text-secondary"></span></label>
            @if ($selected_batch)
            <div class="text-end mt-4 mb-3">
                <a class="btn btn-success d-inline ms-3" href="{{ route('a.view-training-schedule-excel', ['selected_batch' => $selected_batch]) }}" target="_blank">EXPORT TO EXCEL</a>
                <a class="btn btn-primary d-inline ms-3" href="{{ route('a.view-training-schedule-pdf', ['selected_batch' => $selected_batch]) }}" target="_blank">EXPORT TO PDF</a>
            </div>
            @endif
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
                                <th>#</th>
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
                            <tr @if ($training_schedule->cutoffid == 1) style="background-color: rgba(128, 0, 0, 0.8); color: white;" @endif>
                                <td>{{ $training_schedule->scheduleid}}</td>

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
                                    <button class="btn btn-warning btn-sm" title="Assign" wire:click="show({{$training_schedule->scheduleid}})" data-bs-toggle="modal" data-bs-target=".assign"><i class="bi bi-arrow-return-left"></i></button>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr class="text-center">
                                <td colspan="6">-----No Records Found-----</td>
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