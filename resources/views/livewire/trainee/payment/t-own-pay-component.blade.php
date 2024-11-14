<div>
    <div class="mb-3">
        <span wire:loading>
            <livewire:components.loading-screen-component />
        </span>
        <div class="row">
            @if($selectedPackage == 2 || $selectedPackage == 3 || $selectedPackage == 4)

            <div class="form-check">
                <input class="form-check-input ml-3" type="checkbox" value="" id="flexCheckDefault" wire:model="showAdditionalForm">
                <label class="form-check-label" for="flexCheckDefault">
                    <h5> You wanto to avail a room with breakfast and dinner?</h5>
                </label>
            </div>

            @if ($showAdditionalForm)

            <div class="col-md-12">
                <label for="courseTitle" class="form-label">ROOM</label>
                <select class="form-control" name="" id="" wire:model="selectedDorm">
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

        </div>
    </div>


    <div class="mb-3">
        @if ($course_sched->numberofenroled < $course_sched->course->maximumtrainees)

        @else
        <div class="alert alert-warning" role="alert">
                It reached the maximum trainees of this training schedule. Select another schedule.
            </div>
        @endif

            <!-- Card -->
            <div class="card border-0">
                <!-- Card header -->
                <div class="card-header d-lg-flex justify-content-between align-items-center">
                    <div class="mb-3 mb-lg-0">
                        <h3 class="mb-0">OWN PAYMENT (BALANCE)</h3>
                        <p class="mb-0">
                            Here is list of course training fee/room/meals that you selected.
                        </p>
                    </div>
                    <div>
                        @if ($showAdditionalForm)
                        <span class="badge bg-success"><i class="mdi mdi-wallet"></i> Total Balance: ₱ {{number_format($total, 0, '.', ',')}}</span>
                        @else
                        <span class="badge bg-success"><i class="mdi mdi-wallet"></i> Total Balance: ₱ {{number_format($t_fee, 0, '.', ',')}}</span>

                        @endif
                    </div>
                </div>
                <!-- Card body -->
                <div class="card-body">
                    <div class="border-bottom pt-0 pb-5">
                        <div class="row mb-4">
                            <div class="col-lg-6 col-md-8 col-7 mb-2 mb-lg-0">
                                <span class="d-block">
                                    <span class="h4">{{$course_sched->course->coursename}}</span>
                                    <span class="badge bg-success ms-2">
                                        {{$course_sched->course->coursecode}}</span></span>
                                <p class="mb-0 fs-6">
                                    Course Schedule ID: #{{$course_sched->scheduleid}} - 0001
                                </p>
                            </div>
                        </div>
                        <!-- Pricing data -->
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-6 mb-2 mb-lg-0">
                                <span class="fs-6">Started On</span>
                                <h6 class="mb-0">{{$course_sched->startdateformat}}</h6>
                            </div>
                            <div class="col-lg-3 col-md-3 col-6 mb-2 mb-lg-0">
                                <span class="fs-6">End On</span>
                                <h6 class="mb-0">{{$course_sched->enddateformat}}</h6>
                            </div>
                            <div class="col-lg-3 col-md-3 col-6 mb-2 mb-lg-0">
                                <span class="fs-6">Total enrolled:</span>
                                @if ($course_sched->numberofenroled < $course_sched->course->maximumtrainees)
                                    <h6 class="mb-0">{{$course_sched->numberofenroled}} / {{$course_sched->course->maximumtrainees}}</h6>
                                    @else
                                    <h6 class="mb-0" style="color: red;">{{$course_sched->numberofenroled}} / {{$course_sched->course->maximumtrainees}}</h6>

                                    @endif
                            </div>
                            <div class="col-lg-3 col-md-3 col-6 mb-2 mb-lg-0">
                                <span class="fs-6">PACKAGE PRICE:</span>
                                <h6 class="mb-0">₱ {{ number_format($this->t_fee, 0, '.', ',') }}</h6>
                            </div>
                        </div>
                    </div>


                    @if ($showAdditionalForm)
                    <div class="border-bottom pt-0 pb-5">
                        <div class="pt-5">
                            <div class="row mb-4">
                                <div class="col-lg-6 col-md-8 col-7 mb-2 mb-lg-0">
                                    <span class="d-block">
                                        <span class="h4">DORMITORY BILL</span>
                                        <span class="badge bg-success ms-2">
                                            @if ($dorm_name)
                                            {{$dorm_name->dorm}}
                                            @else
                                            Select a room first
                                            @endif
                                        </span></span>
                                    <p class="mb-0 fs-6">
                                        Check in: <i>{{$start_date}}</i> - Check out: <i>{{$end_date}}</i>
                                    </p>
                                </div>
                            </div>
                            <!-- Pricing data -->
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-6 mb-2 mb-lg-0">
                                    <span class="fs-6">Dorm price</span>
                                    <h6 class="mb-0">
                                        @if ($total_price_dorm)
                                        ₱ {{$total_price_dorm}} <i> ({{$dorm_name->atddormprice}} per day)</i>
                                        @else
                                        <i>---choose a room---</i>
                                        @endif
                                    </h6>
                                </div>
                                <div class="col-lg-3 col-md-2 col-6 mb-2 mb-lg-0">
                                    <span class="fs-6">Capacity</span>
                                    <h6 class="mb-0">
                                        @if ($dorm_name)
                                        {{$dorm_name->capacity}}
                                        @else
                                        <i>---choose a room---</i>
                                        @endif
                                    </h6>
                                </div>
                                <div class="col-lg-3 col-md-4 col-6 mb-2 mb-lg-0">
                                    <span class="fs-6">Meals (Breakfast & Dinner)</span>
                                    <h6 class="mb-0">
                                        @if ($meal_price)
                                        ₱ {{$meal_price}}
                                        @else
                                        <i>---choose a room---</i>
                                        @endif
                                    </h6>
                                </div>
                                <div class="col-lg-3 col-md-3 col-6 mb-2 mb-lg-0">
                                    <span class="fs-6">TOTAL PRICE OF ROOM AND MEAL</span>
                                    <h6 class="mb-0">
                                        @if ($total)
                                        ₱{{ number_format($totalmealdorm, 0, '.', ',') }}
                                        @else
                                        <i>---choose a room---</i>
                                        @endif
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
    </div>
    <div class="d-flex justify-content-end">
        @if ($course_sched->numberofenroled < $course_sched->course->maximumtrainees)
            <button type="submit" class="btn btn-danger mt-5" wire:click.prevent="create">
                Submit For Review
            </button>
            @else
            <button type="submit" class="btn btn-danger mt-5" disabled>
                Submit For Review
            </button>
            @endif
    </div>
</div>