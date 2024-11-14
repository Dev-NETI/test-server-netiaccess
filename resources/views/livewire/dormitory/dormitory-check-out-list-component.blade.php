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
                        List of Checked Out
                    </h1>
                    <!-- Breadcrumb  -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="#">Dormitory</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Checked Out List
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
                    <h3>Data Table</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                         <!-- table footer -->
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Enroled ID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Rank</th>
                                    <th scope="col">Company</th>
                                    <th scope="col">Course Code</th>
                                    <th scope="col">Course</th>
                                    <th scope="col">Contact Number</th>
                                    <th scope="col">Payment Method</th>
                                    <th scope="col">Start Date</th>
                                    <th scope="col">End Date</th>
                                    <th scope="col">Check In</th>
                                    <th scope="col">Check Out</th>
                                    <th scope="col">Room Type</th>
                                    <th scope="col">Room</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reservations as $data)
                                    <tr style="font-size: 10px;">
                                        <th scope="row">{{ $data->enroledid }}</th>
                                        <td>{{ $data->l_name }}, {{ $data->f_name }} {{ $data->m_name }}</td>
                                        <td>{{ $data->rank }}</td>
                                        <td>{{ $data->company }}</td>
                                        <td>{{ $data->coursecode }}</td>
                                        <td>{{ $data->coursename }}</td>
                                        <td>{{ $data->contact_num }}</td>
                                        <td>{{ $data->paymentmode }}</td>
                                        <td>{{ date("F d, Y", strtotime($data->startdateformat)) }}</td>
                                        <td>{{ date("F d, Y", strtotime($data->enddateformat)) }}</td>
                                        <td>{{ date("F d, Y", strtotime($data->checkindate)) }}</td>
                                        <td>{{ date("F d, Y", strtotime($data->checkoutdate)) }}</td>
                                        <td>{{ $data->roomtype }}</td>
                                        <td>{{ $data->roomname }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</section>
