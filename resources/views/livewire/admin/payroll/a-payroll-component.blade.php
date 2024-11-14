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
                <h3>GENERATE PAYROLL</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-12">
                        <div class="mb-3">
                            <label class="form-label">SELECT A WEEK: <span class="text-danger">*</span></label>
                            <select class="form-select" wire:model="selectedWeek">
                                <option value="">----- Select Week -----</option>
                                @foreach($this->getWeekOptions() as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="offset-7 offset-lg-7 offset-md-7 col-lg-5 col-md-5 col-5 py-5 text-end">
                        <button class="btn btn-dark" wire:click="submit" wire:loading.attr="disabled">GENERATE DATA</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-5">
            <div class="card-header mx-auto">
                <h3>PAYROLL HISTORY</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-12">
                        <div class="table-responsive">
                            <table class="table table-sm text-nowrap mb-0 table-centered table-dark" width="100%">
                                <thead>
                                    <tr>
                                        <!-- <th>#</th> -->
                                        <th>#</th>
                                        <th>MEMORANDUM NUMBER</th>
                                        <th>PAYROLL PERIOD</th>
                                        <th>CREATED DATE</th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody class="" style="font-size: 11px;">
                                    @foreach ($payroll_periods as $key => $payroll)
                                    <tr>
                                        <td>{{ ($payroll_periods->currentPage() - 1) * $payroll_periods->perPage() + $loop->iteration }}</td>
                                        @if(optional($payroll)->memo_num)
                                        <td>{{$payroll->memo_num}}</td>
                                        @else
                                        <td>ASSIGN MEMO NUMBER IN THE PAYROLL</td>
                                        @endif
                                        <td>{{$payroll->format_date()}}</td>
                                        <td>{{$payroll->created_at}}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button id="" type="button" class="btn btn-dark btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Edit
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{route('glps.payroll-details', $payroll->hash_id)}}"><i class="bi bi-person-check"></i>&nbsp;View</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>