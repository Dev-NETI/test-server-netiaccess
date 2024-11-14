<div class="row">
    <div class="col-md-3">
        <x-request-message />
        <x-input type="text" :model="$qrCode" label="Scan" wire:model="qrCode" class="mt-2" autofocus />
        <button class="btn btn-info mt-2" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#addattendancemodal">ADD ATTENDANCE</button>
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
                                <label class="form-label" for="">Date</label><br>
                                <input type="date" id="date" name="date" class="col-lg-12 form-control" wire:model.defer="input_date" required>
                            </div>
                            <div class="mt-2 col-lg-12">
                                <label class="form-label" for="">Time in/out</label>
                                <small><i>(It automatically assigned based on current time.)</i></small>
                                <input type="time" id="timeIn" name="timeIn" class="col-lg-12 form-control" wire:model.defer="input_timein">
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