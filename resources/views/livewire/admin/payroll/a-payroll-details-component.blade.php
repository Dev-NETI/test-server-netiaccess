<div class="container-fluid">
    <div class="col-12 col-md-12">
        <div class="card mt-5">
            <div class="card-header mx-auto">
                <h3>PAYROLL DATA: {{date('F d, Y', strtotime($period_start))}} - {{date('F d, Y', strtotime($period_end))}}</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="input-group input-group-warning">
                            <input type="text" class="form-control form-control-bold" placeholder="INSERT HERE THE MEMO NUMBER" wire:model.defer="memo_num" required>
                            <div class="input-group-append">
                                <button class="btn btn-danger" wire:click.prevent="save_memo" type="button">SAVE MEMO. NUM</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-6 py-2 text-end">
                        <button class="btn btn-success" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#addpayrollmodal">ADD DATA</button>
                        <button class="btn btn-success" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#addadjustmentmodal">ADD ADJUSTMENT DATA</button>
                        <button class="btn btn-danger" x-data @click="confirmation_delete" wire:loading.attr="disabled">DELETE DATA</button>
                        <a class="btn btn-info" href="{{route('glps.print-attendance', ['hash_id' => $hash_id])}}" target="_blank" wire:loading.attr="disabled">ATTENDANCE DATA</a>
                        <a class="btn btn-warning" href="{{route('glps.print-payroll', ['hash_id' => $hash_id])}}" target="_blank">PAYROLL DATA</a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-12">
                        <div class="table-responsive">
                            <table class="table table-sm text-nowrap mb-0 table-centered table-dark" width="100%">
                                <thead>
                                    <tr>
                                        <th>ACTION</th>
                                        <th>#</th>
                                        <th>NAME OF INSTRUCTOR</th>
                                        <th>RANK</th>
                                        <th>ASSIGNMENT / COURSE CONDUCTED</th>
                                        <th>NO. OF DAY</th>
                                        <th>NO. OF HR/OT</th>
                                        <th>MIN. LATE</th>
                                        <th>DEDUCTION</th>
                                        <th>RATE PER DAY</th>
                                        <th>RATE PER HR</th>
                                        <th>SUBTOTAL</th>
                                        <th>TOTAL</th>
                                    </tr>
                                </thead>

                                <tbody class="" style="font-size: 11px;">
                                    @if($payrolls->count() == 0)
                                    <tr>
                                        <td class="text-center" colspan="15">NO DATA FOUND</td>
                                    </tr>
                                    @else
                                    @foreach($payrolls as $index => $payroll_log)
                                    <tr>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button id="" type="button" class="btn btn-info btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                                <div class="dropdown-menu" aria-labelledby="">
                                                    <button class="dropdown-item" wire:click="editSchedule({{ $payroll_log->id }})" data-bs-toggle="modal" data-bs-target="#movetomodal" wire:key="{{ $payroll_log->id }}"><i class="bi bi-arrow-right" style="font-size: 1em;"></i>&nbsp;Move to</button>
                                                    <button class="dropdown-item" wire:click="showAssignedDescription({{ $payroll_log->id }})" data-bs-toggle="modal" data-bs-target="#assigndescription" wire:key="{{ $payroll_log->id }}"><i class="bi bi-plus" style="font-size: 1em;"></i>&nbsp;Assign Description</button>
                                                    <button class="dropdown-item" wire:click="confirm_delete({{$payroll_log->id}})" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="bi bi-trash" style="font-size: 1em;"></i>&nbsp;Delete Record</button>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $payroll_log->user->formal_name() }}</td>
                                        @if(optional(optional($payroll_log->user)->instructor)->rank)
                                        <td>{{ $payroll_log->user->instructor->rank->rankacronym }} - {{ $payroll_log->user->instructor->rank->rank }}</td>
                                        @else
                                        <td>NEED TO ASSIGN RANK</td>
                                        @endif

                                        <td> @if($payroll_log->course_id && $payroll_log->description_id)
                                            {{ optional($payroll_log->payrolldesc)->description }}
                                            @elseif(optional($payroll_log)->course_id)
                                            {{ $payroll_log->course->coursecode }} - {{ $payroll_log->course->coursename }}
                                            @elseif(optional($payroll_log)->description_id)
                                            {{ $payroll_log->payrolldesc->description }}
                                            @endif
                                        </td>

                                        <td>{{ $payroll_log->no_day }}</td>
                                        <td>{{ $payroll_log->no_ot }}</td>
                                        <td>{{ $payroll_log->no_late }}</td>
                                        <td>{{ number_format($payroll_log->deduction, 2) }}</td>
                                        <td>{{ number_format($payroll_log->rate_per_day, 2) }}</td>
                                        <td>{{ number_format($payroll_log->rate_per_hr, 2) }}</td>
                                        <td>{{ number_format($payroll_log->subtotal, 2) }}</td>
                                        <td>{{ number_format($payroll_log->total, 2) }}</td>
                                    </tr>
                                    @endforeach
                                    @endif

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="input-group input-group-warning">
                        <textarea type="text" rows="5" class="form-control form-control-bold" placeholder="INSERT NOTE HERE" wire:model.defer="memo_note" required></textarea>
                    </div>
                    <button class="btn btn-danger float-end mt-2 mb-2" wire:click.prevent="save_note" type="button">SAVE MEMO. NOTE</button>
                </div>
            </div>
        </div>

        <div class="card mt-5">
            <div class="card-header mx-auto">
                <h3>NON-TEACHING PAYROLL</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="input-group input-group-warning">
                            <input type="text" class="form-control form-control-bold" placeholder="INSERT HERE THE MEMO NUMBER" wire:model.defer="non_memo_num" required>
                            <div class="input-group-append">
                                <button class="btn btn-danger" wire:click.prevent="non_save_memo" type="button">SAVE MEMO. NUM</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-6 py-2 text-end">
                        <a class="btn btn-warning" href="{{route('glps.print-non-teaching-payroll', ['hash_id' => $hash_id])}}" target="_blank">NON-TEACHING PAYROLL DATA</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-12">
                        <div class="table-responsive">
                            <table class="table table-sm text-nowrap mb-0 table-centered table-dark" width="100%">
                                <thead>
                                    <tr>
                                        <th>ACTION</th>
                                        <th>#</th>
                                        <th>NAME OF INSTRUCTOR</th>
                                        <th>RANK</th>
                                        <th>ASSIGNMENT / COURSE CONDUCTED</th>
                                        <th>NO. OF DAY</th>
                                        <th>NO. OF HR/OT</th>
                                        <th>MIN. LATE</th>
                                        <th>DEDUCTION</th>
                                        <th>RATE PER DAY</th>
                                        <th>RATE PER HR</th>
                                        <th>SUBTOTAL</th>
                                        <th>TOTAL</th>
                                    </tr>
                                </thead>

                                <tbody class="" style="font-size: 11px;">
                                    @if($others_payrolls->count() == 0)
                                    <tr>
                                        <td class="text-center" colspan="15">NO DATA FOUND</td>
                                    </tr>
                                    @else
                                    @foreach($others_payrolls as $index => $payroll_log)
                                    <tr>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button id="" type="button" class="btn btn-info btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                                <div class="dropdown-menu" aria-labelledby="">
                                                    <button class="dropdown-item" wire:click="editSchedule({{ $payroll_log->id }})" data-bs-toggle="modal" data-bs-target="#movetomodal" wire:key="{{ $payroll_log->id }}"><i class="bi bi-arrow-right" style="font-size: 1em;"></i>&nbsp;Move to</button>
                                                    <button class="dropdown-item" wire:click="showAssignedDescription({{ $payroll_log->id }})" data-bs-toggle="modal" data-bs-target="#assigndescription" wire:key="{{ $payroll_log->id }}"><i class="bi bi-plus" style="font-size: 1em;"></i>&nbsp;Assign Description</button>
                                                    <button class="dropdown-item" wire:click="confirm_delete({{$payroll_log->id}})" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="bi bi-trash" style="font-size: 1em;"></i>&nbsp;Delete Record</button>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $payroll_log->user->formal_name() }}</td>
                                        @if(optional(optional($payroll_log->user)->instructor)->rank)
                                        <td>{{ $payroll_log->user->instructor->rank->rankacronym }} - {{ $payroll_log->user->instructor->rank->rank }}</td>
                                        @else
                                        <td>NEED TO ASSIGN RANK</td>
                                        @endif
                                        <td> @if($payroll_log->course_id && $payroll_log->description_id)
                                            {{ optional($payroll_log->payrolldesc)->description }}
                                            @elseif(optional($payroll_log)->course_id)
                                            {{ $payroll_log->course->coursecode }} - {{ $payroll_log->course->coursename }}
                                            @elseif(optional($payroll_log)->description_id)
                                            {{ $payroll_log->payrolldesc->description }}
                                            @endif
                                        </td>
                                        <td>{{ $payroll_log->no_day }}</td>
                                        <td>{{ $payroll_log->no_ot }}</td>
                                        <td>{{ $payroll_log->no_late }}</td>
                                        <td>{{ number_format($payroll_log->deduction, 2) }}</td>
                                        <td>{{ number_format($payroll_log->rate_per_day, 2) }}</td>
                                        <td>{{ number_format($payroll_log->rate_per_hr, 2) }}</td>
                                        <td>{{ number_format($payroll_log->subtotal, 2) }}</td>
                                        <td>{{ number_format($payroll_log->total, 2) }}</td>
                                    </tr>
                                    @endforeach
                                    @endif

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="input-group input-group-warning">
                        <textarea type="text" rows="5" class="form-control form-control-bold" placeholder="INSERT NOTE HERE" wire:model.defer="non_memo_note" required></textarea>
                    </div>
                    <button class="btn btn-danger float-end mt-2 mb-2" wire:click.prevent="non_save_note" type="button">SAVE MEMO. NOTE</button>
                </div>
            </div>
        </div>

        <div class="card mt-5">
            <div class="card-header mx-auto">
                <h3>UNASSIGNED PAYROLL</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-12">
                        <div class="text-end">
                            <button class="btn btn-dark mb-2" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#addattendancemodal">ADD ATTENDANCE</button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm text-nowrap mb-0 table-centered table-dark" width="100%">
                                <thead>
                                    <tr>
                                        <th>ACTION</th>
                                        <th>#</th>
                                        <th>DATE</th>
                                        <th>DAY</th>
                                        <th>#/NAME OF INSTRUCTOR</th>
                                        <th>RANK</th>
                                        <th>ASSIGNMENT / COURSE CONDUCTED</th>
                                        <th>TIME IN</th>
                                        <th>TIME OUT</th>
                                        <th>LOG TYPE</th>
                                        <th>REGULAR</th>
                                        <th>LATE</th>
                                        <th>UNDERTIME</th>
                                        <th>OVERTIME</th>
                                        <th>STATUS</th>
                                    </tr>
                                </thead>

                                <tbody class="" style="font-size: 11px;">
                                    @if($unasigned_payrolls->count() == 0)
                                    <tr>
                                        <td class="text-center" colspan="15">NO DATA FOUND</td>
                                    </tr>
                                    @else
                                    @foreach($unasigned_payrolls as $index => $payroll_log)
                                    <tr>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button id="" type="button" class="btn btn-info btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                                <div class="dropdown-menu" aria-labelledby="">
                                                    <button class="dropdown-item" wire:click="showAssignedCourse({{ $payroll_log->id }})" data-bs-toggle="modal" data-bs-target="#assigncourse"><i class="bi bi-plus" style="font-size: 1em;"></i>&nbsp;Assign Course</button>
                                                    <button class="dropdown-item" wire:click="showEditAttendance({{ $payroll_log->id }})" data-bs-toggle="modal" data-bs-target="#edit_attendance"><i class="bi bi-gear" style="font-size: 1em;"></i>&nbsp;Edit Attendance</button>
                                                    <button class="dropdown-item" wire:click="delete_attendance({{ $payroll_log->id }})"><i class="bi bi-trash" style="font-size: 1em;" wire:key="{{ $payroll_log->id }}"></i>&nbsp;Delete Attendance</button>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $payroll_log->created_date }}</td>
                                        <td>{{ $payroll_log->formatted_created_date }}</td>
                                        <td>{{$payroll_log->user->user_id}} - {{ $payroll_log->user->formal_name() }}</td>
                                        @if(optional(optional($payroll_log->user)->instructor)->rank)
                                        <td>{{ $payroll_log->user->instructor->rank->rankacronym }} - {{ $payroll_log->user->instructor->rank->rank }}</td>
                                        @else
                                        <td>NEED TO ASSIGN RANK</td>
                                        @endif
                                        @if(optional($payroll_log->course)->FullCourseName)
                                        <td>{{ $payroll_log->course->FullCourseName }}</td>
                                        @else
                                        <td>NO ASSIGNMENT COURSE</td>
                                        @endif
                                        <td>{{ $payroll_log->time_in }}</td>
                                        <td>{{ $payroll_log->time_out }}</td>
                                        <td>{{ $payroll_log->regular }}</td>
                                        <td>{{ $payroll_log->late }}</td>
                                        <td>{{ $payroll_log->undertime }}</td>
                                        <td>{{ $payroll_log->overtime }}</td>
                                        <td>{{ $payroll_log->time_type }}</td>
                                        <td>{{ $payroll_log->log_status }}</td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('livewire.admin.payroll.a-payroll-modal')
        @include('livewire.admin.payroll.a-payroll-desc-modal')
        @include('livewire.admin.payroll.a-payroll-move-to-modal')
        @include('livewire.admin.payroll.add-payroll-modal-component')
        @include('livewire.admin.payroll.add-adjustment-rate-modal-component')


        <div wire:ignore.self class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="exampleModalScrollableTitle">DELETE THE RECORD</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <form wire:submit.prevent="delete_time_log">
                                @csrf
                                <div class="mt-2 col-lg-12">
                                    <label class="form-label" for="">Are you sure you want to delete the record? <small><i>(It will update the payroll once it generated again.)</i></small></label><br>
                                </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        <button type="submit" type="button" class="btn btn-danger">Yes</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="edit_attendance" tabindex="-1" role="dialog" aria-labelledby="edit_attendance" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="exampleModalScrollableTitle">EDIT ATTENDANCE</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <form wire:submit.prevent="saveshowEditAttendance">
                                @csrf
                                <div class="mt-2 col-lg-12">
                                    <label class="form-label" for="">Time in <small><i>(Assign time in to apply it to the payroll.)</i></small></label><br>
                                    <input type="time" class="form-control" wire:model="time_in">
                                </div>
                                <div class="mt-2 col-lg-12">
                                    <label class="form-label" for="">Time out <small><i>(Assign time out to apply it to the payroll.)</i></small></label><br>
                                    <input type="time" class="form-control" wire:model="time_out">
                                </div>
                                <div class="mt-2 col-lg-12">
                                    <label class="form-label" for="">Status <small><i>(for request, approval, declined)</i></small></label><br>
                                    <select class="col-lg-12 form-select" wire:model.defer="request_id" required>
                                        <option value="">Select option</option>
                                        <option value="1">Request</option>
                                        <option value="2">Approved</option>
                                        <option value="3">Declined</option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="mt-2 col-md-6">
                                        <label class="form-label" for="">Regular</label><br>
                                        <input type="text" class="form-control" wire:model="input_regular">
                                    </div>
                                    <div class="mt-2 col-md-6">
                                        <label class="form-label" for="">Late</label><br>
                                        <input type="text" class="form-control" wire:model="input_late">
                                    </div>
                                    <div class="mt-2 col-md-6">
                                        <label class="form-label" for="">Undertime</label><br>
                                        <input type="text" class="form-control" wire:model="input_undertime">
                                    </div>
                                    <div class="mt-2 col-md-6">
                                        <label class="form-label" for="">Overtime</label><br>
                                        <input type="text" class="form-control" wire:model="input_overtime">
                                    </div>
                                </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" type="button" class="btn btn-success">Save</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="addattendancemodal" tabindex="-1" role="dialog" aria-labelledby="addattendancemodal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="exampleModalScrollableTitle">ADD TIME IN/OUT</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <form wire:submit.prevent="submit_attendance">
                                @csrf
                                <div class="mt-2 col-lg-12">
                                    <label class="form-label" for="">Instructor</label><br>
                                    <select class="col-lg-12 form-select" wire:model.defer="instructor_id" required>
                                        <option value="">Select option</option>
                                        @foreach ($instructor_data as $instructor)
                                        <option value="{{ $instructor->user_id }}">{{ strtoupper($instructor->formal_name()) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mt-2 col-lg-12">
                                    <label class="form-label" for="">Assign course </label><br>
                                    <select class="col-lg-12 form-select" wire:model.defer="course_id" required>
                                        <option value="">Select option</option>
                                        @foreach ($course_data as $course)
                                        <option value="{{ $course->courseid }}">{{ strtoupper($course->FullCourseName) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mt-2 col-lg-12">
                                    <label class="form-label" for="">Date</label><br>
                                    <input type="date" id="date" name="date" class="col-lg-12 form-control" wire:model.defer="input_date" required>
                                </div>
                                <div class="row">
                                    <div class="mt-2 col-lg-6">
                                        <label class="form-label" for="">Time in </label><br>
                                        <input type="time" class="form-control" wire:model="time_in">
                                    </div>
                                    <div class="mt-2 col-lg-6">
                                        <label class="form-label" for="">Time out </label><br>
                                        <input type="time" class="form-control" wire:model="time_out">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="mt-2 col-md-6">
                                        <label class="form-label" for="">Regular</label><br>
                                        <input type="text" class="form-control" wire:model="input_regular">
                                    </div>
                                    <div class="mt-2 col-md-6">
                                        <label class="form-label" for="">Late</label><br>
                                        <input type="text" class="form-control" wire:model="input_late">
                                    </div>
                                    <div class="mt-2 col-md-6">
                                        <label class="form-label" for="">Undertime</label><br>
                                        <input type="text" class="form-control" wire:model="input_undertime">
                                    </div>
                                    <div class="mt-2 col-md-6">
                                        <label class="form-label" for="">Overtime</label><br>
                                        <input type="text" class="form-control" wire:model="input_overtime">
                                    </div>
                                </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" type="button" class="btn btn-info">Add</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        function confirmation_delete() {
            if (confirm('Are you sure you want to delete it?')) {
                Livewire.emit('delete_all_time_log');
            }
        }
    </script>
    @endpush
</div>