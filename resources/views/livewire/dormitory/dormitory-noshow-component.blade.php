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
                        No Show
                    </h1>
                    <!-- Breadcrumb  -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="#">Dormitory</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                No Show
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
                                <select name="" wire:model.defer="companysearch" class="form-select" id="">
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
                                    @foreach ($loadpaymentmethod as $data)
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
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-6">
                            <h4 class="card-title">List
                                @if (session('datefrom') != null)
                                    ( {{date("F d, Y", strtotime(session('datefrom')))}} - {{date("F d, Y", strtotime(session('dateto')))}})</h4>
                                @endif
                        </div>
                        <div class="col-lg-6">
                            <button class="float-end btn btn-sm btn-danger" wire:click="resetdate">Reset Date</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="dataTableBasic_wrapper">
                          <thead class="table-dark" >
                            <tr style="font-size: 10px;">
                              <th scope="col">Name</th>
                              <th scope="col">Rank</th>
                              <th scope="col">Company</th>
                              <th scope="col">Course Code</th>
                              <th scope="col">Course</th>
                              <th scope="col">Contact Number</th>
                              <th scope="col">Payment Method</th>
                              <th scope="col">Start Date</th>
                              <th scope="col">End Date</th>
                              <th scope="col" style="min-width: 130px;">Action</th>
                            </tr>
                          </thead>
                          <tbody style="font-size: 10px;">
                            @if (!empty($reservations))
                                @foreach ($reservations as $reservation)
                                    <tr>
                                        <td>{{ $reservation->f_name }} {{ $reservation->l_name }}</td>
                                        <td>{{ $reservation->rank }}</td>
                                        <td>{{ $reservation->company }}</td>
                                        <td>{{ $reservation->coursecode }}</td>
                                        <td>{{ $reservation->coursename }}</td>
                                        <td>{{ $reservation->contact_num }}</td>
                                        <td>{{ $reservation->paymentmode }}</td>
                                        <td>{{ date('F d, Y', strtotime($reservation->startdateformat)) }}</td>
                                        <td>{{ date('F d, Y', strtotime($reservation->enddateformat)) }}</td>

                                        <td style="font-size: 10px; min-width: 130px;">
                                            <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Edit
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="#" wire:click.prevent="checkin({{$reservation->enroledid}})"><i class="bi bi-bookmark-plus-fill"></i>&nbsp;Assign Room</a>
                                                <a class="dropdown-item" href="#" wire:click.prevent="cancel({{$reservation->enroledid}})"><i class="bi bi-x-octagon-fill"></i>&nbsp;Cancel</a>
                                                <a class="dropdown-item" href="#" wire:click.prevent="returnforreserve({{$reservation->enroledid}})"><i class="bi bi-arrow-left-square-fill"></i>&nbsp;Return for Reserve</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="text-center"><td colspan="19">No Date To Show</td></tr>
                            @endif
                        </tbody>

                        </table>
                      </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="editreservation" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Assign Room (Enroled ID: {{ $this->enroledid }} - {{ $this->traineeid }})</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="checkinform" action="" wire:submit.prevent="checkinform">
                    @csrf
                    <div class="row mt-1">
                            <div class="col-lg-6">
                                <label for="">Date From</label>
                                <input type="text" wire:model.defer="datefrom" class="form-control flatpickr">
                            </div>
                            <div class="col-lg-6">
                                <label for="">Date To</label>
                                <input type="text" wire:model.defer="dateto" class="form-control flatpickr">
                            </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-lg-12">
                            <label for="">Remarks</label>
                            <textarea name="" class="form-control" wire:model.defer="remarks" id="" cols="12" row mt-1s="2"></textarea>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-lg-6">
                            <label for="">Course </label>
                            <input type="text" wire:model.defer="coursename" class="form-control" readonly>
                        </div>
                        <div class="col-lg-6">
                            <label for="">Company</label>
                            <input type="text" wire:model.defer="companyname" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-lg-4">
                            <label for="">Room Type</label>
                            <select wire:model="selectedroomtype" wire:target="selectedroomtype" class="form-select" name="" id="">
                                <option value="">Select</option>
                                @if($roomtype)
                                    @foreach ($roomtype as $roomtypes)
                                        <option value="{{ $roomtypes->id }}">{{ $roomtypes->roomtype }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label for="">Room</label>
                            <select wire:model="selectedrooms" wire:target="selectedrooms" class="form-select" name="" id="">
                                <option value="">Select Option</option>
                                @if($roomdata)
                                    @foreach ($roomdata as $roomdatas)
                                        <option @if ($roomdatas->capacity == 0) class="bg-danger text-white" disabled @endif value="{{ $roomdatas->id }}">{{ $roomdatas->roomname }} @if ($roomdatas->capacity == 0) (Full) @else {{ '('.$roomdatas->capacity.')' }} @endif</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label for="">Course Type</label>
                            <select wire:model="selectedcoursetype" wire:target="selectedcoursetype"  class="form-select" name="" id="">
                                <option value="">Select Option</option>
                                @if ($coursetype)
                                    @foreach ($coursetype as $coursetypes)
                                        <option value="{{$coursetypes->coursetypeid}}">{{ $coursetypes->coursetype }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="row mt-1" @if(empty($selectedcoursetype))
                        style="display: none;"
                    @endif>
                        <div class="col-lg-4">
                            <label for="">Payment Mode</label>
                            <select wire:model.defer="selectedpaymentmethod" class="form-select" name="" id="">
                                @if ($paymentmethod)
                                    @foreach ($paymentmethod as $paymentmethods)
                                        <option value="{{$paymentmethods->paymentmodeid}}">{{$paymentmethods->paymentmode}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-check-label" for="flexCheckChecked">Meal</label>
                            <input class="form-check-input" wire:model.defer="availmeal" type="checkbox" value="" id="flexCheckChecked">
                            <input wire:model.defer="meal" type="text" class="form-control">
                        </div>
                        <div class="col-lg-4">
                            <label for="">Room Rate</label>
                            <input type="text" wire:model.defer="roomrate" class="form-control">
                        </div>
                    </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="checkinform" class="btn btn-primary @if ($enablecheckin == 0)
                        disabled
                    @endif">Check in</button>
                </div>
            </div>
        </div>
    </div>
</section>
