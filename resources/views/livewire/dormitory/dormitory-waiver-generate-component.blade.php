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
                        Generate Waiver & Ammenities Reports
                    </h1>
                    <!-- Breadcrumb  -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="#">Dormitory</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="#">Report</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Waiver / Ammenities
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
                <div class="card-header">
                    <h4 class="card-title">Select Date Range</h4>
                </div>
                <div class="card-body">
                    <form id="searchcheckin" wire:submit.prevent="searchcheckin" action="">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <label class="form-label">Date From</label>
                                <input class="form-control flatpickr" wire:model.defer="datefrom" type="text" placeholder="Select Date">
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label" for="">Date To</label>
                                <input class="form-control flatpickr" wire:model.defer="dateto" type="text" placeholder="Select Date">
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col-lg-4">
                                <label class="form-label">Company</label>
                                <select name="" wire:model.defer="company" class="form-select" id="">
                                    <option value="">All</option>
                                    @foreach ($loadcompany as $data)
                                    <option value="{{ $data->companyid }}">{{ $data->company }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label" for="">Course</label>
                                <select name="" wire:model.defer="courses" class="form-select" id="">
                                    <option value="">All</option>
                                    @foreach ($loadcourses as $data)
                                    <option value="{{ $data->courseid }}">{{ $data->coursecode }} / {{ $data->coursename }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label" for="">Payment Method</label>
                                <select name="" wire:model.defer="searchpaymentmethod" class="form-select" id="">
                                    <option value="">All</option>
                                    @foreach ($loadpaymentmode as $data)
                                    <option value="{{ $data->paymentmodeid }}">{{ $data->paymentmode }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    <div class="col-lg-12 d-grid">
                        <button type="submit" form="searchcheckin" class="btn d-block btn-primary">Search</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header col-lg-12">
                    <div class="row">
                        <div class="col-lg-6">
                            <h4 class="card-title">List
                                @if (session('datefrom') != null)
                                    ( {{date("F d, Y", strtotime(session('datefrom')))}} - {{date("F d, Y", strtotime(session('dateto')))}})</h4>
                                @endif
                        </div>
                        @if (session('datefrom'))
                            <div class="col-lg-6">
                                <button class="float-end btn btn-sm btn-danger" wire:click="resetdate">Reset Date</button>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-check">
                                    |<input class="form-check-input" wire:model="togglebatch" type="checkbox" value="" id="togglebatch">
                                <label class="form-check-label" for="togglebatch">
                                  Toggle by batch checkbox
                                </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="dataTableBasic_wrapper">
                          <thead class="table-dark" >
                            <tr style="font-size: 10px;">
                               @if ($togglebatch)
                                <th scope="col">
                                    <input class="form-check-input" type="checkbox" wire:model="checkall" value="" id="checkall">
                                </th>
                              @endif
                              <th scope="col">No</th>
                              <th scope="col">Name</th>
                              <th scope="col">Rank</th>
                              <th scope="col">Company</th>
                              <th scope="col">Course Code</th>
                              <th scope="col">Course</th>
                              <th scope="col">Contact Number</th>
                              <th scope="col">Payment Method</th>
                              <th scope="col">Start Date</th>
                              <th scope="col">End Date</th>
                              <th scope="col">Room Name</th>
                              @if ($togglebatch)   
                                @else
                                    <th scope="col">Action&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                @endif
                            </tr>
                          </thead>
                          <tbody style="font-size: 10px;">
                            @if (!empty($reservations))
                                @php
                                    $x = 1;
                                @endphp
                                @foreach ($reservations as $reservation)
                                    <tr>
                                        @if ($togglebatch)
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" wire:model.defer="checkboxtd.{{ $reservation->traineeid }}" type="checkbox" id="batchcheck">
                                            </div>
                                        </td>
                                        @endif
                                        <td><label for="batchcheck">{{ $x }}</label></td>
                                        <td><label for="batchcheck">{{ $reservation->f_name }} {{ $reservation->l_name }}</label></td>
                                        <td><label for="batchcheck">{{ $reservation->rank }}</label></td>
                                        <td><label for="batchcheck">{{ $reservation->company }}</label></td>
                                        <td><label for="batchcheck">{{ $reservation->coursecode }}</label></td>
                                        <td><label for="batchcheck">{{ $reservation->coursename }}</label></td>
                                        <td><label for="batchcheck">{{ $reservation->contact_num }}</label></td>
                                        <td><label for="batchcheck">{{ $reservation->paymentmode }}</label></td>
                                        <td><label for="batchcheck">{{ date('F d, Y', strtotime($reservation->startdateformat)) }}</label></td>
                                        <td><label for="batchcheck">{{ date('F d, Y', strtotime($reservation->enddateformat)) }}</label></td>
                                        <td><label for="batchcheck">{{ $reservation->roomname }}</label></td>
                                        @if ($togglebatch)
                                            
                                        @else
                                            <td><button class="btn btn-sm btn-danger" wire:click="printwaiver({{$reservation->traineeid}})"><small>Generate Waiver</small></button></td>
                                        @endif
                                    </tr>
                                    @php
                                        $x++;
                                    @endphp
                                @endforeach
                            @else
                                <tr class="text-center"><td colspan="19">No Date To Show - Pick Date Range</td></tr>
                            @endif
                            </tbody>
                        </table>

                        @if ($togglebatch)
                                @if (!empty($reservations))
                                    <div class="card-footer">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <button wire:click="generatewaiverbatch" class="btn btn-sm btn-warning">Generate Waiver</button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                      </div>
                </div>
            </div>
        </div>
    </div>

</section>
