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
                        Generate Weekly list of Enrolees
                        <span class="fs-5 text-muted"></span>
                    </h1>
                    <!-- Breadcrumb  -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{route('a.dashboard')}}">Administrator</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{route('a.report-dashboard')}}">Reports</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Generate Weekly list of Enrolees
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 col-md-4 col-12">
            <div class="mb-3">
                <label class="form-label" for="">Batch Week<span class="text-danger">*</span></label>
                <select class="form-select " wire:model="selected_batch" placeholder="Click to Batch Week">
                    <option value="">Select Batch</option>
                    @foreach ($batchWeeks as $week)
                    <option value="{{$week->batchno}}">{{$week->batchno}}</option>
                    @endforeach
                </select>
                @error('selected_batch')
                <small class="text-danger">{{$message}}</small>
                @enderror
            </div>
        </div>

        <div class="col-lg-4 col-md-4 col-12">
            <label class="form-label" for="selectOne"><span class="text-secondary"></span></label>
            @if ($selected_batch)
            <div class="text-end mt-4 mb-3">
                <a class="btn btn-primary d-inline ms-3" href="{{ route('a.export-list-enrolees-excel', ['selected_batch' => $selected_batch]) }}" target="_blank">EXPORT TO EXCEL</a>
            </div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="card">
                <!-- card header  -->
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-5">
                            <h4 class="mb-1">List of Training Schedule</h4>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm text-nowrap mb-0 table-centered" width="100%">
                        <thead>
                            <tr>
                                <!-- <th>#</th> -->
                                <th>#</th>
                                <th>BATCH NO.</th>
                                <th>FULL NAME</th>
                                <th>POSITION</th>
                                <th>COMPANY</th>
                                <th>BUS</th>
                                <th>ROOM TYPE</th>
                                <th>COURSE</th>
                                <th>START</th>
                                <th>END</th>
                                <th>FLEET</th>
                                <th>BIRTHDATE</th>
                                <th>MOBILE NUMBER</th>
                                <th>Email Address</th>
                                <th>ADDRESS</th>
                                <th>DATE ENROLLED</th>
                                <th>T-SHIRT</th>
                                <th>CHECK IN</th>
                                <th>CHECK OUT</th>
                                <th>MODE OF PAYMENT</th>
                                <th>ATTENDING</th>
                                <th>PEME</th>
                                <th>COP</th>
                                <th>ENROLLED BY</th>
                                <th>PLACE OF BIRTH</th>
                            </tr>
                        </thead>

                        <tbody class="" style="font-size: 11px;">
                            @if ($query)
                            @if ($query->count())
                            @php
                            $counter = ($query->currentPage() - 1) * $query->perPage();
                            @endphp

                            <!-- inactive if 1 -->
                            @foreach ($query as $enroll )
                            <tr>
                                <td>
                                    {{ ++$counter }}
                                </td>
                                <td>
                                    <b>{{$enroll->schedule->batchno}}</b>
                                </td>
                                <td>
                                    <b class="text-uppercase">{{$enroll->trainee->formal_name()}}</b>
                                </td>
                                <td>
                                    <b>{{$enroll->trainee->rank->rank}}</b>
                                </td>
                                <td>
                                    <b>{{optional($enroll->trainee->company)->company}}</b>
                                </td>
                                @if ($enroll->bus)
                                <td>
                                    <b>{{$enroll->bus->busmode}}</b>
                                </td>
                                @else
                                <td>
                                    <b>None</b>
                                </td>
                                @endif

                                @if (!is_null(optional($enroll->dorm)->dorm))
                                <td>
                                    <b>{{ $enroll->dorm->dorm }}</b>
                                </td>

                                @else
                                <td>
                                    <b>None</b>
                                </td>
                                @endif

                                <td>
                                    <b>{{ $enroll->course->coursename }}</b>
                                </td>

                                <td>
                                    <b>{{ $enroll->schedule->startdateformat }}</b>
                                </td>

                                <td>
                                    <b>{{ $enroll->schedule->enddateformat }}</b>
                                </td>

                                <td>
                                    @if ($enroll->trainee->fleet)
                                    <b>{{ $enroll->trainee->fleet->fleet }}</b>
                                    @endif
                                </td>

                                <td>
                                    <b>{{ $enroll->trainee->birthday }}</b>
                                </td>

                                <td>
                                    <b>{{ $enroll->trainee->contact_num }}</b>
                                </td>

                                <td>
                                    <b>{{ $enroll->trainee->email }}</b>
                                </td>

                                <td>
                                    <b>{{ $enroll->trainee->address }}</b>
                                </td>

                                <td>
                                    <b>{{ $enroll->dateconfirmed }}</b>
                                </td>

                                <td>
                                    <b>{{ $enroll->tshirt->tshirt }}</b>
                                </td>

                                <td>
                                    <b>{{ $enroll->checkindate }}</b>
                                </td>

                                <td>
                                    <b>{{ $enroll->checkoutdate }}</b>
                                </td>

                                <td>
                                    <b>{{ $enroll->payment->paymentmode }}</b>
                                </td>

                                <td>
                                    <b> @if ($enroll->isAttending == 1)
                                        Yes
                                        @else
                                        No response
                                        @endif
                                    </b>
                                </td>

                                <td>
                                    <b>N/A</b>
                                </td>

                                <td>
                                    <b>N/A</b>
                                </td>

                                <td>
                                    <b>Trainee</b>
                                </td>

                                <td>
                                    <b>{{ $enroll->trainee->birthplace }}</b>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                            @endif

                        </tbody>
                    </table>
                    <div class="card-footer">
                        <div class="row">
                            @if ($query)
                            {{ $query->links('livewire.components.customized-pagination-link')}}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>