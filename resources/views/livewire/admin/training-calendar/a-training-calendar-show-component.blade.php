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
                        List of Training schedule
                        <span class="fs-5 text-muted">({{$course->coursecode}} - {{$course->coursename}})</span>
                    </h1>
                    <!-- Breadcrumb  -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/company/dashboard">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item">Training Schedule<a href=""></a></li>
                            <li class="breadcrumb-item active" aria-current="page">
                                {{$course->coursecode}} - {{$course->coursename}}
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2 col-12 mb-3">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal_file">
            <i class="bi bi-file-earmark-excel-fill"></i> Upload Schedule
        </button>

        <div wire:ignore.self class="modal fade" id="modal_file" tabindex="-1" role="dialog" aria-labelledby="modal_file" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal_file">UPLOAD THE FILE</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <p class="mb-1 text-dark">Download the template:</p>
                            <div class="d-grid gap-2">
                                <button class="btn btn-success" wire:click="downloadTemplate">Click to download the
                                    template</button>
                            </div>
                        </div>
                        <form wire:submit.prevent="upload" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <p class="mb-1 text-dark">Upload the file here: <small style="color: red;"><i>(It only
                                            accept xls. xlsx)</i></small></p>
                                <div class="input-group mb-1">
                                    <input type="file" class="form-control" name="file" wire:model.defer="file">
                                    <button class="input-group-text" type="submit">Upload</button>
                                </div>
                                @error('file') <small class="text-muted">{{$message}} </small>@enderror
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>


        <button type="button" class="btn btn-info" wire:click="export">
            <i class="bi bi-calendar-week-fill"></i> Export training calendar
        </button>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target=".createtraining">
            <i class="bi bi-clipboard-plus-fill"></i> Create Training Calendar
        </button>

    </div>
    <div class="row">
        <!-- basic table -->
        <div class="col-md-12 col-12 mb-5">
            <div class="card">
                <!-- card header  -->
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-5">
                            <h4 class="mb-1">List of Training Schedule</h4>
                        </div>
                        <div class="col-lg-3 text-end">
                            <label for="" class="form-label pt-2">Search:</label>
                        </div>
                        <div class="col-lg-4 float-end">
                            <input type="text" placeholder="search id or training date.." wire:model="search" class="form-control">
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
                                <th># OF ENROLED</th>
                                <th>INSTRUCTOR</th>
                                <th>ASSESSOR</th>
                                <th>ROOM</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody class="" style="font-size: 11px;">
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
                                    <button class="btn btn-danger btn-sm" title="Cutoff" wire:click="load_status({{$training_schedule->scheduleid}})" data-bs-toggle="modal" data-bs-target=".schedule"><i class="bi bi-calendar2-check"></i></button>
                                    <button class="btn btn-info btn-sm" title="Edit training schedule" wire:click="ts_edit({{ $training_schedule->scheduleid }})" data-bs-toggle="modal" data-bs-target=".updatetraining"><i class="bi bi-pencil-fill"></i></button>
                                    <button class="btn btn-danger btn-sm" title="Delete" wire:click="confirm_delete({{ $training_schedule->scheduleid }})"><i class="bi bi-trash"></i></button>
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
                            {{ $training_schedules->links('livewire.components.customized-pagination-link')}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('modals.training-schedule-modal')
</section>