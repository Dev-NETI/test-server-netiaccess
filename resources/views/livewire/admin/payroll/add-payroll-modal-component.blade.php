    <div wire:ignore.self class="modal fade" id="addpayrollmodal" tabindex="-1" role="dialog" aria-labelledby="addpayrollmodal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalScrollableTitle">ADD PAYROLL DATA</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form wire:submit.prevent="submit_payroll">
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

                            <div class="col-lg-12 mt-2">
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

                            <div class="row">
                                <div class="mt-2 col-md-6">
                                    <label class="form-label" for="">Number of Day</label><br>
                                    <input type="text" class="form-control" wire:model="number_day">
                                </div>
                                <div class="mt-2 col-md-6">
                                    <label class="form-label" for="">Number of Hour</label><br>
                                    <input type="text" class="form-control" wire:model="number_hour">
                                </div>
                                <div class="mt-2 col-md-6">
                                    <label class="form-label" for="">Number of OT</label><br>
                                    <input type="text" class="form-control" wire:model="number_overtime">
                                </div>
                                <div class="mt-2 col-md-6">
                                    <label class="form-label" for="">Number of Min. Late</label><br>
                                    <input type="text" class="form-control" wire:model="number_late">
                                </div>

                                <div class="mt-2 col-md-4">
                                    <label class="form-label" for="">Rate</label><br>
                                    <input type="text" class="form-control" wire:model="display_rate" disabled>
                                </div>

                                <div class="mt-2 col-md-4">
                                    <label class="form-label" for="">Rate per Hr</label><br>
                                    <input type="text" class="form-control" wire:model="display_rate_hr" disabled>
                                </div>

                                <div class="mt-2 col-md-4">
                                    <label class="form-label" for="">Total</label><br>
                                    <input type="text" class="form-control" wire:model="display_total" disabled>
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