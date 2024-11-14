<div wire:ignore.self class="modal right fade" id="editenrollment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">UPDATE ENROLLMENT</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="enroll_save">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label for="" class="form-label">Course<span class="text-danger">*</span>
                                </label>
                                <input id="course" class="form-control" type="text" wire:model="course_title" placeholder="" disabled>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label for="" class="form-label">Batch<span class="text-danger">*</span></label>
                                <input id="batch" class="form-control" type="text" wire:model="batch" disabled>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12">
                            <div class="mb-3">
                                <label for="" class="form-label">Date online</label>
                                @if ($dateonline)
                                <input id="datefrom" class="form-control" type="text" wire:model="dateonline" disabled>
                                @else
                                <input id="dateto" class="form-control" type="text" placeholder="Not specified" disabled>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12">
                            <div class="mb-3">
                                <label for="" class="form-label">Date onsite</label>
                                @if ($dateonsite)
                                <input id="onsitefrom" class="form-control" type="text" wire:model="dateonsite" disabled>
                                @else
                                <input id="onsiteto" class="form-control" type="text" placeholder="Not specified" disabled>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="" class="form-label">Max allowed trainees</label>
                            <input type="text" class="form-control mb-3" disabled wire:model="maximumtrainees"></input>
                        </div>

                        <div class="col-md-6">
                            <label for="" class="form-label">Number of Enrolled</label>
                            <input type="text" class="form-control mb-3 " disabled wire:model="numberofenroled" id="numberofenroled"></input>
                        </div>

                        <div class="col-md-12">
                            @if(Auth::user()->u_type == 1)
                            <div class="form-group mb-3">
                                <label for="" class="form-label">Payment Mode<span class="text-danger">*</span></label>
                                <select class="form-select text-dark" wire:model.defer="payment_features" id="payment">
                                    <option value="">Select a payment method</option>
                                    <option value="1">Company Sponsored</option>
                                    <option value="2">Own Pay</option>
                                    <option value="3">Salary Deduction</option>
                                    <option value="4">NTIF Boarding Loan</option>
                                </select>
                                <small>Check your preferred payment.</small> <br>
                                @error('payment_features')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            @else
                            <div class="form-group mb-3">
                                <label for="" class="form-label">Payment Mode<span class="text-danger">*</span></label>
                                <select class="form-select text-dark" wire:model.defer="payment_features" id="payment">
                                    <option value="1" selected>Company Sponsored</option>
                                </select>
                                <small>Check your preferred payment.</small> <br>
                                @error('payment_features')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            @endif

                            <div class="form-group mb-3">
                                <label class="form-label">Transportation</label>
                                <select class="form-select text-dark" wire:model.defer="bus_id">
                                    <option value="">None</option>
                                    <option value="1">Bus round trip</option>
                                    <option value="2">Daily bus round trip</option>
                                </select>
                                <small>Check your preferred transportation.</small>
                            </div>
                            <div class="form-group mb-3">
                                <label for="courseTitle" class="form-label">ROOM</label>
                                <select class="form-control" wire:model.defer="selectedDorm">
                                    @foreach ($dorm as $d)
                                                <option value="{{ $d->dormid }}">{{ $d->dorm }}
                                                </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="courseTitle" class="form-label">Check-in
                                            Date<span class="text-danger">*</span></label>
                                        <div class="input-group me-3">
                                            <input class="form-control flatpickr flatpickr-input active" type="date" placeholder="Select Date" aria-describedby="basic-addon2" wire:model.defer="room_start">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="courseTitle" class="form-label">Check-out
                                            Date<span class="text-danger">*</span></label>
                                        <div class="input-group me-3">
                                            <input class="form-control flatpickr flatpickr-input active" type="date" placeholder="Select Date" aria-describedby="basic-addon2" wire:model.defer="room_end">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Save</button>
            </div>
        </div>
    </div>
    </form>

</div>