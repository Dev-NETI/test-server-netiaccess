<div class="col-md-12 mt-2">
    <div class="row">

    </div>
    <div class="col-4 col-md-4 col-lg-4 mt-3">
        <div class="d-flex justify-content-between  text-center ">

            <label class="form-label" for="">Date Range:</label>

        </div>

        @props([
        'options' => "{ mode:'range',
        dateFormat: 'Y-m-d',
        altInput:true, }",
        ])

        <div wire:ignore>
            <input x-data x-init="flatpickr($refs.input, {{ $options }});" x-ref="input" class="form-control mb-2" type="text" data-input wire:model.debounce.1000ms="date_range" />
        </div>
    </div>
    <div class="col-4 col-md-4 col-lg-4 mt-3">

        <livewire:admin.payroll.a-payroll-attendance-component wire:key="{{$change}}" :data="$exportData" />
    </div>

    <x-table-list :data="$timeData" :total="$dataCount" wire:model="search">
        <thead>
            <x-th>Action</x-th>
            <x-th>Date</x-th>
            <x-th>Day</x-th>
            <x-th>ID</x-th>
            <x-th>Name</x-th>
            <x-th>Assigment Course</x-th>
            <x-th>Time in</x-th>
            <x-th>Time Out</x-th>
            <x-th>
                <select class="form-select w-auto" wire:model="filter_time_data">
                    <option value="0" selected> Time in and out</option>
                    <option value="1">No time In</option>
                    <option value="2">No time Out</option>
                </select>
            </x-th>
            <x-th>Regular</x-th>
            <x-th>Late <i>(per minute)</i></x-th>
            <x-th>Undertime</x-th>
            <x-th>Overtime</x-th>
            <x-th>Status</x-th>
            <x-th>Created By</x-th>
            <x-th>Created At</x-th>
        </thead>
        <tbody>
            @foreach ($timeData as $item)
            <livewire:components.instructor-time-log.time-log-list-item-component :data="$item" wire:key="{{ $item->id }}" />
            @endforeach
        </tbody>
    </x-table-list>

    @include('livewire.admin.payroll.a-payroll-modal')

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