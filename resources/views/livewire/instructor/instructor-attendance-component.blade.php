<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header" wire:ignore>
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Attendance</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">My Forms </a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content" id="myTabContent">
                    <div wire:ignore.self class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-6">
                                    <h3>Attendance</h3>
                                </div>
                            </div>
                        </div>
                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" id="date-from" for="date">Date From</span>
                                        <input aria-describedby="date-from" wire:model="dateFrom" class="form-control flatpickr" type="text">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-group mb-3">
                                        <span for="date" class="input-group-text">Date To</span>
                                        <input class="form-control flatpickr" wire:model="dateTo" type="text">
                                    </div>
                                </div>
                                {{-- <div class="col-lg-2 text-center">
                                    <button wire:click="filterAttendance" class="btn btn-primary">Filter</button>
                                </div> --}}
                            </div>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Course Instructed</th>
                                        <th>Time In</th>
                                        <th>Time Out</th>
                                        <th>Regular Hr</th>
                                        <th>Overtime</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($logs->count() == 0)
                                    <tr>
                                        <td colspan="6" class="text-center">No Data Available</td>
                                    </tr>
                                    @else
                                    @foreach ($logs as $item)
                                    <tr>
                                        <td>{{ $item->created_date }}</td>
                                        @if ($item->course != null)
                                        <td>{{ ($item->course)->coursecode }} / {{ $item->course->coursename }}</td>
                                        @else
                                        <td>No Course</td>
                                        @endif
                                        <td class="@if($item->time_in == null) text-danger @endif">{{ $item->time_in != null ? $item->time_in : 'No Time In' }}</td>
                                        <td class="@if($item->time_out == null) text-danger @endif">{{ $item->time_out != null ? $item->time_out : 'No Time Out' }}</td>
                                        <td>{{ $item->regular }}</td>
                                        <td>{{ $item->overtime != null ? $item->overtime." hrs" : '0 hr' }}</td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            {{ $logs->links('livewire.components.customized-pagination-link') }}
                        </div>
                    </div>
                    <div wire:ignore.self class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-6">
                                    <h3>My Forms</h3>
                                </div>
                                <div class="col-lg-6 text-end">
                                    <button wire:click="createModal" class="btn btn-success">Create Form</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Date Filed</th>
                                        <th>Course Instructed</th>
                                        <th>Time In / Time Out</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Remarks</th>
                                        <th>Comment <i class="text-danger">(applicable to disapproved)</i></th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($failureLogs->count() == 0)
                                    <tr>
                                        <td colspan="8" class="text-center">No Data Available</td>
                                    </tr>
                                    @else
                                    @foreach ($failureLogs as $item)
                                    <tr>
                                        <td>{{ date('F d, Y', strtotime($item->created_at)) }}</td>
                                        <td>{{ $item->courseDetails->coursecode }} / {{ $item->courseDetails->coursename
                                            }}</td>
                                        <td>{{ date('F d, Y H:i', strtotime($item->dateTime)) }}</td>
                                        <td>{{ $item->type == 1 ? 'Time In' : 'Time Out' }}</td>
                                        <td>
                                            @if ($item->status == 1)
                                            <span class="badge bg-warning text-dark">Pending</span>
                                            @elseif ($item->status == 2)
                                            <span class="badge bg-success">Approved</span>
                                            @else
                                            <span class="badge bg-danger">Disapproved</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->remarks }}</td>
                                        <td>
                                            @if(optional($item)->comment)
                                            {{ $item->comment }}
                                            @else
                                            N/A
                                            @endif
                                        </td>
                                        <td>
                                            <button wire:click="editFailure({{$item->id}})" class="btn btn-sm btn-primary {{$item->status != 1 ? 'disabled' : ''}}">Edit</button>
                                            <button wire:click="deleteFailure({{$item->id}})" class="btn btn-sm btn-danger {{$item->status != 1 ? 'disabled' : ''}}">Delete</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            {{ $failureLogs->links('livewire.components.customized-pagination-link') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="attendanceModal" tabindex="-1" aria-labelledby="attendanceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="attendanceModalLabel">Attendance Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if ($IDtoUpdate)
                    <form wire:submit.prevent='updateFailure'>
                        @else
                        <form wire:submit.prevent='submitFailure'>
                            @endif
                            <div class="mb-3">
                                <label for="course" class="form-label">Course</label>
                                <select class="form-select" wire:model="failureCourse" id="course" aria-label="Default select example">
                                    <option selected>Select Course</option>
                                    @foreach ($courses as $course)
                                    <option value="{{ $course->courseid }}">{{ $course->coursecode }} / {{
                                        $course->coursename }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="dateTime" class="form-label">Date and Time</label>
                                <input type="datetime-local" wire:model="failureDateTime" placeholder="Select Date" class="form-control" id="dateTime">
                            </div>
                            <div class="mb-3">
                                <label for="type" class="form-label">Type</label>
                                <select class="form-select" wire:model="failureType" id="type" aria-label="Default select example">
                                    <option selected>Select Type</option>
                                    <option value="1">Time In</option>
                                    <option value="2">Time Out</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="remarks" class="form-label">Remarks</label>
                                <textarea wire:model="failureRemarks" class="form-control" id="remarks" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">{{ $IDtoUpdate != NULL ? 'Update' : 'Submit'
                                }}</button>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>