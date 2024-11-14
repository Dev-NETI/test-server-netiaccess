<section class="container-fluid p-4">
    <div class="row border-bottom">
        <div class="col-lg-6">
            <h3>Dormitory Dashboard</h3>
            <p class="text-primary">{{ date('F', strtotime(now())).' Week '. ceil(date('j', strtotime(now())) / 7).
                date(' - Y', strtotime(now())) }}</p>
        </div>
        <div class="col-lg-6">
            <div class="float-end">
                <label for="">Select Room Type </label>
                <select class="form-select" name="" id="" wire:model="selectedBuilding">
                    @foreach ($loadbuildings as $building)
                    <option value="{{ $building->id }}">{{ $building->roomtype }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-xl-3 col-lg-6 col-md-12 col-12">
            <!-- Card -->
            <div class="card mb-4">
                <!-- Card body -->
                <div class="card-body">
                    <span class="fs-6 text-uppercase fw-semibold ls-md">Pending for Reserve this Month</span>
                    <div class="mt-2 d-flex justify-content-between align-items-center">
                        <div class="lh-1">
                            <h2 class="h1 fw-bold mb-1">{{ number_format($countForReserve, 0, '.', ',') }}</h2>
                            {{-- <span>100Last 30Days</span> --}}
                        </div>
                        <div>
                            <span class="bg-light-primary icon-shape icon-xl rounded-3 text-dark-primary h1">
                                <i class="bi bi-clock-fill"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-12 col-12">
            <!-- Card -->
            <div class="card mb-4">
                <!-- Card Body -->
                <div class="card-body">
                    <span class="fs-6 text-uppercase fw-semibold ls-md">Reserved</span>
                    <div class="mt-2 d-flex justify-content-between align-items-center">
                        <div class="lh-1">
                            <h2 class="h1 fw-bold mb-1">{{ $countReserve }}</h2>
                            {{-- <span>300+ Media Object</span> --}}
                        </div>
                        <div>
                            <span class="bg-light-info icon-shape icon-xl rounded-3 text-dark-info h1">
                                <i class="bi bi-door-open-fill"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-12 col-12">
            <!-- Card -->
            <a href="#" wire:click="showCheckInList">
                <div class="card mb-4">
                    <!-- Card Body -->
                    <div class="card-body">
                        <span class="fs-6 text-uppercase fw-semibold ls-md">Check In</span>
                        <div class="mt-2 d-flex justify-content-between align-items-center">
                            <div class="lh-1">
                                <h2 class="h1 fw-bold mb-1">{{ $countCheckIn }}</h2>
                                {{-- <span>1.5k in 30Days</span> --}}
                            </div>
                            <div>
                                <span class="bg-light-success icon-shape icon-xl rounded-3 text-dark-success h1">
                                    <i class="bi bi-clipboard-check-fill"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-12 col-12">
            <!-- Card -->
            <div class="card mb-4">
                <!-- Card Body -->
                <div class="card-body">
                    <span class="fs-6 text-uppercase fw-semibold ls-md">Check Out</span>
                    <div class="mt-2 d-flex justify-content-between align-items-center">
                        <div class="lh-1">
                            <h2 class="h1 fw-bold mb-1">{{ $countCheckOut }}</h2>
                            {{-- <span>20+ Comments</span> --}}
                        </div>
                        <div>
                            <span class="bg-light-danger icon-shape icon-xl rounded-3 text-dark-danger h1">
                                <i class="bi bi-clipboard-minus-fill"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="checkinmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Check In List</h5>
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-sm text-nowrap mb-0 table-centered">
                            <thead>
                                <tr>
                                    <th>Full Name</th>
                                    <th>Check In Date</th>
                                    <th>Room</th>
                                    <th>Training Course</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($loadCheckIn as $checkindata)
                                @if ($checkindata->enroled && $checkindata->room)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center h3">
                                            <div>
                                                <div>
                                                    <i class="bi bi-person-circle"></i>
                                                </div>
                                            </div>
                                            <div class="ms-3 lh-1">
                                                <h5 class="mb-1"><a href="#" class="text-inherit">{{
                                                        $checkindata->enroled->trainee->l_name. ',
                                                        '.$checkindata->enroled->trainee->f_name }}</a></h5>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $checkindata->checkindate }}</td>
                                    <td>{{ $checkindata->room->roomname }}</td>
                                    <td>{{ $checkindata->enroled->course->coursecode.'/
                                        '.$checkindata->enroled->course->coursename }}</td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                            {{-- <tfoot>
                                {{ $loadCheckIn->links('livewire.components.customized-pagination-link') }}
                            </tfoot> --}}
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    @foreach ($buildings as $buildingname)
    <div class="row mt-1">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header text-center h4 text-black">
                    {{ $buildingname->roomtype }} - {{ date('F', strtotime(now())) }} <span class="h4"> - from ({{
                        date('d F, Y' , strtotime($startOfWeek)) }}) to ({{ date('d F, Y', strtotime($endOfWeek))
                        }})</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-dark text-nowrap mb-0 table-centered">
                            <thead>
                                <tr>
                                    <th>Room Name</th>
                                    @php
                                    $startOfWeekTH = new DateTime($startOfWeek);
                                    $endOfWeekTH = new DateTime($endOfWeek);
                                    $dateStart = $startOfWeekTH;
                                    $dateEnd = $endOfWeekTH;
                                    $dateEnd->modify('+1 day');
                                    $dateInterval = new DateInterval('P1D');
                                    $dateRange = new DatePeriod($dateStart, $dateInterval, $dateEnd);
                                    @endphp
                                    @while ($startOfWeekTH < $endOfWeekTH) <th> {{ $startOfWeekTH->format('d - l') }}
                                        </th>
                                        @php
                                        $startOfWeekTH->modify('+1 day');
                                        @endphp
                                        @endwhile
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($buildlinks = $buildingname->roomname()->get() as $building)
                                @if ($building->deleteid == 0)
                                <tr>
                                    <td rowspan="2">{{ '('. $building->id .')' . $building->roomname }}</td>
                                    @foreach ($dateRange as $date)
                                    @php
                                    $mdate = $date->format('Y-m-d');
                                    $this->checkIfReserved($mdate, $building->id);

                                    if ($this->check == 1){
                                    echo '<td class="bg-info"><small>Reserved</small></td>';
                                    }
                                    else{
                                    echo '<td class="bg-success"><small>Available</small></td>';
                                    }
                                    @endphp

                                    @endforeach
                                </tr>
                                <tr>
                                    @foreach ($dateRange as $date)
                                    @php
                                    $this->checkIfCheckIn($date->format('Y-m-d'), $building->id);

                                    if ($this->check == 1){
                                    echo '<td class="bg-warning text-black"><small>Check In</small></td>';
                                    }
                                    else{
                                    echo '<td class="bg-success"><small>Available</small></td>';
                                    }

                                    @endphp
                                    @endforeach
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header text-center h3">
                    {{ $buildingname->roomtype }}
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped border-black text-nowrap mb-0 table-centered">
                            <thead>
                                <tr>
                                    <th>Room Name</th>
                                    <th>Reserved</th>
                                    <th>Check In</th>
                                    <th>Trainee's Name</th>
                                    <th>Training Date</th>
                                    <th>Used Space</th>
                                    <th>Max Capacity</th>
                                    <th>Beds Available</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($buildingname->roomname()->paginate(10) as $data)
                                @if ($data->deleteid == 0)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <div>
                                                    <img src="../../assets/images/brand/bed2.png" alt="">
                                                </div>
                                            </div>
                                            <div class="ms-3 lh-1">
                                                <h5 class="mb-1"><a href="#" class="text-inherit">{{ $data->roomname
                                                        }}</a></h5>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $this->countReserve($data->id) }}</td>
                                    <td>{{ $this->countCheckIn($data->id) }}</td>
                                    <td>
                                        @foreach ($this->collectNames($data->id) as $datan)
                                        <span class="{{ $datan->is_reserved == 1 ? 'text-info' : 'text-warning' }}">{{
                                            strtoupper($datan->firstname.' '.$datan->lastname) }}</span> <br>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($this->collectTrainingDate($data->id) as $datatd)
                                        <span class="{{ $datatd->is_reserved == 1 ? 'text-info' : 'text-warning' }}">{{
                                            strtoupper($datatd->datefrom.' '.$datatd->dateto) }}</span> <br>
                                        @endforeach
                                    </td>
                                    <td>{{ $data->capacity }}</td>
                                    <td>{{ $data->max_capacity }}</td>
                                    <td>
                                        {{ $data->max_capacity - $data->capacity }}
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{
                    $buildingname->roomname()->paginate(10)->links('livewire.components.customized-pagination-link')}}
                </div>
            </div>
        </div>
    </div>
    @endforeach
</section>