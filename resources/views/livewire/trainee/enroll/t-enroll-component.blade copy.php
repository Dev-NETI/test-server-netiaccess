<div>
    <span wire:loading>
        <livewire:components.loading-screen-component />
    </span>
    <section class="py-4 py-lg-6 bg-primary">

        <div class="container">
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
    <div class="container">
        <div id="courseForm" class="bs-stepper">
            <div class="row">
                <div class="offset-lg-1 col-lg-10 col-md-12 col-12">
                    <!-- Stepper Button -->
                    <div class="bs-stepper-header shadow-sm" role="tablist" wire:ignore>
                        <div class="step" data-target="#test-l-1">
                            <button type="button" class="step-trigger" role="tab" id="courseFormtrigger1" aria-controls="test-l-1">
                                <span class="bs-stepper-circle">1</span>
                                <span class="bs-stepper-label">Choose training schedule</span>
                            </button>
                        </div>
                        @if ($user->company_id == 1 && ($this->course_main->coursetypeid == 3 || $this->course_main->coursetypeid == 4))

                        @else
                        <div class="bs-stepper-line"></div>
                        <div class="step" data-target="#test-l-2">
                            <button type="button" class="step-trigger" role="tab" id="courseFormtrigger2" aria-controls="test-l-2">
                                <span class="bs-stepper-circle">2</span>
                                <span class="bs-stepper-label">Choose Package</span>
                            </button>
                        </div>
                        @endif
                        <div class="bs-stepper-line"></div>
                        <div class="step" data-target="#test-l-3">
                            <button type="button" class="step-trigger" role="tab" id="courseFormtrigger3" aria-controls="test-l-3">
                                <span class="bs-stepper-circle">3</span>
                                <span class="bs-stepper-label">Payment method</span>
                            </button>
                        </div>
                    </div>
                    <!-- Stepper content -->
                    <div class="bs-stepper-content mt-5">
                        <form onSubmit="return false">
                            <!-- Content one -->
                            <div id="test-l-1" role="tabpanel" class="bs-stepper-pane fade" aria-labelledby="courseFormtrigger1" wire:ignore>
                                <!-- Card -->
                                <div class="card mb-3 ">
                                    <div class="card-header border-bottom px-4 py-3">
                                        <h4 class="mb-0">Basic Information</h4>
                                    </div>
                                    <!-- Card body -->
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="courseTitle" class="form-label">Training Title</label>
                                            <input id="courseTitle" class="form-control" type="text" placeholder="Course Title" wire:model="c_title" disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Training Schedule</label>
                                            <select class="form-control" name="" id="" wire:model.defer="selectedSched" wire:change="eventClicked($event.target.value)">
                                                <option value="">Select Training schedule</option>
                                                @foreach ($course_sched as $sched)
                                                <option value="{{$sched->scheduleid}}">
                                                    {{date('d F Y', strtotime($sched->startdateformat))}} - {{date('d F Y', strtotime($sched->enddateformat))}}
                                                </option>
                                                @endforeach
                                            </select>
                                            <small>Choosing a training schedule for your available time.</small>


                                            <!-- <div class="row">
                                                <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                                                    <div class="card">
                                                        <div id="calendar"></div>
                                                    </div>
                                                </div>
                                            </div> -->

                                        </div>
                                    </div>
                                </div>
                                <!-- Button -->
                                <button class="btn btn-primary" onclick="courseForm.next()">
                                    Next
                                </button>
                            </div>

                            
                            @if ($user->company_id == 1 && ($this->course_main->coursetypeid == 3 || $this->course_main->coursetypeid == 4))

                            @else
                            <!-- Content two -->
                            <div id="test-l-2" role="tabpanel" class="bs-stepper-pane fade" aria-labelledby="courseFormtrigger2" wire:ignore.self>
                                <!-- Card -->
                                <div class="card mb-3 border-0">
                                    <div class="card-header border-bottom px-4 py-3">
                                        <h4 class="mb-0">Choose Package</h4>
                                    </div>
                                    <!-- Card body -->
                                    <div class="card-body">
                                        <div class="mb-3">


                                            <section class="py-lg-13 py-8" style="background-image: url({{asset('assets/images/oesximg/card.jpg')}}); background-size: cover; background-position: center; background-repeat: no-repeat;">
                                                <div class="container">
                                                    <!-- Page header -->
                                                    <div class="row align-items-center">
                                                        <div class="offset-xl-2 col-xl-8 col-lg-12 col-md-12 col-12">
                                                            <div class="text-center mb-6 px-md-8">
                                                                <h1 class="text-white display-3 fw-bold">
                                                                    Choose what are you prefered package?
                                                                </h1>
                                                                <p class="text-white lead mb-4">
                                                                    NTIF Boarding Loan are for all cadets that will ...
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </section>
                                            <section class="mt-n8 pb-8">
                                                <div class="container">
                                                    @if (Auth::guard('trainee')->user()->nationalityid == 32)
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-12 col-12">
                                                            <!-- Card -->
                                                            <div class="card border-0 mb-3 card-hover">
                                                                <!-- Card body -->
                                                                <div class="p-5 text-center">
                                                                    <img src="{{asset('assets/images/oesximg/iconflag.png')}}" width="50" alt="icon" class="mb-5">
                                                                    <div class="mb-5">
                                                                        <h2 class="fw-bold">TRAINING FEE</h2>
                                                                        <p class="mb-0">
                                                                            Course <span class="text-dark fw-medium">Training fee</span> only.
                                                                        </p>
                                                                    </div>
                                                                    <div class="d-flex justify-content-center mb-4">
                                                                        <span class="h3 mb-0 fw-bold">₱</span>
                                                                        <div class=" toggle-price-content odometer h1">
                                                                            {{$course_main->trainingfee}}
                                                                        </div>
                                                                        <span class="align-self-end mb-1 ms-2 toggle-price-content">/pessos</span>
                                                                    </div>
                                                                    <div class="d-grid">
                                                                        <button class="btn btn-success" wire:click="disableButton({{ 1 }})" @if ($selectedButtonIndex===1) disabled @endif>Select</button>
                                                                    </div>
                                                                </div>
                                                                <hr class="m-0">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-12 col-12">
                                                            <!-- Card -->
                                                            <div class="card border-0 mb-3 mb-lg-0 card-hover">
                                                                <!-- Card body -->
                                                                <div class="p-5 text-center">
                                                                    <img src="{{asset('assets/images/oesximg/iconflag.png')}}" width="50" alt="icon" class="mb-5">
                                                                    <div class="mb-5">
                                                                        <h2 class="fw-bold">PACKAGE 1</h2>
                                                                        <p class="mb-0">
                                                                            Course <span class="text-dark fw-medium">Training schedule</span> with Lunch Meal and Polo Shirt.
                                                                        </p>
                                                                    </div>
                                                                    <div class="d-flex justify-content-center mb-4">
                                                                        <span class="h3 mb-0 fw-bold">₱</span>
                                                                        <div class=" toggle-price-content odometer h1">
                                                                            {{$course_main->atdpackage1}}
                                                                        </div>
                                                                        <span class="align-self-end mb-1 ms-2 toggle-price-content">/pessos</span>
                                                                    </div>
                                                                    <div class="d-grid">
                                                                        <button class="btn btn-success" wire:click="disableButton({{ 2 }})" @if ($selectedButtonIndex===2) disabled @endif>Select</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-12 col-12">
                                                            <!-- Card -->
                                                            <div class="card border-0 mb-3 mb-lg-0 card-hover">
                                                                <!-- Card body -->
                                                                <div class="p-5 text-center">
                                                                    <img src="{{asset('assets/images/oesximg/iconflag.png')}}" width="50" alt="icon" class="mb-5">
                                                                    <div class="mb-5">
                                                                        <h2 class="fw-bold">PACKAGE 2</h2>
                                                                        <p class="mb-0">
                                                                            Course <span class="text-dark fw-medium">Training schedule</span> with Lunch Meal, Polo Shirt & Bus Round Trip.
                                                                        </p>
                                                                    </div>
                                                                    <div class="d-flex justify-content-center mb-4">
                                                                        <span class="h3 mb-0 fw-bold">₱</span>
                                                                        <div class=" toggle-price-content odometer h1">
                                                                            {{$course_main->atdpackage2}}
                                                                        </div>
                                                                        <span class="align-self-end mb-1 ms-2 toggle-price-content">/pessos</span>
                                                                    </div>
                                                                    <div class="d-grid">
                                                                        <button href="#" class="btn btn-success" wire:click="disableButton({{ 3 }})" @if ($selectedButtonIndex===3) disabled @endif>Select</button>
                                                                    </div>
                                                                </div>
                                                                <hr class="m-0">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-12 col-12">
                                                            <!-- Card -->
                                                            <div class="card border-0 mb-3 mb-lg-0 card-hover">
                                                                <!-- Card body -->
                                                                <div class="p-5 text-center">
                                                                    <img src="{{asset('assets/images/oesximg/iconflag.png')}}" width="50" alt="icon" class="mb-5">
                                                                    <div class="mb-5">
                                                                        <h2 class="fw-bold">PACKAGE 3</h2>
                                                                        <p class="mb-0">
                                                                            Course <span class="text-dark fw-medium">Training schedule</span> with Lunch Meal and Daily Bus Round Trip.
                                                                        </p>
                                                                    </div>
                                                                    <div class="d-flex justify-content-center mb-4">
                                                                        <span class="h3 mb-0 fw-bold">₱</span>
                                                                        <div class=" toggle-price-content odometer h1">
                                                                            {{$course_main->atdpackage3}}
                                                                        </div>
                                                                        <span class="align-self-end mb-1 ms-2 toggle-price-content">/pessos</span>
                                                                    </div>
                                                                    <div class="d-grid">
                                                                        <button href="#" class="btn btn-success" wire:click="disableButton({{ 4 }})" @if ($selectedButtonIndex===4) disabled @endif>Select</button>
                                                                    </div>
                                                                </div>
                                                                <hr class="m-0">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @else
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-12 col-12">
                                                            <!-- Card -->
                                                            <div class="card border-0 mb-3 card-hover">
                                                                <!-- Card body -->
                                                                <div class="p-5 text-center">
                                                                    <img src="{{asset('assets/images/oesximg/iconflag.png')}}" width="50" alt="icon" class="mb-5">
                                                                    <div class="mb-5">
                                                                        <h2 class="fw-bold">TRAINING FEE</h2>
                                                                        <p class="mb-0">
                                                                            Course <span class="text-dark fw-medium">Training fee</span> only.
                                                                        </p>
                                                                    </div>
                                                                    <div class="d-flex justify-content-center mb-4">
                                                                        <span class="h3 mb-0 fw-bold">$</span>
                                                                        <div class=" toggle-price-content odometer h1">
                                                                            {{$course_main->dollarTrainingFee}}
                                                                        </div>
                                                                        <span class="align-self-end mb-1 ms-2 toggle-price-content">/usd</span>
                                                                    </div>
                                                                    <div class="d-grid">
                                                                        <button class="btn btn-success" wire:click="disableButton({{ 5 }})" @if ($selectedButtonIndex===5) disabled @endif>Select</button>
                                                                    </div>
                                                                </div>
                                                                <hr class="m-0">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-12 col-12">
                                                            <!-- Card -->
                                                            <div class="card border-0 mb-3 mb-lg-0 card-hover">
                                                                <!-- Card body -->
                                                                <div class="p-5 text-center">
                                                                    <img src="{{asset('assets/images/oesximg/iconflag.png')}}" width="50" alt="icon" class="mb-5">
                                                                    <div class="mb-5">
                                                                        <h2 class="fw-bold">PACKAGE 1</h2>
                                                                        <p class="mb-0">
                                                                            Course <span class="text-dark fw-medium">Training schedule</span> with Lunch Meal and Polo Shirt.
                                                                        </p>
                                                                    </div>
                                                                    <div class="d-flex justify-content-center mb-4">
                                                                        <span class="h3 mb-0 fw-bold">$</span>
                                                                        <div class=" toggle-price-content odometer h1">
                                                                            {{$course_main->dollarAtdpackage1}}
                                                                        </div>
                                                                        <span class="align-self-end mb-1 ms-2 toggle-price-content">/usd</span>
                                                                    </div>
                                                                    <div class="d-grid">
                                                                        <button class="btn btn-success" wire:click="disableButton({{ 6 }})" @if ($selectedButtonIndex===6) disabled @endif>Select</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-12 col-12">
                                                            <!-- Card -->
                                                            <div class="card border-0 mb-3 mb-lg-0 card-hover">
                                                                <!-- Card body -->
                                                                <div class="p-5 text-center">
                                                                    <img src="{{asset('assets/images/oesximg/iconflag.png')}}" width="50" alt="icon" class="mb-5">
                                                                    <div class="mb-5">
                                                                        <h2 class="fw-bold">PACKAGE 2</h2>
                                                                        <p class="mb-0">
                                                                            Course <span class="text-dark fw-medium">Training schedule</span> with Lunch Meal, Polo Shirt & Bus Round Trip.
                                                                        </p>
                                                                    </div>
                                                                    <div class="d-flex justify-content-center mb-4">
                                                                        <span class="h3 mb-0 fw-bold">$</span>
                                                                        <div class=" toggle-price-content odometer h1">
                                                                            {{$course_main->dollarAtdpackage2}}
                                                                        </div>
                                                                        <span class="align-self-end mb-1 ms-2 toggle-price-content">/usd</span>
                                                                    </div>
                                                                    <div class="d-grid">
                                                                        <button href="#" class="btn btn-success" wire:click="disableButton({{ 7 }})" @if ($selectedButtonIndex===7) disabled @endif>Select</button>
                                                                    </div>
                                                                </div>
                                                                <hr class="m-0">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-12 col-12">
                                                            <!-- Card -->
                                                            <div class="card border-0 mb-3 mb-lg-0 card-hover">
                                                                <!-- Card body -->
                                                                <div class="p-5 text-center">
                                                                    <img src="{{asset('assets/images/oesximg/iconflag.png')}}" width="50" alt="icon" class="mb-5">
                                                                    <div class="mb-5">
                                                                        <h2 class="fw-bold">PACKAGE 3</h2>
                                                                        <p class="mb-0">
                                                                            Course <span class="text-dark fw-medium">Training schedule</span> with Daily Meal (breakfast,lunch,dinner) and Bus Round Trip.
                                                                        </p>
                                                                    </div>
                                                                    <div class="d-flex justify-content-center mb-4">
                                                                        <span class="h3 mb-0 fw-bold">$</span>
                                                                        <div class=" toggle-price-content odometer h1">
                                                                            {{$course_main->dollarAtdpackage3}}
                                                                        </div>
                                                                        <span class="align-self-end mb-1 ms-2 toggle-price-content">/usd</span>
                                                                    </div>
                                                                    <div class="d-grid">
                                                                        <button href="#" class="btn btn-success" wire:click="disableButton({{ 8 }})" @if ($selectedButtonIndex===8) disabled @endif>Select</button>
                                                                    </div>
                                                                </div>
                                                                <hr class="m-0">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </section>
                                        </div>
                                    </div>
                                </div>

                                <!-- Button -->
                                <div class="d-flex justify-content-between">
                                    <button class="btn btn-secondary" onclick="courseForm.previous()">
                                        Previous
                                    </button>

                                    <button class="btn btn-primary" onclick="courseForm.next()">
                                        Next
                                    </button>
                                </div>
                            </div>
                            @endif

                            <!-- Content three -->
                            <div id="test-l-3" role="tabpanel" class="bs-stepper-pane fade" aria-labelledby="courseFormtrigger3" wire:ignore.self>
                                <!-- Card -->
                                <div class="card mb-3 border-0">
                                    <div class="card-header border-bottom px-4 py-3">
                                        <h4 class="mb-0">Payment method</h4>
                                    </div>
                                    <!-- Card body -->
                                    <div class="card-body">
                                        <div class="mb-3">
                                            @if ($alert_notif)
                                            <div class="alert alert-danger d-flex align-items-center" role="alert">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill me-2" viewBox="0 0 16 16">
                                                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                                </svg>
                                                <div>
                                                    {{$alert_notif}}
                                                </div>
                                            </div>
                                            @endif
                                            <label class="form-label">Payment method</label>
                                            <select class="form-control" wire:model="payment_features" @if($disabled) disabled @endif>
                                                <option value="">Select payment method</option>

                                                <!-- NMC/NMCR -->
                                                @if ($course_main->coursetypeid == 3 || $course_main->coursetypeid == 4)
                                                <option value="1">Company sponsored</option>
                                                <!-- <option value="2">Own Pay</option> -->
                                                <!-- UPGRADING -->
                                                <!-- 17 - NTMA  -->
                                                <!-- 13 - TECHNICAL  -->
                                                @elseif ($user->fleet_id == 13 || $user->fleet_id == 17)
                                                <option value="4">NTIF Boarding Loan</option>
                                                <option value="2">Own Pay</option>
                                                @else
                                                <option value="1">Company Sponsored</option>
                                                <option value="2">Own Pay</option>
                                                <option value="3">Salary Deduction</option>
                                                @endif
                                            </select>
                                            <small>Check your preferred payment.</small>
                                        </div>
                                        @if ($payment_features == 1)
                                        <livewire:trainee.payment.t-company-sponsored-component :selectedSched="$selectedSched" :payment_features="$payment_features" :course_main="$course_main" :selectedPackage="$selectedPackage" :t_fee="$t_fee" :bus_id="$bus_id" />

                                        @elseif ($payment_features == 2)
                                        <livewire:trainee.payment.t-own-pay-component :selectedSched="$selectedSched" :payment_features="$payment_features" :course_main="$course_main" :selectedPackage="$selectedPackage" :t_fee="$t_fee" :bus_id="$bus_id" />

                                        @elseif ($payment_features == 3)
                                        <livewire:trainee.payment.t-salary-deduction-component :selectedSched="$selectedSched" :payment_features="$payment_features" :course_main="$course_main" :selectedPackage="$selectedPackage" :t_fee="$t_fee" :bus_id="$bus_id" />


                                        @elseif ($payment_features == 4)
                                        <livewire:trainee.payment.t-boarding-loan-component :selectedSched="$selectedSched" :payment_features="$payment_features" :course_main="$course_main" :selectedPackage="$selectedPackage" :t_fee="$t_fee" :bus_id="$bus_id" />

                                        @endif
                                    </div>

                                </div>
                                <!-- Button -->
                                <div class="d-flex justify-content-between">
                                    <button class="btn btn-secondary" onclick="courseForm.previous()">
                                        Previous
                                    </button>

                                    <!-- <button class="btn btn-primary" onclick="courseForm.next()">
                                        Next
                                    </button> -->
                                </div>
                                <!-- Content four -->
                                <div id="test-l-4" role="tabpanel" class="bs-stepper-pane fade" aria-labelledby="courseFormtrigger4">
                                    <!-- Card -->
                                    <div class="card mb-3  border-0">
                                        <div class="card-header border-bottom px-4 py-3">
                                            <h4 class="mb-0">Requirements</h4>
                                        </div>
                                        <!-- Card body -->
                                        <div class="card-body">

                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mb-22">
                                        <!-- Button -->
                                        <button class="btn btn-secondary mt-5" onclick="courseForm.previous()">
                                            Previous
                                        </button>
                                        <button type="submit" class="btn btn-danger mt-5">
                                            Submit
                                        </button>
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        if ($('#calendar').length) {
            document.addEventListener('livewire:load', function() {
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth'
                    },
                    height: 900,
                    contentHeight: 800,
                    aspectRatio: 3,
                    nowIndicator: true,
                    initialView: 'dayGridMonth',
                    editable: true,
                    dayMaxEvents: true,
                    navLinks: true,
                    events: [
                        @foreach($course_sched as $schedule) {
                            title: '{{ $schedule->batchno }}',
                            start: '{{ $schedule->startdateformat }}',
                            end: '{{ date('Y-m-d', strtotime($schedule->enddateformat.' + 1 day '))}}',
                            extendedProps: {
                                schedid: '{{ $schedule->scheduleid }}',
                            },
                        },
                        @endforeach
                    ],
                    eventClick: function(info) {
                        var schedid = info.event.extendedProps.schedid;
                        Livewire.emit('eventClicked', schedid);
                    },
                });

                calendar.render();
            });
        };
    </script>
    @endpush
</div>