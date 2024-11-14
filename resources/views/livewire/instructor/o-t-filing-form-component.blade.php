<div class="container-fluid mt-2">
    <div class="row border-bottom">
        <label for="" class="h2">File Overtime</label>

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Overtime</li>
            </ol>
        </nav>
    </div>

    <div class="row m-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <label for="" class="h4">Select Overtime Date</label>
                        <input type="date" wire:model='dateLogs' class="form-control flatpickr"
                            placeholder="Select Work Date">
                    </div>
                    <hr>
                    @if ($showForm)
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="" class="form-label h5">Start Time</label>
                                <input type="time" step="1" wire:model='overtimeStart' class="form-control"
                                    placeholder="Select overtime start">
                            </div>
                            <div class="col-lg-6">
                                <label for="" class="form-label h5">End Time</label>
                                <input type="time" step="1" wire:model='overtimeEnd' class="form-control"
                                    placeholder="Select overtime end">
                            </div>
                            <div class="col-lg-12 d-grid mt-2">
                                <button wire:click="submitForm" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Modal --}}
    <div wire:ignore.self class="modal fade" id="updateOvertimeModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4" id="exampleModalCenterTitle">Update Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <label for="" class="h4">Select Overtime Date</label>
                            <input type="date" wire:model='updateDateLogs' class="form-control flatpickr"
                                placeholder="Select Work Date">
                        </div>
                        <hr>
                        <div class="col-lg-12 mt-2">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="" class="form-label h5">Start Time</label>
                                    <input type="time" step="1" wire:model='updateStart' class="form-control"
                                        placeholder="Select overtime start">
                                </div>
                                <div class="col-lg-6">
                                    <label for="" class="form-label h5">End Time</label>
                                    <input type="time" step="1" wire:model='updateEnd' class="form-control"
                                        placeholder="Select overtime end">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-lg-12 d-grid mt-2">
                        <button wire:click="updateOvertime({{$updateID}})" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal --}}
    <div wire:ignore.self class="modal fade" id="courseSelect" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4" id="exampleModalCenterTitle">2 Attendance Detected - Please select
                        courses</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <label for="" class="h4">Courses Available</label>
                            <select class="form-select" wire:model="selectedInstructorTimeLogs" name="" id="">
                                <option value="" selected>Select Course</option>
                                @foreach ($courses as $item)
                                <option value="{{$item->id}}">{{ $item->course->coursecode }} / {{
                                    $item->course->coursename }}</option>
                                @endforeach
                            </select>
                            @error('selectedInstructorTimeLogs')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-lg-12 d-grid mt-2">
                        <button wire:click="validateModels" class="btn btn-primary">Select</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row m-3">
        <div class="card">
            <div class="card-header">
                <h4>My Forms</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-0 table-centered" width="100%">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Date Filed</th>
                                <th>Work Date</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Status</th>
                                <th>Remarks</th>
                                <th>Approver</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($instructorovertime as $index => $item)
                            @php
                            $index++;
                            @endphp
                            <tr>
                                <td>{{ $index }}</td>
                                <td>{{ $item['datefiled'] }}</td>
                                <td>{{ $item['workdate'] }}</td>
                                <td>{{ date('h:i a', strtotime($item['overtime_start'])) }}</td>
                                <td>{{ date('h:i a', strtotime($item['overtime_end'])) }}</td>
                                <td>
                                    @switch($item['status'])
                                    @case(2)
                                    <span class="badge bg-success">Approved</span>
                                    @break
                                    @case(3)
                                    <span class="badge bg-danger">Disapproved</span>
                                    @break
                                    @default
                                    <span class="badge bg-warning">Pending</span>
                                    @endswitch
                                </td>
                                <td class="{{$item->remarks == null ? 'text-danger' : ''}}">{{ $item->remarks != null ?
                                    $this->remarks : 'No remarks' }}</td>
                                <td>{{ $item->personApprover->f_name.' '.$item->personApprover->l_name }}</td>
                                <td>
                                    <button class="btn btn-info {{ $item['status'] == 1 ? '' : 'disabled' }}"
                                        wire:click="updateLogs({{$item['id']}})">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button class="btn btn-danger {{ $item['status'] == 1 ? '' : 'disabled' }}"
                                        wire:click="softDelete({{$item['id']}})" data-bs-toggle="modal"
                                        data-bs-target="#exampleModalCenter">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>