<main>
    <section class="pb-5 py-md-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-12 col-12">
                    <div class="card mb-3 mt-3 border-0">
                        <div class="card-header border-bottom px-4 py-3">
                            <h4 class="mb-0 text-uppercase"><i>Enrollment Request Received</i></h4>
                        </div>
                        <!-- Card body -->
                        <div class="card-body ">
                            <div class="row">
                                <!-- NTIF BOARDING LOAN & SALARY DEDUCTION -->
                                @if ($enrol->paymentmodeid == 4 || $enrol->paymentmodeid == 3 )
                                <div class="col-md-12">
                                    <h5>Upload the following documents for evaluation at least a day before the scheduled training. Don't forget to sign your Authority to Deduct when you report to NETI for your practical assessment.</h5>
                                    <div class="mb-3">
                                        <small>List of Requirements: <small class="text-danger">(For mandatory courses only)</small> <br> 1. Medical certification (PEME FORMAT) from any Clinic / Hospital recognized by Marina and accredited by DOH <a href="https://stcw.marina.gov.ph/wp-content/uploads/2016/02/MFOWS-as-of-March-2023-2-1.pdf" target="_blank"><i>(Click here to check)</i></a> <br> 2. Certificate of Proficiency <br> 3. 2x2 ID Picture (Soft Copy) <br> 4. Proof of Payment </small>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td colspan="10" class="text-center">
                                                    <b>SUMMARY</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3"><b>Couse Training:</b> <b><i><u>{{$enrol->course->coursename}}</u></i></b></td>
                                                <td colspan="2">Date Training: <i>{{$enrol->schedule->startdateformat}} - {{$enrol->schedule->enddateformat}}</i> </td>
                                                <td colspan="5">Reference #: <i>{{$enrol->registrationcode}}</i> </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><b>Package: </b> </td>
                                                <td colspan="3"><i> The Selected Package is #{{$enrol->t_fee_package}}
                                                        @if ($enrol->t_fee_package == 1)
                                                        (Training fee only)
                                                        @elseif ($enrol->t_fee_package == 2)
                                                        (Training schedule with Lunch Meal and Polo Shirt.)
                                                        @elseif ($enrol->t_fee_package == 3)
                                                        (Training schedule with Lunch Meal, Polo Shirt & Bus Round Trip.)
                                                        @elseif ($enrol->t_fee_package == 4)
                                                        (Training schedule with Lunch Meal, Polo Shirt and Daily Bus Round Trip.)
                                                        @endif
                                                    </i>
                                                </td>
                                                <td colspan="2">Package Price: </td>
                                                <td colspan="3">₱ {{number_format($enrol->t_fee_price,2,'.',',')}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="10" class="center"> <b>DORMITORY & MEAL</b> </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3"><b>Room type:</b></td>
                                                @if ($enrol->dorm)
                                                <td colspan="2">{{$enrol->dorm->dorm}}</td>
                                                @else
                                                <td colspan="2"><i> N/A </i></td>
                                                @endif
                                                <td colspan="3"> <b>Dorm & Meal fee:</b></td>
                                                @if ($enrol->dorm_price)
                                                <td colspan="2">₱{{number_format($enrol->dorm_price + $enrol->meal_price, 2, '.', ',')}}</td>
                                                @else
                                                <td colspan="2"><i> N/A </i></td>
                                                @endif
                                            </tr>
                                            <tr>
                                                <td colspan="3"><b>Check in:</b></td>
                                                @if ($enrol->checkindate)
                                                <td colspan="2">{{$enrol->checkindate}}</td>
                                                @else
                                                <td colspan="2"><i> N/A </i></td>
                                                @endif
                                                <td colspan="3"><b>Check out:</b></td>
                                                @if ($enrol->checkindate)
                                                <td colspan="2">{{$enrol->checkoutdate}}</td>
                                                @else
                                                <td colspan="2"><i> N/A </i></td>
                                                @endif
                                            </tr>
                                            <tr>
                                                <td colspan="5"> <b>Transporation:</b> </td>
                                                <td colspan="5"> <i>
                                                        @if ($enrol->busmodeid == 1)
                                                        Round trip
                                                        @elseif ($enrol->busmodeid == 2)
                                                        Daily Round Trip
                                                        @else
                                                        None
                                                        @endif </i> </td>
                                            </tr>

                                            <tr>
                                                <td colspan="10"><b> TOTAL AMOUNT (Need to pay): </b> <i>₱ {{ number_format($enrol->total, 2, '.', ',') }}</i></td>
                                            </tr>
                                        </table>
                                    </div>

                                    {{-- <div class="float-end">
                                        @if ($enrol->paymentmodeid == 4 )
                                        <a href="{{ route('view-atd', ['registration' => $enrol->enroledid])}}" class="btn btn-secondary btn-sm" target="_blank">
                                    VIEW YOUR ATD/SLAF
                                    <span class="mdi mdi-download"></span>
                                    </a>
                                    @else
                                    <a href="{{ route('view-sd', ['registration' => $enrol->enroledid])}}" class="btn btn-secondary btn-sm" target="_blank">
                                        VIEW YOUR ATD/SLAF
                                        <span class="mdi mdi-download"></span>
                                    </a>
                                    @endif
                                </div> --}}
                                <!-- <div class="px-4">
                                        <div class="card mb-4 card-hover " style="background-color: #f5f4f8;">
                                            <div class="row">
                                                <a class="col-12 col-md-12 col-xl-3 col-lg-3 bg-cover rounded-start" style="background-image: url({{asset('assets/images/oesximg/logo.png')}});" href="#">
                                                </a>
                                                <div class="col-lg-9 col-md-12 col-12">
                                                    <div class="card-body">
                                                        <h3 class="mb-2 text-truncate-line-2 ">
                                                            {{$enrol->course->coursecode}} - {{$enrol->course->coursename}}
                                                        </h3>
                                                        <ul class="mb-5  list-inline">
                                                            <li class="list-inline-item"><i class="mdi mdi-clock-time-four-outline text-muted me-1"></i> {{$enrol->schedule->startdateformat}} - {{$enrol->schedule->enddateformat}}</li>
                                                            <li class="list-inline-item"><i class="mdi mdi-cash-clock text-muted me-1"></i> {{$enrol->payment->paymentmode}}</li>
                                                        </ul>
                                                        <div class="row align-items-center g-0">
                                                            <div class="col-auto">
                                                                <span>Reference Number: {{$enrol->registrationcode}}</span>

                                                            </div>
                                                            <div class="col ms-2">
                                                            </div>
                                                            <div class="col-auto">

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
                            </div>
                            <!-- OWNPAY -->
                            @elseif($enrol->paymentmodeid == 2)
                            <div class="col-md-12">
                                <h5>Upload the following documents for evaluation at least a day before the scheduled training.</h5>
                                <div class="mb-3">
                                    <small>List of Requirements: <small class="text-danger">(For mandatory courses only)</small> <br> 1. Medical certification (PEME FORMAT) from any Clinic / Hospital recognized by Marina and accredited by DOH <a href="https://stcw.marina.gov.ph/wp-content/uploads/2016/02/MFOWS-as-of-March-2023-2-1.pdf" target="_blank"><i>(Click here to check)</i></a> <br> 2. Certificate of Proficiency <br> 3. 2x2 ID Picture (Soft Copy) <br> 4. Proof of Payment </small>
                                    <h5><i>Kindly send it to <u>registrar@neti.com.ph</u>, and please also CC <u>collection@neti.com.ph</u></i></h5>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td colspan="10" class="text-center">
                                                <b>SUMMARY</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"><b>Couse Training:</b> <b><i><u>{{$enrol->course->coursename}}</u></i></b></td>
                                            <td colspan="2">Date Training: <i>{{$enrol->schedule->startdateformat}} - {{$enrol->schedule->enddateformat}}</i> </td>
                                            <td colspan="5">Reference #: <i>{{$enrol->registrationcode}}</i> </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><b>Package: </b> </td>
                                            <td colspan="3"><i> The Selected Package is #{{$enrol->t_fee_package}}
                                                    @if ($enrol->t_fee_package == 1)
                                                    (Training fee only)
                                                    @elseif ($enrol->t_fee_package == 2)
                                                    (Training schedule with Lunch Meal and Polo Shirt.)
                                                    @elseif ($enrol->t_fee_package == 3)
                                                    (Training schedule with Lunch Meal, Polo Shirt & Bus Round Trip.)
                                                    @elseif ($enrol->t_fee_package == 4)
                                                    (Training schedule with Lunch Meal, Polo Shirt and Daily Bus Round Trip.)
                                                    @endif
                                                </i>
                                            </td>
                                            <td colspan="2">Package Price: </td>
                                            <td colspan="3">₱ {{number_format($enrol->t_fee_price,2,'.',',')}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="10" class="center"> <b>DORMITORY & MEAL</b> </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"><b>Room type:</b></td>
                                            @if ($enrol->dorm)
                                            <td colspan="2">{{$enrol->dorm->dorm}}</td>
                                            @else
                                            <td colspan="2"><i> N/A </i></td>
                                            @endif
                                            <td colspan="3"> <b>Dorm & Meal fee:</b></td>
                                            @if ($enrol->dorm_price)
                                            <td colspan="2">₱{{number_format($enrol->dorm_price + $enrol->meal_price, 2, '.', ',')}}</td>
                                            @else
                                            <td colspan="2"><i> N/A </i></td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td colspan="3"><b>Check in:</b></td>
                                            @if ($enrol->checkindate)
                                            <td colspan="2">{{$enrol->checkindate}}</td>
                                            @else
                                            <td colspan="2"><i> N/A </i></td>
                                            @endif
                                            <td colspan="3"><b>Check out:</b></td>
                                            @if ($enrol->checkindate)
                                            <td colspan="2">{{$enrol->checkoutdate}}</td>
                                            @else
                                            <td colspan="2"><i> N/A </i></td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td colspan="5"> <b>Transporation:</b> </td>
                                            <td colspan="5"> <i>
                                                    @if ($enrol->busmodeid == 1)
                                                    Round trip
                                                    @elseif ($enrol->busmodeid == 2)
                                                    Daily Round Trip
                                                    @else
                                                    None
                                                    @endif </i> </td>
                                        </tr>

                                        <tr>
                                            <td colspan="10"><b> TOTAL AMOUNT (Need to pay): </b> <i>₱ {{ number_format($enrol->total, 2, '.', ',') }}</i></td>
                                        </tr>
                                    </table>
                                </div>

                            </div>
                            @elseif($enrol->paymentmodeid == 1)
                            <div class="col-md-12">
                                <h5>Please wait for the registrar's approval for your enrollment. You can check the status of your enrollment on the 'My Courses' tab.</h5>
                                <div class="mb-3">
                                    <small>List of Requirements: <small class="text-danger">(For mandatory courses only)</small> <br> 1. Medical certification (PEME FORMAT) from any Clinic / Hospital recognized by Marina and accredited by DOH <a href="https://stcw.marina.gov.ph/wp-content/uploads/2016/02/MFOWS-as-of-March-2023-2-1.pdf" target="_blank"><i>(Click here to check)</i></a> <br> 2. Certificate of Proficiency <br> 3. 2x2 ID Picture (Soft Copy) <br> 4. Proof of Payment </small>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td colspan="10" class="text-center">
                                                <b>SUMMARY</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"><b>Couse Training:</b> <b><i><u>{{$enrol->course->coursename}}</u></i></b></td>
                                            <td colspan="2">Date Training: <i>{{$enrol->schedule->startdateformat}} - {{$enrol->schedule->enddateformat}}</i> </td>
                                            <td colspan="5">Reference #: <i>{{$enrol->registrationcode}}</i> </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><b>Package: </b> </td>
                                            <td colspan="3"><i> The Selected Package is #{{$enrol->t_fee_package}}
                                                    @if ($enrol->t_fee_package == 1)
                                                    (Training fee only)
                                                    @elseif ($enrol->t_fee_package == 2)
                                                    (Training schedule with Lunch Meal and Polo Shirt.)
                                                    @elseif ($enrol->t_fee_package == 3)
                                                    (Training schedule with Lunch Meal, Polo Shirt & Bus Round Trip.)
                                                    @elseif ($enrol->t_fee_package == 4)
                                                    (Training schedule with Lunch Meal, Polo Shirt and Daily Bus Round Trip.)
                                                    @endif
                                                </i>
                                            </td>
                                            <td colspan="2">Package Price: </td>
                                            <td colspan="3">₱ {{number_format($enrol->t_fee_price,2,'.',',')}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="10" class="center"> <b>DORMITORY & MEAL</b> </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"><b>Room type:</b></td>
                                            @if ($enrol->dorm)
                                            <td colspan="2">{{$enrol->dorm->dorm}}</td>
                                            @else
                                            <td colspan="2"><i> N/A </i></td>
                                            @endif
                                            <td colspan="3"> <b>Dorm & Meal fee:</b></td>
                                            @if ($enrol->dorm_price)
                                            <td colspan="2">₱{{number_format($enrol->dorm_price + $enrol->meal_price, 2, '.', ',')}}</td>
                                            @else
                                            <td colspan="2"><i> N/A </i></td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td colspan="3"><b>Check in:</b></td>
                                            @if ($enrol->checkindate)
                                            <td colspan="2">{{$enrol->checkindate}}</td>
                                            @else
                                            <td colspan="2"><i> N/A </i></td>
                                            @endif
                                            <td colspan="3"><b>Check out:</b></td>
                                            @if ($enrol->checkindate)
                                            <td colspan="2">{{$enrol->checkoutdate}}</td>
                                            @else
                                            <td colspan="2"><i> N/A </i></td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td colspan="5"> <b>Transporation:</b> </td>
                                            <td colspan="5"> <i>
                                                    @if ($enrol->busmodeid == 1)
                                                    Round trip
                                                    @elseif ($enrol->busmodeid == 2)
                                                    Daily Round Trip
                                                    @else
                                                    None
                                                    @endif </i> </td>
                                        </tr>
                                    </table>
                                </div>
                                <!-- <div class="px-4">
                                        <div class="card mb-4 card-hover " style="background-color: #f5f4f8;">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-12">
                                                    <div class="card-body">
                                                        <h3 class="mb-2 text-truncate-line-2 ">
                                                            {{$enrol->course->coursecode}} - {{$enrol->course->coursename}}
                                                            <hr>
                                                        </h3>
                                                        <ul class="mb-4  list-inline">
                                                            <li class="list-inline-item"><i class="mdi mdi-clock-time-four-outline text-muted me-1"></i> {{$enrol->schedule->startdateformat}} - {{$enrol->schedule->enddateformat}}</li>
                                                            <li class="list-inline-item"><i class="mdi mdi-cash-clock text-muted me-1"></i> {{$enrol->payment->paymentmode}}</li>
                                                        </ul>
                                                        <div class="row align-items-center g-0">
                                                            <div class="col-auto mt-3">
                                                                <span>Reference Number: {{$enrol->registrationcode}}</span>
                                                            </div>
                                                            <div class="col ms-2">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="col-auto">
                            <a href="{{ route('t.coursedetails', ['regis' => $enrol->enroledid]) }}" class="btn btn-success btn-sm float-end">
                                GO TO COURSE TAB
                                <span class="mdi mdi-arrow-right-bold"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
</main>