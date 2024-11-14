<div class="container-fluid mt-2">
    <div class="row border-bottom">
        <label for="" class="h2">Overtime Approval</label>

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Approval </li>
        </ol>
        </nav>
    </div>

    {{-- Modal --}}
    <div wire:ignore.self class="modal fade" id="updateOvertimeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                        <input type="date" wire:model='updateDateLogs' class="form-control flatpickr" placeholder="Select Work Date">
                    </div>
                    <hr>
                    <div class="col-lg-12 mt-2">
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="" class="form-label h5">Start Time</label>
                                <input type="time" step="1" wire:model='updateStart' class="form-control" placeholder="Select overtime start">
                            </div>
                            <div class="col-lg-6">
                                <label for="" class="form-label h5">End Time</label>
                                <input type="time" step="1" wire:model='updateEnd' class="form-control" placeholder="Select overtime end">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-lg-12 d-grid mt-2">
                    <button class="btn btn-primary">Submit</button>
                </div>
            </div>
          </div>
        </div>
    </div>

    <div class="row m-3">
        <div class="card">
            <div class="card-header">
                <h4>File Overtime</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-0 table-centered" width="100%">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Instructor Name</th>
                                <th>Date Filed</th>
                                <th>Work Date</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($overtime as $index => $item)
                                @php
                                    $index++;
                                @endphp
                                <tr>
                                    <td>{{ $index }}</td>
                                    <td>{{ $item->user->f_name }} {{ $item->user->l_name }}</td>
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
                                    <td>
                                        @if ($item['status'] == 1)
                                            <button class="btn btn-info" title="Approve" wire:click="approveOT({{$item['id']}})">
                                            <i class="bi bi-check2-circle"></i>
                                            </button>
                                            <button class="btn btn-danger" title="Disapprove" wire:click="openRemarksModal({{$item['id']}})" data-bs-toggle="modal" data-bs-target="#exampleModalCenter">
                                                <i class="bi bi-x"></i>
                                            </button>
                                            
                                        @else
                                            <button class="btn btn-info {{ $item['status'] == 3 ? '' : 'disabled' }}" title="Approve" wire:click="approveOT({{$item['id']}})">
                                            <i class="bi bi-check2-circle"></i>
                                            </button>
                                            <button class="btn btn-danger {{ $item['status'] == 2 ? '' : 'disabled' }}" title="Disapprove" wire:click="openRemarksModal({{$item['id']}})" data-bs-toggle="modal" data-bs-target="#exampleModalCenter">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="disapprovedRemarksModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalCenterTitle">Disapprove Remarks</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            </button>
            </div>
            <div class="modal-body">
            <div class="form-group">
                <label for="exampleFormControlTextarea1">Remarks</label>
                <textarea class="form-control" wire:model="remarks" id="exampleFormControlTextarea1" rows="3"></textarea>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" wire:click="disapproveOT" class="btn btn-primary">Save changes</button>
            </div>
        </div>
        </div>
    </div>
</div>
