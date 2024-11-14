<section class="container-fluid p-4">
    <span wire:loading>
        <livewire:components.loading-screen-component />
    </span>
    <div class="row">
        <!-- Page Header -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-flex justify-content-between align-items-center">
                <div class="mb-1 mb-lg-0">
                    <h1 class="mb-0 h2 fw-bold">
                        Drop Enrollments
                    </h1>
                    <span style="font-size:13px; font-weight: regula;">(View all dropped enrollments)</span>
                    <!-- Breadcrumb  -->
                    <nav class="mt-2 " aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('a.dashboard') }}">Admin</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">Billing</a></li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Billing Logs
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
                <div class="card-body">
                    <div class="col-lg-2 mb-2">
                        <form class="d-flex align-items-center">
                            <span class="position-absolute ps-3 search-icon">
                                <i class="fe fe-search"></i>
                            </span>
                            <input type="search" wire:model.debounce.700ms="search" class="form-control ps-6" placeholder="Search from course name . .">
                    </div>
                    <div class="col-lg-6">

                    </div>
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-hover second" style="width:100%">
                                <thead class="text-white" style="background-color: #000838">
                                    <tr>
                                        <th>Course Name</th>
                                        <th>Name</th>
                                        <th>Date Confirmed</th>
                                        <th>Date Dropped</th>
                                        <th>Training Start</th>
                                        <th>Remarks</th>
                                        <th>Fee</th>
                                        <th>Reason</th>
                                        <th>Dropped by</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!is_null($tblbillingdrop))
                                    @foreach ($tblbillingdrop as $data)
                                    <tr class="border">
                                        <td>{{ $data->enroled->course->coursename }}</td>
                                        <td>{{ $data->enroled->trainee->certificate_name() }}</td>
                                        <td>{{ date("d F Y", strtotime($data->dateconfirmed)) }}</td>
                                        <td>{{ date("d F Y", strtotime($data->datedrop)) }}</td>
                                        <td>{{ date("d F Y", strtotime($data->enroled->schedule->startdateformat)) }}</td>

                                        @php
                                        $remarksa = "7 days prior the training date: No fee";
                                        $remarksb = "4-6 days prior the training date: 25% of the total course fee payable";
                                        $remarksc = "3-2 days prior the training date: 50% of the total course fee payable";
                                        $remarksd = "1 day prior the training date or no show: Full course fee must be paid";
                                        $remarkse = "No cancellation fee shall be collected if the course is cancelled due to force majeure reagrdless of prior notice";

                                        $date1 = $data->datedrop;
                                        $date2 = $data->enroled->schedule->startdateformat;
                                        // Convert dates to timestamps
                                        $timestamp1 = strtotime($date1);
                                        $timestamp2 = strtotime($date2);

                                        // Calculate the difference in seconds
                                        $differenceInSeconds = $timestamp2 - $timestamp1;

                                        $differenceInDays = floor($differenceInSeconds / (60 * 60 * 24));

                                        if ($differenceInDays == 7) {
                                        $remarks = $remarksa;
                                        $percentage = 0;
                                        } elseif ($differenceInDays >= 4 && $differenceInDays <= 6) { $remarks=$remarksb; $percentage=.25; } elseif ($differenceInDays==2 || $differenceInDays==3) { $remarks=$remarksc; $percentage=.5; } elseif ($differenceInDays==1) { $remarks=$remarksd; $percentage=1; } else { $remarks=$remarkse; $percentage=0; } @endphp <td class="text-danger">@php
                                            echo $remarks;
                                            @endphp</td>
                                            <td>{{ $data->price*$percentage }}</td>
                                            <td>{{ $data->reason }}</td>
                                            <td>{{ $data->droppedby }}</td>
                                    </tr>
                                    @endforeach

                                    @else
                                    <tr>
                                        <td class="text-center" colspan="8">No Data</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    {{ $tblbillingdrop->appends(['search' => $search])->links('livewire.components.customized-pagination-link')}}
                </div>
            </div>
        </div>
    </div>
</section>