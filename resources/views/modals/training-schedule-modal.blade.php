<div wire:ignore.self class="modal fade assign" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">Assign Instructor, Assessor and Room</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="update_training" class="row">
                    <div class="mb-3 col-12 col-md-12">
                        <label class="form-label">Assign instructor:</label>
                        <select class="form-control" wire:model.defer="selected_instructor">
                            @if ($course->type->coursetypeid == 1)
                            <option value="">Select Instructor</option>
                            @if ($instructors_man)
                            @foreach ($instructors_man as $ins)
                                @if ($ins->dateofissue <= $datenow && $ins->expirationdate >= $datenow && $ins->instructor->user)
                                {{-- <option value="{{$ins->instructor->userid}}">{{$ins->instructor->user->formal_name()}} - {{$ins->license}} - {{$ins->dateofissue}} - {{$ins->expirationdate}}</option> --}}
                                <option value="{{$ins->instructor->userid}}">{{$ins->instructor->user->f_name}} - {{$ins->license}} - {{$ins->dateofissue}} - {{$ins->expirationdate}}</option>
                               
                                @endif
                            @endforeach
                            @endif

                            @else
                                <option value="">Select Instructor</option>
                                @foreach ($instructors as $ins)
                                    @if ($ins->user)
                                    <option value="{{$ins->user->user_id}}">{{$ins->user->formal_name()}} </option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                        @error('selected_instructor')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>

                    <div class="mb-3 col-12 col-md-12">
                        <label class="form-label">Assign alternate instructor:</label>
                        <select class="form-control" wire:model.defer="selected_a_instructor">
                            @if ($course->type->coursetypeid == 1)
                            <option value="">Select Instructor</option>
                            @if ($instructors_man)
                            @foreach ($instructors_man as $ins)
                                @if ($ins->dateofissue <= $datenow && $ins->expirationdate >= $datenow && $ins->instructor->user)
                                <option value="{{$ins->instructor->userid}}">{{$ins->instructor->user->formal_name()}} - {{$ins->license}} - {{$ins->dateofissue}} - {{$ins->expirationdate}}</option>
                               
                                @endif
                            @endforeach
                            @endif

                            @else
                                <option value="">Select Instructor</option>
                                @foreach ($instructors as $ins)
                                    @if ($ins->user)
                                    <option value="{{$ins->user->user_id}}">{{$ins->user->formal_name()}} </option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                        @error('selected_instructor')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>

                    <div class="mb-3 col-12 col-md-12">
                        <label class="form-label">Assign assessor:</label>
                        <select class="form-control" wire:model.defer="selected_assessor">
                            <option value="">Select assessor</option>
                            @if ($assessor_man)
                            @foreach ($assessor_man as $ins)
                            @if ($ins->dateofissue <= $datenow && $ins->expirationdate >= $datenow && $ins->instructor->user)
                                <option value="{{$ins->instructor->userid}}">{{$ins->instructor->user->formal_name()}} - {{$ins->license}} - {{$ins->dateofissue}} - {{$ins->expirationdate}}</option>
                               
                                @endif
                                @endforeach
                                @endif
                        </select>
                        @error('selected_assessor')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>

                    <div class="mb-3 col-12 col-md-12">
                        <label class="form-label">Assign alternate assessor:</label>
                        <select class="form-control" wire:model.defer="selected_a_assessor">
                            <option value="">Select assessor</option>
                            @if ($assessor_man)
                            @foreach ($assessor_man as $ins)
                            @if ($ins->dateofissue <= $datenow && $ins->expirationdate >= $datenow && $ins->instructor->user)
                                <option value="{{$ins->instructor->userid}}">{{$ins->instructor->user->formal_name()}} - {{$ins->license}} - {{$ins->dateofissue}} - {{$ins->expirationdate}}</option>
                               
                                @endif
                                @endforeach
                                @endif
                        </select>
                        @error('selected_assessor')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>

                    <div class="mb-3 col-12 col-md-12">
                        <label class="form-label">Assign room:</label>
                        <select class="form-control" wire:model.defer="selected_room">
                            @foreach ($rooms as $room)
                            <option value="{{$room->roomid}}">{{$room->room}}</option>
                            @endforeach
                        </select>
                        @error('selected_room')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                    <div class=" col-12 col-md-12">
                        <div class="float-end">
                            <button class="btn btn-success">Update the schedule</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div wire:ignore.self class="modal fade createtraining" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">Create training schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="create_sched" class="row">

                    @if (Session::has('error'))
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill me-2" viewBox="0 0 16 16">
                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                        </svg>
                        <div>
                            {{ Session::get('error') }}
                        </div>
                    </div>
                    @endif

                    @if (Session::has('success'))
                    <div class="alert alert-success d-flex align-items-center" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-check-circle-fill me-2" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                        </svg>
                        <div>
                            {{ Session::get('success') }}
                        </div>
                    </div>
                    @endif

                    <div class="mb-3 col-12 col-md-12">
                        <label class="form-label" for="fname">Batch No.</label>
                            <select class="form-select " wire:model.defer="batchno" placeholder="Click to Batch Week">
                                <option value="">Select Batch</option>
                                @foreach ($batchWeeks as $week)
                                <option value="{{$week->batchno}}">{{$week->batchno}}</option>
                                @endforeach
                            </select>
                        @error('batchno')
                        <small style="color: red;">{{$message}}</small>
                        @enderror
                    </div>

                    <div class="mb-3 col-12 col-md-6">
                        <label class="form-label" for="fname">Date from</label>
                        <input type="date" class="form-control" placeholder="mm/dd/yyyy" wire:model.defer="datefrom" required>
                        @error('datefrom')
                        <small style="color: red;">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="mb-3 col-12 col-md-6">
                        <label class="form-label" for="fname">Date to</label>
                        <input type="date" class="form-control" placeholder="mm/dd/yyyy" wire:model.defer="dateto" required>
                        @error('dateto')
                        <small style="color: red;">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="mb-3 col-12 col-md-6">
                        <label class="form-label" for="fname">Online Date from <span class="badge bg-danger">optional</span></label>
                        <input type="date" class="form-control" placeholder="mm/dd/yyyy" wire:model.defer="onlinefrom">
                    </div>
                    <div class="mb-3 col-12 col-md-6">
                        <label class="form-label" for="fname">Online Date to <span class="badge bg-danger">optional</span></label>
                        <input type="date" class="form-control" placeholder="mm/dd/yyyy" wire:model.defer="onlineto">
                    </div>
                    <div class="mb-3 col-12 col-md-6">
                        <label class="form-label" for="fname">Practical Date from <span class="badge bg-danger">optional</span></label>
                        <input type="date" class="form-control" placeholder="mm/dd/yyyy" wire:model.defer="onsitefrom">
                    </div>
                    <div class="mb-3 col-12 col-md-6">
                        <label class="form-label" for="fname">Practical Date to <span class="badge bg-danger">optional</span></label>
                        <input type="date" class="form-control" placeholder="mm/dd/yyyy" wire:model.defer="onsiteto">
                    </div>
                    <div class="mb-3 col-12 col-md-12">
                        <div class="float-end">
                            <button class="btn btn-success">ADD</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div wire:ignore.self class="modal fade updatetraining" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">Edit training schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="ts_update" id="ts_update" class="row">

                    @if (Session::has('error'))
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill me-2" viewBox="0 0 16 16">
                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                        </svg>
                        <div>
                            {{ Session::get('error') }}
                        </div>
                    </div>
                    @endif

                    @if (Session::has('success'))
                    <div class="alert alert-success d-flex align-items-center" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-check-circle-fill me-2" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                        </svg>
                        <div>
                            {{ Session::get('success') }}
                        </div>
                    </div>
                    @endif

                    <div class="mb-3 col-12 col-md-12">
                        <label class="form-label" for="fname">Batch No.</label>
                        <input type="text" class="form-control" placeholder="Ex. January Week 1 2023" wire:model.defer="editbatchno" required>
                        @error('batchno')
                        <small style="color: red;">{{$message}}</small>
                        @enderror
                    </div>

                    <div class="mb-3 col-12 col-md-6">
                        <label class="form-label" for="fname">Start of Training</label>
                        <input type="date" class="form-control" placeholder="mm/dd/yyyy" wire:model.defer="editstartdate" required>
                        @error('datefrom')
                        <small style="color: red;">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="mb-3 col-12 col-md-6">
                        <label class="form-label" for="fname">End of Training</label>
                        <input type="date" class="form-control" placeholder="mm/dd/yyyy" wire:model.defer="editenddate" required>
                        @error('dateto')
                        <small style="color: red;">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="mb-3 col-12 col-md-6">
                        <label class="form-label" for="fname">Online Date from <span class="badge bg-danger">optional</span></label>
                        <input type="date" class="form-control" placeholder="mm/dd/yyyy" wire:model.defer="editonlinefrom">
                    </div>
                    <div class="mb-3 col-12 col-md-6">
                        <label class="form-label" for="fname">Online Date to <span class="badge bg-danger">optional</span></label>
                        <input type="date" class="form-control" placeholder="mm/dd/yyyy" wire:model.defer="editonlineto">
                    </div>
                    <div class="mb-3 col-12 col-md-6">
                        <label class="form-label" for="fname">Practical Date from <span class="badge bg-danger">optional</span></label>
                        <input type="date" class="form-control" placeholder="mm/dd/yyyy" wire:model.defer="editonsitefrom">
                    </div>
                    <div class="mb-3 col-12 col-md-6">
                        <label class="form-label" for="fname">Practical Date to <span class="badge bg-danger">optional</span></label>
                        <input type="date" class="form-control" placeholder="mm/dd/yyyy" wire:model.defer="editonsiteto">
                    </div>
                    <div class="mb-3 col-12 col-md-12">
                        <div class="float-end">
                            <button class="btn btn-success">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div wire:ignore.self class="modal fade schedule" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">Closed the schedule?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="save_status" class="row">
                    <div class="mb-3 col-12 col-md-12">
                        <label class="form-label" for="fname">SCHEDULE CUTOFF:</label>
                        <select class="form-control" wire:model.defer="cutoff_status">
                            <option value="0">Open</option>
                            <option value="1">Closed</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-12">
                        <div class="float-end">
                            <button class="btn btn-success">Update the schedule</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>