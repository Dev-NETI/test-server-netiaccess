<main>
    <span wire:loading>
        <livewire:components.loading-screen-component />
    </span>
    <section class="py-4 py-lg-6 bg-primary">

        <div class="container-fluid">
            <div class="row">
                <div class="offset-lg-1 col-lg-10 col-md-12 col-12">
                    <div class="d-lg-flex align-items-center justify-content-between">
                        <!-- Content -->
                        <div class="mb-4 mb-lg-0">
                            <h1 class="text-white mb-1">{{$course_main->coursecode}} - {{$c_title}}</h1>
                            <p class="mb-0 text-white lead">
                                Just fill the form to process your enrolment.
                            </p>
                        </div>
                        <div>
                            <a href="{{route('t.dashboard')}}" class="btn btn-white">Back to Course</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 col-md-12">
                @error('selectedSched')
                <div class="alert alert-danger mt-3 d-flex align-items-center" role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                        class="bi bi-exclamation-triangle-fill me-2" viewBox="0 0 16 16">
                        <path
                            d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                    </svg>
                    <div>
                        Select a training schedule to complete your enrollment process.
                    </div>
                </div>
                @enderror

                @error('payment_features')
                <div class="alert alert-danger mt-3 d-flex align-items-center" role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                        class="bi bi-exclamation-triangle-fill me-2" viewBox="0 0 16 16">
                        <path
                            d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                    </svg>
                    <div>
                        Select a Payment method to complete your enrollment process.
                    </div>
                </div>
                @enderror

                <div class="card mb-3 mt-3 smooth-shadow-md p-3 bg-white rounded">
                    <div class="card-header border-bottom">
                        <h2 class="mb-0">BASIC INFORMATION</h2>
                    </div>
                    <!-- Card body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <div class="mb-3">
                                    <label for="courseTitle" class="form-label">Training Title</label>
                                    <input id="courseTitle" class="form-control" type="text" placeholder="Course Title"
                                        wire:model="c_title" disabled>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-12">
                                <label class="form-label">Training Schedule</label>
                                <select class="form-control" name="" id="" wire:model.defer="selectedSched"
                                    wire:change="eventClicked($event.target.value)"
                                    @if(Auth::guard('trainee')->user()->fleet_id == 10 && $course_id == 68) disabled
                                    @endif>
                                    <option value="">Select Training schedule</option>
                                    @foreach ($course_sched as $sched)
                                    <option value="{{$sched->scheduleid}}">
                                        {{date('d F Y', strtotime($sched->startdateformat))}} - {{date('d F Y',
                                        strtotime($sched->enddateformat))}}
                                    </option>
                                    @endforeach
                                </select>
                                <small>Choosing a training schedule for your available time.</small>
                            </div>

                            @if ($selectedSched)
                            <div class="col-lg-6 col-md-12">
                                <div class="mb-3">
                                    <label for="" class="form-label">Date online:</label>

                                    @if ($dateonlinefrom == null && $dateonlineto == null || $dateonlinefrom == null ||
                                    $dateonlineto == null)
                                    <input id="" class="form-control" type="text" disabled placeholder="TBA">
                                    @else
                                    <input id="" class="form-control" type="text" disabled
                                        placeholder="{{ date('d F Y', strtotime($dateonlinefrom)) }} - {{ date('d F Y', strtotime($dateonlineto)) }}">
                                    @endif

                                </div>
                            </div>

                            <div class="col-lg-6 col-md-12">
                                <div class="mb-3">
                                    <label for="" class="form-label">Date onsite:</label>

                                    @if ($dateonsitefrom == null && $dateonsiteto == null || $dateonsitefrom == null ||
                                    $dateonsiteto == null)
                                    <input id="" class="form-control" type="text" disabled placeholder="TBA">
                                    @else
                                    <input id="" class="form-control" type="text"
                                        placeholder="{{date('d F Y', strtotime($dateonsitefrom))}} - {{date('d F Y', strtotime($dateonsiteto))}}"
                                        disabled>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="row">
                            <div class="mb-3">
                                <div class="col-lg-12 col-md-12">
                                    <h2 class="mb-3 border-bottom mt-3">PAYMENT METHOD</h2>
                                    <div class="mb-3">

                                        <label class="form-label">Payment method</label>
                                        <select class="form-control" wire:model="payment_features" @if($disabled)
                                            disabled @endif>
                                            <option value="">Select payment method</option>

                                            <!-- NMC/NMCR -->
                                            @if ($course_main->coursetypeid == 3 || $course_main->coursetypeid == 4 ||
                                            $course_main->coursetypeid == 8)
                                            <option value="1">Company sponsored</option>
                                            <!-- UPGRADING -->
                                            <!-- 17 - NTMA  -->
                                            <!-- 13 - TECHNICAL  -->
                                            @elseif ($user->fleet_id == 10 || $user->fleet_id == 13)
                                            <option value="4">NTIF Boarding Loan</option>
                                            <option value="2">Own Pay</option>
                                            @elseif ($user->fleet_id == 17 ||$user->fleet_id == 18 || $user->fleet_id ==
                                            19)
                                            <option value="2">Own Pay</option>
                                            <option value="3">Salary Deduction</option>
                                            @else
                                            <option value="2">Own Pay</option>
                                            @endif
                                        </select>
                                        <small>Check your preferred payment.</small> <br>

                                        <label class="form-label">Transportation</label>
                                        <select class="form-control" wire:model="bus_id">
                                            @if($start_date === $end_date)
                                            <option value="0">NONE</option>
                                            @else
                                            <option value="0">NONE</option>
                                            <option value="1">BUS ROUND TRIP</option>
                                            <option value="2">DAILY BUS ROUND TRIP</option>
                                            @endif
                                        </select>
                                        <small>Check your preferred transportation.</small>

                                        @if (($payment_features == 1 ||$payment_features == 2 || $payment_features == 3
                                        || $payment_features == 4))
                                        <div class="row">
                                            <div class="col-md-12 mt-3">
                                                <h3>ADD-ONS:</h3>
                                                <hr>
                                            </div>
                                            <div class="col-md-12 ">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        id="flexCheckDefault" wire:model="showAdditionalForm">
                                                    <label class="form-check-label" for="flexCheckDefault">
                                                        <h4> Tick the box if you want to avail a dormitory/room.</h4>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12">
                                    @if ($showAdditionalForm)
                                    <div class="col-md-12">
                                        <h2>ROOM RESERVATION</h2>
                                        <hr>
                                        <label for="courseTitle" class="form-label">ROOM</label>
                                        <select class="form-control" wire:model="selectedDorm">
                                            <option value="">Select a room</option>
                                            @foreach ($dorm as $d)
                                            @if(optional(Auth::guard('trainee')->user()->fleet)->fleetid == 10 &&
                                            $d->dormid == 5)
                                            <option value="{{$d->dormid}}">{{$d->dorm}}</option>
                                            @elseif(optional(Auth::guard('trainee')->user()->fleet)->fleetid != 10)
                                            @if($d->dormid != 5)
                                            <!-- Exclude dormid 5 if fleetid is not 10 -->
                                            <option value="{{$d->dormid}}">{{$d->dorm}}</option>
                                            @endif
                                            @endif
                                            @endforeach
                                        </select>
                                        <small>ADVISORY:</small>
                                        <small>Present your vaccination card before check-in. Requirement - <small
                                                style="color: red;">RAT and MARINA Safety Protocol</small> </small> <br>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3 mt-3">
                                            <label for="courseTitle" class="form-label">Check-in Date</label>
                                            <div class="input-group me-3">
                                                <input class="form-control flatpickr flatpickr-input active" type="date"
                                                    placeholder="Select Date" aria-describedby="basic-addon2"
                                                    wire:model="room_start">
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-3 mb-3">
                                            <label for="courseTitle" class="form-label">Check-out Date</label>
                                            <div class="input-group me-3">
                                                <input class="form-control flatpickr flatpickr-input active" type="date"
                                                    placeholder="Select Date" aria-describedby="basic-addon2"
                                                    wire:model="room_end">
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-12 mt-3 mb-3">
                <div class="row gy-4">
                    <!--  training schedule -->
                    <div class="card smooth-shadow-md p-3 bg-white rounded">
                        <div class="row">
                            <div class="col-12">
                                <span class="icon-shape icon-md bg-light-primary text-dark-primary rounded-3 ">
                                    <i class="bi bi-calendar2-check" style="font-size: 20px;"></i>
                                </span>
                                <span class="fs-6 text-uppercase fw-semibold">The selected training schedule</span>

                            </div>
                            <div class="col-12">
                                <!-- card body -->
                                <p class="mb-0 text-center fs-4">
                                    @if ($selectedSched)
                                    <i style="color:green;">{{date('d F Y', strtotime($start_date))}} - {{date('d F Y',
                                        strtotime($end_date))}}</i>
                                    @else
                                    <i class="text-danger">-----NO SELECTED SCHEDULE-----</i>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    <!--  selected package -->
                    <div class="card smooth-shadow-md p-3 bg-white rounded">
                        <div class="row">
                            <div class="col-12">
                                <span class="icon-shape icon-sm bg-light-primary text-dark-primary rounded-3 ">
                                    <i class="bi bi-card-checklist" style="font-size: 20px;"></i>
                                </span>
                                <span class="fs-6 text-uppercase fw-semibold">The selected package</span>

                            </div>
                            <div class="col-12">
                                <!-- card body -->
                                <p class="mb-0 text-center fs-4">
                                    @if ($start_date && $bus_id == 2 && $course_main->type->coursetypeid != 1)
                                <p class="mb-0 text-center fs-4 fst-italic" style="color:green;">
                                    <b>3 - </b> Course <span class="text-dark fw-medium">Training schedule</span> with
                                    Lunch Meal, Polo Shirt & Daily Bus Round Trip.
                                </p>
                                @elseif ($start_date && $bus_id == 1 && $course_main->type->coursetypeid != 1)
                                <p class="mb-0 text-center fs-4 fst-italic" style="color:green;">
                                    <b>2 - </b> Course <span class="text-dark fw-medium">Training schedule</span> with
                                    Lunch Meal, Polo Shirt & Bus Round Trip.
                                </p>
                                @elseif ($start_date && $bus_id == 1 && $course_main->type->coursetypeid == 1)
                                <p class="mb-0 text-center fs-4 fst-italic" style="color:green;">
                                    <b>2 - </b> Course <span class="text-dark fw-medium">Training schedule</span> with
                                    Lunch Meal, Polo Shirt & Bus Round Trip.
                                </p>
                                @elseif ($start_date)
                                <p class="mb-0 text-center fs-4 fst-italic" style="color:green;">
                                    <b>1 - </b>Course <span class="text-dark fw-medium">Training schedule</span> with
                                    Lunch Meal and Polo Shirt.
                                </p>
                                @else
                                <p class="mb-0 text-center fs-4">
                                    <i class="text-danger">-----NO SELECTED SCHEDULE-----</i>
                                </p>
                                @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- room reservation -->
                    <div class="card smooth-shadow-md p-3 bg-white rounded">
                        <div class="row">
                            <div class="col-12">
                                <span class="icon-shape icon-sm bg-light-primary text-dark-primary rounded-3 ">
                                    <i class="bi bi-building" style="font-size: 20px;"></i>
                                </span>
                                <span class="fs-6 text-uppercase fw-semibold">Room Reservation</span>

                            </div>
                            <div class="col-12">
                                <!-- card body -->
                                <p class="mb-0 text-center fs-4 fst-italic" style="color:green;">
                                    @if ($selectedDorm && $room_start && $room_end)
                                    <b>{{$dorm_name->dorm}} - </b> {{date('d F Y', strtotime($room_start))}} - {{date('d
                                    F Y', strtotime($room_end))}}
                                    @else
                                    <i class="text-danger">-----NO ROOM RESERVATION-----</i>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- breakdown price -->
                    @if($this->payment_features != 1)
                    <div class="card smooth-shadow-md p-3 bg-white rounded">
                        <div class="row g-4">
                            <div class="col-12">
                                <span class="icon-shape icon-sm bg-light-primary text-dark-primary rounded-3 ">
                                    <i class="bi bi-wallet" style="font-size: 20px;"></i>
                                </span>
                                <span class="fs-6 text-uppercase fw-semibold">Breakdown Price:</span>

                            </div>
                            <div class="col-12">
                                <!-- card body -->
                                <p class="mb-0 fs-4">
                                    @if ($selectedSched)
                                    <span class="text-dark fw-medium"> PACKAGE FEE: </span>
                                    {{number_format($this->t_fee, 0,'.',',')}}
                                    @else
                                <p class="mb-0 text-center fs-4">
                                    <i class="text-danger">-----NO SELECTED PACKAGE-----</i>
                                </p>
                                @endif
                                <br>
                                @if ($showAdditionalForm)
                                @if ($selectedDorm && $room_start && $room_end)
                                <span class="text-dark fw-medium"> DORM & MEAL FEE: </span>
                                {{number_format($dorm_price->atddormprice + $meal ,0,'.',',')}} per day * {{$duration}}
                                = {{number_format($totalmealdorm,0,'.',',')}}
                                @endif
                                @endif
                                </p>
                                <hr>
                                <h3 class="text-end">TOTAL AMOUNT DUE: {{number_format($total,0,'.',',')}}</h3>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <!-- <button type="submit" class="btn btn-lg btn-danger my-5" wire:click="create">
                SUBMIT FOR APPROVAL
            </button> -->
            <button type="submit" class="btn btn-lg btn-danger my-5" data-bs-toggle="modal"
                data-bs-target="#exampleModalScrollable">
                SUBMIT FOR APPROVAL
            </button>
        </div>
    </div>
    @include('modals.reminders-modal')
</main>