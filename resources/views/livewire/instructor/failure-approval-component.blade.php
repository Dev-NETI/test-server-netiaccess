<div class="container-fluid mt-5">
    <span wire:loading>
        <livewire:components.loading-screen-component />
    </span>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-9">
                            <h4>Failure For Approval</h4>
                        </div>
                        <div class="col-lg-3">
                            <div class="input-group float-end">
                                <span class="input-group-text">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" wire:model='searchItem' class="form-control" placeholder="Search Instructor Name">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-resposive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <!-- <th><input type="checkbox" wire:model='batchChecked'></th> -->
                                    <th>Filed Date</th>
                                    <th>Instructor Name</th>
                                    <th>Course</th>
                                    <th>Type</th>
                                    <th>Time In / Time Out</th>
                                    <th>Reason</th>
                                    <th> <select class="form-select w-auto" wire:model="failure_status">
                                            <option value="" selected>All</option>
                                            <option value="1"> Pending</option>
                                            <option value="2"> Approved</option>
                                            <option value="3">Disapprove</option>
                                        </select></th>
                                    <th>Comment (Disapprove)</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($failureLogs as $index => $item)
                                <tr @if ($item->status == 1)
                                    class=""
                                    @elseif ($item->status == 2)
                                    class="table-success"
                                    @elseif ($item->status == 3)
                                    class="table-danger"
                                    @endif
                                    >
                                    <!-- <td>
                                        @if ($item->status == 1)
                                        <input type="checkbox" wire:model='checkBox.{{$item->id}}' class="form-checkbox">
                                        @endif
                                    </td> -->
                                    <td>{{ date('F d, Y H:i', strtotime($item->created_at)) }}</td>
                                    <td>{{ $item->user->f_name }} {{ $item->user->l_name }}</td>
                                    <td>{{ $item->courseDetails->coursecode }} {{ $item->courseDetails->coursename }}
                                    </td>
                                    <td>{{ $item->type == 1 ? 'Time In' : 'Time Out' }}</td>
                                    <td>{{ date('F d, Y H:i', strtotime($item->dateTime)) }}</td>
                                    <td>{{ $item->remarks }}</td>
                                    <td>
                                        @if ($item->status == 1)
                                        <p class="badge text-secondary bg-light-info"> Pending</p>
                                        @elseif ($item->status == 2)
                                        <p class="badge text-secondary bg-light-dark"> Approved</p>
                                        @elseif ($item->status == 3)
                                        <p class="badge text-secondary bg-light-dark">Disapproved </p>
                                        @endif

                                    </td>
                                    <td>
                                        {{optional($item)->comment}}
                                    </td>
                                    <td>
                                        {{-- <button wire:click='updateStatus({{$item->id}}, 1, 0, 0, 0, 0)'
                                        class="btn btn-sm btn-warning p-1 m-1">Unset</button> --}}
                                        @if ($item->status == 1)
                                        <button wire:click='updateStatus({{$item->id}}, 2, "{{$item->dateTime}}", {{$item->course}}, {{$item->type}}, {{$item->user_id}})' class="btn btn-sm btn-success p-1 m-1">Approve</button>
                                        <!-- <button wire:click='updateStatus({{$item->id}}, 3, 0, 0, 0, 0)' class="btn btn-sm btn-danger p-1 m-1">Disapprove</button> -->
                                        <button class="btn btn-sm btn-danger p-1 m-1" wire:click="openModal({{$item->id}})" data-bs-toggle="modal" data-bs-target="#payrollModalDisapprove">Disapprove</button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if ($checkBox)
                        <div class="row">
                            <div class="col-lg-12">
                                <!-- {{-- <button wire:click="batchFunct(1)" class="btn btn-sm btn-warning">Batch
                                    Unset</button> --}}
                                <button wire:click="batchFunct(2)" class="btn btn-sm btn-success">Batch Approve</button>
                                <button wire:click="batchFunct(3)" class="btn btn-sm btn-danger">Batch
                                    Disapprove</button> -->
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="card-footer">
                    {{ $failureLogs->links('livewire.components.customized-pagination-link') }}
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="payrollModalDisapprove" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel" wire:model.defer="title">Disapproval</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="saveDisapprove" id="disapprove">
                        <div class="row gx-3">
                            <div class="col-md-12 col-12">
                                <div class="alert alert-danger">
                                    Add your comment to disapprove this request payroll attendance.
                                </div>
                            </div>
                            <div class="col-md-12 col-12">
                                <label for="">Comment:</label>
                                <textarea class="form-control" name="" id="" cols="30" rows="10" wire:model.defer="comment"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="disapprove" class="btn btn-primary">Proceed</button>
                </div>
            </div>
        </div>
    </div>
</div>