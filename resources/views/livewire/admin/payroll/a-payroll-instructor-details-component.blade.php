<section class="mt-3">
    <span wire:loading>
        <livewire:components.loading-screen-component />
    </span>
    <div class="container-fluid my-6">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-12">
                <div class="border-bottom pb-3 mb-3 d-flex justify-content-between align-items-center">
                    <div class="mb-2 mb-lg-0">
                        <h1 class="mb-1 h2 fw-bold">
                            Guest Lecturer Payroll
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header mx-auto">
                <h3>GENERATE PAYROLL INSTRUCTOR</h3>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="submit_data">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-6">
                            <div class="mb-3">
                                <label class="form-label">SELECT START DATE: <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" wire:model="startDate" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-6">
                            <div class="mb-3">
                                <label class="form-label">SELECT END DATE: <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" wire:model="endDate" required>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-12">
                            <div class="mb-3">
                                <label class="form-label">SELECT INSTRUCTOR: <span class="text-danger">*</span></label>
                                <select name="" class="form-select" wire:model="selected_instructor" required>
                                    <option value="">----- Select Instructor -----</option>
                                    @foreach($instructors as $instructor)
                                    <option value="{{ $instructor->userid }}">{{ strtoupper($instructor->user->formal_name()) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="offset-7 offset-lg-7 offset-md-7 col-lg-5 col-md-5 col-5 py-5 text-end">
                            <button class="btn btn-dark" wire:loading.attr="disabled">GENERATE DATA</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card mt-5">
            <div class="card-header mx-auto">
                <h3>PAYROLL INSTRUCTOR HISTORY</h3>
            </div>
            <div class="text-end m-3">
                @if(isset($instructor_data) && $instructor_data->count() > 0)
                <a class="btn btn-dark" wire:loading.attr="disabled" href="{{ route('glps.instructor-generate-payroll-pdf', ['startDate' => $startDate, 'endDate' => $endDate, 'selected_instructor' => $selected_instructor]) }}" target="_blank">EXPORT DATA</a>
                @endif
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-12">
                        <div class="table-responsive">
                            <table class="table table-sm text-nowrap mb-0 table-centered table-dark" width="100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>MEMORANDUM NUMBER</th>
                                        <th>NAME OF INSTRUCTOR</th>
                                        <th>RANK</th>
                                        <th>ASSIGNMENT / COURSE CONDUCTED</th>
                                        <th>DATE COVERED</th>
                                        <th>NO. OF DAY</th>
                                        <th>NO. OF HR/OT</th>
                                        <th>MIN. LATE</th>
                                        <th>DEDUCTION</th>
                                        <th>RATE PER DAY</th>
                                        <th>RATE PER HR</th>
                                        <th>SUBTOTAL</th>
                                        <th>TOTAL</th>
                                    </tr>
                                </thead>

                                <tbody class="" style="font-size: 11px;">
                                    @if(isset($instructor_data) && $instructor_data->count() > 0)
                                    @foreach ($instructor_data as $key => $payroll_log)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        @if(optional($payroll_log->payroll_period)->memo_num)
                                        <td>{{$payroll_log->payroll_period->memo_num}}</td>
                                        @else
                                        <td>ASSIGN MEMO NUMBER IN THE PAYROLL</td>
                                        @endif
                                        <td>{{ $payroll_log->user->formal_name() }}</td>
                                        @if(optional(optional($payroll_log->user)->instructor)->rank)
                                        <td>{{ $payroll_log->user->instructor->rank->rankacronym }} - {{ $payroll_log->user->instructor->rank->rank }}</td>
                                        @else
                                        <td>NEED TO ASSIGN RANK</td>
                                        @endif
                                        @if(optional($payroll_log->course)->FullCourseName)
                                        <td>{{ $payroll_log->course->FullCourseName }}</td>
                                        @else
                                        <td>NO ASSIGNMENT COURSE</td>
                                        @endif
                                        <td>
                                            @php
                                            $dates = json_decode($payroll_log->date_record);
                                            sort($dates);
                                            $formattedDates = [];

                                            $startDate = null;
                                            $endDate = null;

                                            foreach ($dates as $date) {
                                            if ($startDate === null) {
                                            $startDate = $date;
                                            $endDate = $date;
                                            } elseif (strtotime($date) == strtotime("+1 day", strtotime($endDate))) {
                                            $endDate = $date;
                                            } else {
                                            if ($startDate == $endDate) {
                                            $formattedDates[] = date('d M', strtotime($startDate));
                                            } else {
                                            $formattedDates[] = date('d', strtotime($startDate)) . '-' . date('d M', strtotime($endDate));
                                            }
                                            $startDate = $date;
                                            $endDate = $date;
                                            }
                                            }

                                            if ($startDate == $endDate) {
                                            $formattedDates[] = date('d M', strtotime($startDate));
                                            } else {
                                            $formattedDates[] = date('d', strtotime($startDate)) . '-' . date('d M', strtotime($endDate));
                                            }

                                            $formattedDateRange = implode(', ', $formattedDates);
                                            echo $formattedDateRange;
                                            @endphp
                                        </td>
                                        <td>{{ $payroll_log->no_day }}</td>
                                        <td>{{ $payroll_log->no_ot }}</td>
                                        <td>{{ $payroll_log->no_late }}</td>
                                        <td>{{ number_format($payroll_log->deduction, 2) }}</td>
                                        <td>{{ number_format($payroll_log->rate_per_day, 2) }}</td>
                                        <td>{{ number_format($payroll_log->rate_per_hr, 2) }}</td>
                                        <td>{{ number_format($payroll_log->subtotal, 2) }}</td>
                                        <td>{{ number_format($payroll_log->total, 2) }}</td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="14" class="text-center">NO DATA FOUND</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>