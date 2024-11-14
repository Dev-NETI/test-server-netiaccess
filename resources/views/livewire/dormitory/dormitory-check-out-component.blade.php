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
                        Check In
                    </h1>
                    <!-- Breadcrumb  -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="#">Dormitory</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Check In </h4>

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
                    <div class="row">
                        <div class="col-lg-6">
                            <h4 class="card-title">Select Date Range</h4>
                        </div>
                        @can('authorizeAdminComponents', 91)
                        <div class="col-lg-6">
                            <a href="{{route('d.checkout-scanner')}}"
                                class="btn btn-outline-primary float-end">Check-Out Scanner</a>
                        </div>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <form id="searchcheckin" wire:submit.prevent="searchcheckin" action="">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <label class="form-label">Date From</label>
                                <input class="form-control flatpickr" wire:model.defer="datefrom" type="date"
                                    placeholder="Select Date">
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label" for="">Date To</label>
                                <input class="form-control flatpickr" wire:model.defer="dateto" type="date"
                                    placeholder="Select Date">
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
                                    <option value="{{ $data->courseid }}"><span class="h6">{{ $data->coursecode
                                            }}</span> / {{ $data->coursename }}</option>
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
                <div class="card-header col-lg-12">
                    <div class="row">
                        <div class="col-lg-6">
                            <h4 class="card-title">List @if (session('datefrom') != null)
                                ( {{date("F d, Y", strtotime(session('datefrom')))}} - {{date("F d, Y",
                                strtotime(session('dateto')))}}) @endif</h4>
                        </div>
                        <div class="col-lg-6">
                            <button class="float-end btn btn-sm btn-danger" wire:click="resetdate">Reset</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover " id="dataTableBasic_wrapper">
                            <thead class="table-dark">
                                <tr style="font-size: 10px;">
                                    <th scope="col" style="min-width: 120px;">Action</th>
                                    <th scope="col">EnroledID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Rank</th>
                                    <th scope="col">Company</th>
                                    <th scope="col">Course Code</th>
                                    <th scope="col">Course</th>
                                    <th scope="col">Contact Number</th>
                                    <th scope="col">Payment Method</th>
                                    <th scope="col">Start Date</th>
                                    <th scope="col">End Date</th>
                                    <th scope="col">Check In Date</th>
                                    <th scope="col">Room</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 10px;">
                                @if (!empty($reservations))
                                @foreach ($reservations as $reservation)
                                <tr>
                                    <td style="font-size: 10px; min-width: 200px;">
                                        <button class="btn btn-sm btn-danger"
                                            wire:click.prevent="showcheckoutmodal({{$reservation->enroledid}})"><i
                                                class="bi bi-box-arrow-right"></i>&nbsp;</button>
                                        <button class="btn btn-sm btn-info"
                                            wire:click.prevent="showeditcheckin({{$reservation->id}})"><i
                                                class="bi bi-pencil-square"></i>&nbsp;</button>
                                        <button class="btn btn-sm btn-success"
                                            wire:click.prevent="showeditroom({{$reservation->id}})"><i
                                                class="bi bi-calendar3-fill"></i>&nbsp;</button>
                                    </td>
                                    <td>{{ $reservation->enroledid }} - {{ $reservation->reservationstatusid }}</td>
                                    <td>{{ $reservation->f_name }} {{ $reservation->l_name }}</td>
                                    <td>{{ $reservation->rank }}</td>
                                    <td>{{ $reservation->company }}</td>
                                    <td>{{ $reservation->coursecode }}</td>
                                    <td>{{ $reservation->coursename }}</td>
                                    <td>{{ $reservation->contact_num }}</td>
                                    <td>{{ $reservation->paymentmode }}</td>
                                    <td>{{ date('F d, Y', strtotime($reservation->startdateformat)) }}</td>
                                    <td>{{ date('F d, Y', strtotime($reservation->enddateformat)) }}</td>
                                    <td>{{ date('F d, Y', strtotime($reservation->checkindate)) }} {{ date('h:m a',
                                        strtotime($reservation->checkintime)) }}</td>
                                    <td>{{ $reservation->roomname }}</td>
                                </tr>
                                @endforeach
                                @else
                                <tr class="text-center">
                                    <td colspan="19">No Date To Show</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="checkoutmodal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <label for="">
                        <h5>Check Out Date:</h5>
                    </label>
                    <input type="text" class="form-control flatpickr" wire:model.defer="checkoutdate"
                        placeholder="Check Out Date">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-danger" wire:click="checkout"><i
                            class="bi bi-box-arrow-right"></i>&nbsp;Check Out</button>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="editcheckin" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <label for="">
                        <h5>Pick Check In Date:</h5>
                    </label>
                    <input type="text" class="form-control flatpickr" wire:model.defer="checkindate"
                        placeholder="Check In Date">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-info" wire:click="savecheckindate">Change</button>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="editroom" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <label class="mt-2" for="">
                        <h5>Room Type:</h5>
                    </label>
                    <select name="" id="" wire:model="selectedroomtype" class="form-select" wire:model="selectedroom">
                        <option value="">Select Room Type</option>
                        @foreach ($loadroomtype as $roomtype)
                        <option value="{{$roomtype->id}}">{{ $roomtype->roomtype }}</option>
                        @endforeach
                    </select>
                    <label class="mt-2" for="">
                        <h5>Change Room:</h5>
                    </label>
                    <select name="" id="" class="form-select" wire:model="selectedroomname">
                        <option value="">Select Room</option>
                        @foreach ($loadroomname as $roomname)
                        <option value="{{$roomname->id}}">{{ $roomname->roomname }} (Bed(s) Available : {{
                            $roomname->capacity }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-info" wire:click="savechangeroom">Change</button>
                </div>
            </div>
        </div>
    </div>
</section>