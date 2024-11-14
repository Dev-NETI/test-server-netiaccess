<div class="mb-3">
    <span wire:loading>
        <livewire:components.loading-screen-component />
    </span>
    <div class="row">
        @if($selectedPackage == 1 || $selectedPackage == 2 || $selectedPackage == 3)

        <div class="form-check ml-3">
            <input class="form-check-input ml-3" type="checkbox" value="" id="flexCheckDefault" wire:model="showAdditionalForm">
            <label class="form-check-label" for="flexCheckDefault">
                <h5> You wanto to avail a room with breakfast and dinner?</h5>
            </label>
        </div>

        @if ($showAdditionalForm)

        <div class="col-md-12">
            <label for="courseTitle" class="form-label">ROOM</label>
            <select class="form-control" name="" id="" wire:model="selectedDorm">
                <option value="">Select room</option>
                @foreach ($dorm as $d)
                <option value="{{$d->dormid}}">{{$d->dorm}}</option>
                @endforeach
            </select>
            <small>ADVISORY:</small>
            <small>One day to two nights stay at the dormitory. Requirement - <small style="color: red;">RAT and MARINA Safety Protocol</small> </small> <br>
            <small>More than nights stay at the dormitory. Requirement - <small style="color: red;">RT-PCR and Dormitory Safety Protocol</small></small>
        </div>
        <div class="col-md-6 mb-3">
            <label for="courseTitle" class="form-label">Check-in Date</label>
            <div class="input-group me-3">
                <input class="form-control flatpickr flatpickr-input active" type="date" placeholder="Select Date" aria-describedby="basic-addon2" wire:model="start_date">
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <label for="courseTitle" class="form-label">Check-out Date</label>
            <div class="input-group me-3">
                <input class="form-control flatpickr flatpickr-input active" type="date" placeholder="Select Date" aria-describedby="basic-addon2" wire:model="end_date">
            </div>
        </div>

        @endif
        @endif
        <!-- <div class="col-md-6 mb-3">
            <label for="courseTitle" class="form-label">Bus</label>
            <select class="form-control" name="" id="" wire:model="bus_status">
                <option value="0">Select</option>
                @foreach ($bus as $b)
                <option value="{{$b->busid}}">{{$b->bus}}</option>
                @endforeach
            </select>
        </div> -->

        <!-- <div class="col-md-6 mb-3">
            <label for="courseTitle" class="form-label">SRN: <small style="color: red;"><i>(yy/mm/dd) + 4 digits from MARINA</i></small></label>
            <input type="text" class="form-control" name="" placeholder="Enter SRN here" required="" wire:model="srn_num">
        </div> -->

    </div>
    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-danger mt-5" wire:click.prevent="create">
            Submit For Review
        </button>
    </div>
</div>