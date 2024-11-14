<div class="row" wire:ignore>
    <div class="col-lg-12">
        <h3 class="mt-2">Select Date Range:</h3>
        <form action="" wire:submit.prevent="weeklysearch" id="weeklysearch">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <label for=""><h4>Date From</h4></label>
                    <input type="text" class="form-control flatpickr" wire:model.defer="weeklydatefrom" placeholder="Select date from">
                </div>
                <div class="col-md-6">
                    <label for=""><h4>Date To</h4></label>
                    <input type="text" class="form-control flatpickr" wire:model.defer="weeklydateto" placeholder="Select date to">
                </div>
                <div class="col-md-3 mt-1">
                    <label for=""><h4>Status</h4></label>
                    <select name="" id="" class="form-select" wire:model.defer="weeklystatus">
                        <option value="all">All</option>
                        @foreach ($reservationstatus as $data)
                            <option value="{{ $data->id }}">{{ $data->status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mt-1">
                    <label for=""><h4>Company</h4></label>
                    <select name="" id="" class="form-select" wire:model.defer="weeklycompany">
                        <option value="0">All</option>
                        @foreach ($selectweeklycompany as $data)
                            <option value="{{ $data->companyid }}">{{ $data->company }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mt-1">
                    <label for=""><h4>Courses</h4></label>
                    <select name="" id="" class="form-select" wire:model.defer="weeklycourses">
                        <option value="0">All</option>
                        @foreach ($loadcourses as $data)
                            <option value="{{ $data->courseid }}">{{ $data->coursecode }} / {{ $data->coursename }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mt-1">
                    <label for=""><h4>Payment Method</h4></label>
                    <select name="" id="" class="form-select" wire:model.defer="weeklypaymethod">
                        <option value="0">All</option>
                        @foreach ($selectpaymethod as $data)
                            <option value="{{ $data->paymentmodeid }}">{{ $data->paymentmode }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>
</div>