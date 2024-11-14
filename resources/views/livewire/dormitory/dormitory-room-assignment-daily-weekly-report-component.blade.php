<section class="container-fluid p-4">
    <span wire:loading>
        <livewire:components.loading-screen-component />
    </span>
    <div class="row">
        <!-- Page Header -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-flex justify-content-between align-items-center">
                <div class="mb-2 mb-lg-0">
                    <h1 class="mb-1 h2 fw-bold">
                        Daily/Weekly Reports
                    </h1>
                    <!-- Breadcrumb  -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="#">Dormitory</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="#">Reports</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Daily/Weekly Reports
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div wire:ignore class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" id="home-tab" data-bs-toggle="tab" href="#home" role="tab"
                                aria-controls="home" aria-selected="true">Daily</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab"
                                aria-controls="profile" aria-selected="false">Weekly</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show" id="home" role="tabpanel" aria-labelledby="home-tab">
                            @include('livewire.dormitory.tab-content.dailytab')
                            <div class="col-lg-12">
                                <div class="d-grid">
                                    <button wire:loading.class="placeholder-wave col-12 bg-info disabled"
                                        form="dailysearch" type="submit" class="btn btn-info mt-2">Search</button>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade show active" id="profile" role="tabpanel"
                            aria-labelledby="profile-tab">
                            @include('livewire.dormitory.tab-content.weeklytab')
                            <div class="col-lg-12 mt-2">
                                <div class="d-grid">
                                    <button wire:loading.class="placeholder-wave col-12 bg-info disabled"
                                        form="weeklysearch" type="submit" class="btn btn-info">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Weekly Table --}}
    {{-- Weekly Table --}}
    {{-- Weekly Table --}}

    @if ($weeklytable)
    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3>Data Table (Weekly)</h3>
                </div>
                <div class="card-body">
                    <div class="table-card table-responsive">
                        <table class="table table-hover" style="width:100%">
                            <thead class="table-ligth" style="font-size: 9px;">
                                <tr>
                                    <th>No</th>
                                    <th>Reservation ID</th>
                                    <th>Room Type</th>
                                    <th>Room</th>
                                    <th>Name</th>
                                    <th>Company</th>
                                    <th>Mode of Payment</th>
                                    <th>Rank</th>
                                    <th>Course</th>
                                    <th>Training Date</th>
                                    <th># of Days</th>
                                    <th>Room Rate</th>
                                    <th>Food Rate</th>
                                    <th>Check In Date</th>
                                    <th>Check Out Date</th>
                                    <th>Status</th>
                                    <th>Total Lodging Rate</th>
                                    <th>Total Food Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $x = 1;
                                $overallTotalDormUSD = 0;
                                $overallTotalDormPHP = 0;
                                $overallTotalMealUSD = 0;
                                $overallTotalMealPHP = 0;
                                @endphp
                                @foreach ($weeklydatatable as $data)
                                @if ($data->enroled->deletedid != 1)
                                <tr style="font-size: 9px;">
                                    <td>{{ $x }}</td>

                                    @if (!empty($data->room->roomtype))
                                    <td>{{ optional($data)->id }}</td>
                                    <td>{{ optional($data)->room->roomtype->roomtype }}</td>
                                    @else
                                    <td>--</td>
                                    <td>--</td>
                                    @endif

                                    @if (!empty($data->room))
                                    <td>{{ optional($data)->room->roomname }}</td>
                                    @else
                                    <td>--</td>
                                    @endif

                                    @if (!empty($data->enroled->trainee))
                                    <td>{{ $data->enroled->trainee->l_name." ".$data->enroled->trainee->f_name }}</td>
                                    <td>{{ optional($data)->enroled->trainee->company->company }}</td>
                                    @else
                                    <td>--</td>
                                    <td>--</td>
                                    @endif

                                    <td>{{ optional($data)->paymentmode->paymentmode }}</td>

                                    @if (!empty($data->enroled->trainee))
                                    <td>{{ optional($data)->enroled->trainee->rank->rankacronym.'
                                        '.optional($data)->enroled->trainee->rank->rank }}</td>
                                    @else
                                    <td>--</td>
                                    @endif

                                    @if (!empty($data->enroled->course))
                                    <td>{{ optional($data->enroled->course)->coursecode.' /
                                        '.optional($data->enroled->course)->coursename }}</td>
                                    @else
                                    <td>--</td>
                                    @endif

                                    @if (!empty($data->enroled->schedule))
                                    <td>{{ optional($data)->enroled->schedule->startdateformat." -
                                        ".optional($data)->enroled->schedule->enddateformat }}</td>
                                    @else
                                    <td>--</td>
                                    @endif

                                    @php
                                    $counteddays = $this->weeklyDaysCounter($data->checkindate, $data->checkoutdate ==
                                    NULL ?
                                    $data->enroled->schedule->enddateformat : $data->checkoutdate);

                                    @endphp

                                    <td>{{ $counteddays }}</td>
                                    <td>{{ $this->getRoomPrice($data->enroled->courseid,
                                        $data->enroled->trainee->company_id, $data->enroled->dormid) }}</td>
                                    <td>
                                        {{$this->getMealPrice($data->enroled->courseid,
                                        $data->enroled->trainee->company_id, $counteddays)}}
                                    </td>
                                    <td>{{ $data->checkindate }}</td>
                                    <td>{{ $data->checkoutdate }}</td>

                                    @if (!empty($data->enroled->reservationstatus))
                                    <td>{{ optional($data)->enroled->reservationstatus->status }}</td>
                                    @else
                                    <td>--</td>
                                    @endif

                                    <td> {{$this->totalDormPrice($data->enroled->courseid,
                                        $data->enroled->trainee->company_id, $data->enroled->dormid, $counteddays)}}
                                    </td>
                                    <td> {{$this->totalMealPrice($data->enroled->courseid,
                                        $data->enroled->trainee->company_id, $counteddays)}}
                                    </td>

                                </tr>
                                @php
                                $x++;
                                $overallTotalDormUSD += $this->getoverallDormUSD($data->enroled->courseid,
                                $data->enroled->trainee->company_id, $data->enroled->dormid, $counteddays);

                                $overallTotalDormPHP += $this->getoverallDormPHP($data->enroled->courseid,
                                $data->enroled->trainee->company_id, $data->enroled->dormid, $counteddays);

                                $overallTotalMealUSD += $this->getoverallMealUSD($data->enroled->courseid,
                                $data->enroled->trainee->company_id, $counteddays);

                                $overallTotalMealPHP += $this->getoverallMealPHP($data->enroled->courseid,
                                $data->enroled->trainee->company_id, $counteddays);
                                @endphp
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div wire:ignore.self class="card-footer col-lg-12">
                    <div class="row">
                        <div class="col-lg-3">
                            <h6>
                                Total Lodging Rate: USD {{ $overallTotalDormUSD }}<br>
                                Total Meal Rate: USD {{ $overallTotalMealUSD }} <br>
                                Overall Total: USD {{ $overallTotalDormUSD + $overallTotalMealUSD }} <br>
                            </h6>
                        </div>
                        <div class="col-lg-3">
                            <h6>
                                Total Lodging Rate: PHP {{ $overallTotalDormPHP }}<br>
                                Total Meal Rate: PHP {{ $overallTotalMealPHP }} <br>
                                Overall Total: PHP {{ $overallTotalDormPHP + $overallTotalMealPHP }} <br>
                            </h6>
                        </div>
                        <div class="col-lg-6 mt-2 text-end">
                            <button wire:click="exportExcel" class="btn btn-sm btn-success">
                                <i class="bi bi-file-earmark-spreadsheet-fill me-2 h4 text-white"></i> Export Excel
                            </button>
                            <a target="_blank" href="{{ route('a.dormitorydailyweeklyreports') }}"
                                class="btn btn-sm btn-danger">
                                <i class="bi bi-file-earmark-pdf-fill me-2 h4 text-white"></i> Export Pdf
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Daily Table --}}
    {{-- Daily Table --}}
    {{-- Daily Table --}}

    @if ($search && !empty($dailydate))
    @foreach ($dailydate as $date)
    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header text-center">
                    <h4>({{date("l, d F Y", strtotime($date))}})</h4>
                </div>
                <div class="card-body table-responsive">
                    <div class="table-card">
                        <table class="table table-hover" style="width:100%">
                            <thead class="table-ligth" style="font-size: 9px;">
                                <tr class="h6">
                                    <th>No</th>
                                    <th>Reservation ID</th>
                                    <th>Room Type</th>
                                    <th>Room</th>
                                    <th>Name</th>
                                    <th>Company</th>
                                    <th>Mode of Payment</th>
                                    <th>Rank</th>
                                    <th>Course</th>
                                    <th>Training Date</th>
                                    <th># of Days</th>
                                    <th>Room Rate</th>
                                    <th>Food Rate</th>
                                    <th>Check In Date</th>
                                    <th>Check Out Date</th>
                                    <th>Status</th>
                                    <th>Total Lodging Rate</th>
                                    <th>Total Food Rate</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 9px;">
                                {{-- @if (!empty($dailydatatable)) --}}
                                @php
                                $x = 1;
                                @endphp
                                @foreach ($this->getdailydata($date) as $data)
                                @if ($data->enroled->deleteid != 1)
                                <tr>
                                <tr style="font-size: 9px;">
                                    <td>{{ $x }}</td>
                                    @if (!empty($data->room->roomtype))
                                    <td>{{ optional($data)->id }}</td>
                                    <td>{{ optional($data)->room->roomtype->roomtype }}</td>
                                    @else
                                    <td></td>
                                    <td></td>
                                    @endif
                                    @if (!empty($data->room))
                                    <td>{{ optional($data)->room->roomname }}</td>
                                    @else
                                    <td></td>
                                    @endif
                                    @if (!empty($data->enroled->trainee))
                                    <td>{{ $data->enroled->trainee->l_name." ".$data->enroled->trainee->f_name }}</td>
                                    <td>{{ optional($data)->enroled->trainee->company->company }}</td>
                                    @else
                                    <td></td>
                                    <td></td>
                                    @endif

                                    <td>{{ optional($data)->paymentmode->paymentmode }}</td>
                                    @if (!empty($data->enroled->trainee))
                                    <td>{{ optional($data)->enroled->trainee->rank->rankacronym.'
                                        '.optional($data)->enroled->trainee->rank->rank }}</td>
                                    @else
                                    <td></td>
                                    @endif
                                    @if (!empty($data->enroled->course))
                                    <td>{{ optional($data->enroled->course)->coursecode.' /
                                        '.optional($data->enroled->course)->coursename }}</td>
                                    @else
                                    <td></td>
                                    @endif
                                    @if (!empty($data->enroled->schedule))
                                    <td>{{ optional($data)->enroled->schedule->startdateformat." -
                                        ".optional($data)->enroled->schedule->enddateformat }}</td>
                                    @else
                                    <td></td>
                                    @endif
                                    @php
                                    $counteddays = 0;
                                    $datefromdaily = new DateTime(optional($data)->checkindate);
                                    $datetodaily = new DateTime($data->checkoutdate != NULL ? $data->checkoutdate :
                                    $data->enroled->schedule->enddateformat);
                                    $datetodaily->modify('+1 day');
                                    $counteddays = $datefromdaily->diff($datetodaily);
                                    @endphp

                                    <td>{{ $counteddays->days }}</td>
                                    <td> {{$this->getRoomPrice($data->enroled->courseid,
                                        $data->enroled->trainee->company_id, $data->enroled->dormid) }}</td>
                                    <td> {{$this->getMealPrice($data->enroled->courseid,
                                        $data->enroled->trainee->company_id) }}</td>
                                    <td>{{ $data->checkindate }}</td>
                                    <td>{{ $data->checkoutdate }}</td>
                                    @if (!empty($data->enroled->reservationstatus))
                                    <td>{{ optional($data)->enroled->reservationstatus->status }}</td>
                                    @else
                                    <td></td>
                                    @endif
                                    <td> {{$this->getRoomPrice($data->enroled->courseid,
                                        $data->enroled->trainee->company_id, $data->enroled->dormid) }}</td>
                                    <td> {{$this->getMealPrice($data->enroled->courseid,
                                        $data->enroled->trainee->company_id) }}</td>
                                </tr>
                                @endif
                                @php
                                $x++;
                                @endphp
                                @endforeach
                                {{-- @endif --}}
                            </tbody>
                        </table>
                    </div>
                </div>
                @foreach ($this->getdailydata($date) as $data)
                @php
                $PHPMeal += $this->getoverallMealPHP($data->enroled->courseid, $data->enroled->trainee->company_id, 1);
                $PHPDorm += $this->getoverallDormPHP($data->enroled->courseid,
                $data->enroled->trainee->company_id,$data->enroled->dormid ,1);
                $USDMeal += $this->getoverallMealUSD($data->enroled->courseid, $data->enroled->trainee->company_id, 1);
                $USDDorm += $this->getoverallDormUSD($data->enroled->courseid,
                $data->enroled->trainee->company_id,$data->enroled->dormid ,1);
                @endphp
                @endforeach
                <div class="card-footer" style="font-size: 9px;">
                    @php
                    $overallTotalMealPHP += $PHPMeal;
                    $overallTotalDormPHP += $PHPDorm;
                    $overallTotalMealUSD += $USDMeal;
                    $overallTotalDormUSD += $USDDorm;
                    @endphp
                    <div class="row">
                        <div class="col-lg-3">
                            <h6>Total Lodging Rate: USD {{ $overallTotalDormUSD }} <br>
                                Total Meal Rate: USD {{ $overallTotalMealUSD }} <br>
                                Total: USD {{ $overallTotalDormMealUSD = $overallTotalMealUSD+$overallTotalDormUSD }}
                            </h6>
                        </div>
                        <div class="col-lg-3">
                            <h6>Total Lodging Rate: PHP {{ $overallTotalDormPHP }} <br>
                                Total Meal Rate: PHP {{ $overallTotalMealPHP }} <br>
                                Total: PHP {{ $overallTotalDormMealPHP = $overallTotalDormPHP+$overallTotalMealPHP }}
                            </h6>
                        </div>
                    </div>
                </div>
                @php
                $totalUSDMeal += $overallTotalMealUSD;
                $totalUSDDorm += $overallTotalDormUSD;
                $totalPHPMeal += $overallTotalMealPHP;
                $totalPHPDorm += $overallTotalDormPHP;

                $PHPMeal = 0;
                $PHPDorm = 0;
                $USDMeal = 0;
                $USDDorm = 0;
                $overallTotalDormUSD = 0;
                $overallTotalDormPHP = 0;
                $overallTotalMealPHP = 0;
                $overallTotalMealUSD = 0;
                @endphp
            </div>
        </div>
    </div>
    @endforeach
    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row text-black fw-bold">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-3">
                                    Overall Total Lodging Rate: USD {{ $totalUSDDorm }} <br>
                                    Overall Total Meal Rate: USD {{ $totalUSDMeal }} <br>
                                    Overall Total: USD {{ $totalUSDMeal + $totalUSDDorm }} <br>
                                </div>
                                <div class="col-lg-3">
                                    Overall Total Lodging Rate: PHP {{ $totalPHPDorm }} <br>
                                    Overall Total Meal Rate: PHP {{ $totalPHPMeal }} <br>
                                    Overall Total: PHP {{ $totalPHPMeal + $totalPHPDorm}} <br>
                                </div>
                                <div class="col-lg-6 text-end">
                                    <a target="_blank" href="{{ route('a.dormitorydailyweeklyreports') }}"
                                        class="btn btn-sm btn-danger">
                                        Export Pdf
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

</section>