<div class="row">
    <div class="col-lg-12">
        <h3 class="mt-2">Select Date Range:</h3>
        <form action="" wire:submit.prevent="dailysearch" id="dailysearch">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <label for=""><h4>Date From</h4></label>
                    <input type="text" class="form-control flatpickr" required wire:model.defer="dailydatefrom" placeholder="Select date from">
                </div>
                <div class="col-md-6">
                    <label for=""><h4>Date To</h4></label>
                    <input type="text" class="form-control flatpickr" required wire:model.defer="dailydateto" placeholder="Select date to">
                </div>
                <div class="col-md-3 mt-1">
                    <label for=""><h4>Status</h4></label>
                    <select name="" id="" class="form-select" wire:model.defer="dailystatus">
                        <option value="all">All</option>
                        @foreach ($reservationstatus as $status)
                            <option value="{{$status->id}}">{{$status->status}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mt-1">
                    <label for=""><h4>Company</h4></label>
                    <select name="" id="" class="form-select" wire:model.defer="dailycompany">
                        <option value="0">All</option>
                        @foreach ($company as $companys)
                            <option value="{{$companys->companyid}}">{{$companys->company}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mt-1">
                    <label for=""><h4>Courses</h4></label>
                    <select name="" id="" class="form-select" wire:model.defer="dailycourses">
                        <option value="0">All</option>
                        @foreach ($loadcourses as $data)
                            <option value="{{ $data->courseid }}">{{ $data->coursecode }} / {{ $data->coursename }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mt-1">
                    <label for=""><h4>Payment Method</h4></label>
                    <select name="" id="" class="form-select" wire:model.defer="dailypaymethod">
                        <option value="0">All</option>
                        @foreach ($paymentmode as $paymentmodes)
                            <option value="{{$paymentmodes->paymentmodeid}}">{{$paymentmodes->paymentmode}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>
</div>