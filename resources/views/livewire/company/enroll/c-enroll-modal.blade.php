<div wire:ignore.self class="modal right fade" id="exampleModal-2" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">ENROLL A CREW</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="enroll">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="mb-3">
                                <label for="" class="form-label">Date online</label>
                                @if ($dateonlinefrom)
                                <input id="" class="form-control" type="text"
                                    placeholder="{{ $dateonlinefrom ? date('d F Y', strtotime($dateonlinefrom)) . ' - ' . date('d F Y', strtotime($dateonlineto)) : '' }}"
                                    disabled>
                                @else
                                <input id="" class="form-control" type="text" placeholder="Not specified" disabled>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12">
                            <div class="mb-3">
                                <label for="" class="form-label">Date onsite</label>
                                @if ($dateonsitefrom)
                                <input id="" class="form-control" type="text"
                                    placeholder="{{ $dateonsitefrom ? date('d F Y', strtotime($dateonsitefrom)) . ' - ' . date('d F Y', strtotime($dateonsiteto)) : '' }}"
                                    disabled>
                                @else
                                <input id="" class="form-control" type="text" placeholder="Not specified" disabled>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="" class="form-label">Max allowed trainees</label>
                            @if ($schedule)
                            <input type="text" class="form-control mb-3" disabled
                                placeholder="{{ $schedule->course->maximumtrainees }}" name="" id=""></input>
                            @else
                            <input type="text" class="form-control mb-3 " disabled name="" id=""></input>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label for="" class="form-label">Number of Enrolled</label>
                            <input type="text" class="form-control mb-3 " disabled wire:model="numberofenroled" name=""
                                id=""></input>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Transportation</label>
                                <select class="form-select text-dark" wire:model="bus_id">
                                    <option value="0">None</option>
                                    <option value="1">Bus round trip</option>
                                    @if ($schedule)
                                    @if ($schedule->course->type->coursetypeid != 1)
                                    <option value="2">Daily bus round trip</option>
                                    @endif
                                    @endif
                                </select>
                                <small>Check your preferred transportation.</small>
                            </div>
                            <div class="form-group mb-3">
                                <label for="courseTitle" class="form-label">ROOM</label>
                                <select class="form-control" wire:model="selectedDorm">
                                    <option value="">Select a room</option>
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
                                            <input class="form-control flatpickr flatpickr-input active" type="date"
                                                placeholder="Select Date" aria-describedby="basic-addon2"
                                                wire:model.defer="room_start">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="courseTitle" class="form-label">Check-out
                                            Date<span class="text-danger">*</span></label>
                                        <div class="input-group me-3">
                                            <input class="form-control flatpickr flatpickr-input active" type="date"
                                                placeholder="Select Date" aria-describedby="basic-addon2"
                                                wire:model="room_end">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">ENROLL</button>
            </div>
        </div>
    </div>
    </form>

</div>