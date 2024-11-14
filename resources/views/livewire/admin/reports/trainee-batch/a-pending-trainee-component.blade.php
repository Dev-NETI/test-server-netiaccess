<section class="container-fluid p-4">
    <span wire:loading>
        <livewire:components.loading-screen-component />
    </span>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-flex justify-content-between align-items-center">
                <div class="mb-2 mb-lg-0">
                    <h1 class="mb-1 h2 fw-bold">
                        Generate Pending Trainee Batch Report
                        <span class="fs-5 text-muted"></span>
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{route('a.dashboard')}}">Administrator</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{route('a.report-dashboard')}}">Reports</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Generate Trainee Batch report
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
                <a class="btn btn-primary d-inline ms-3" href="{{ route('a.pending-trainee-pdf', ['selected_batch' => $selected_batch]) }}" target="_blank">GENERATE WEEKLY PENDING TRAINEE BATCH</a>
            </div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="card">
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
                                <th>#</th>
                                <th>BATCH NO.</th>
                                <th>FULL NAME</th>
                                <th>POSITION</th>
                                <th>COMPANY</th>
                                <th>COURSE</th>
                                <th>START</th>
                                <th>END</th>
                                <th>FLEET</th>
                            </tr>
                        </thead>

                        <tbody class="" style="font-size: 11px;">
                            @if ($query)
                            @if ($query->count())
                            @php
                            $counter = ($query->currentPage() - 1) * $query->perPage();
                            @endphp

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
                                    <b>{{$enroll->trainee->company->company}}</b>
                                </td>

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
                                    <b>{{ optional($enroll->trainee->fleet)->fleet }}</b>
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