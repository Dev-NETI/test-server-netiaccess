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
                            Authority to Deduct
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-3 col-12">
                <div class="mb-3">
                    <label class="form-label">Month<span class="text-danger">*</span></label>
                    <select class="form-select " wire:model="selected_month" @if ($disabled) disabled @endif>
                        <option value="">Select Month</option>
                        @foreach ($months as $key => $month)
                        <option value="{{$key}}">{{$month}}</option>
                        @endforeach
                    </select>
                    @error('selected_batch')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-12">
                <div class="mb-3">
                    <label class="form-label">Batch Week<span class="text-danger">*</span></label>
                    <select class="form-select " wire:model="selected_batch" placeholder="Click to Batch Week" @if ($disabled) disabled @endif>
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
            <div class="col-lg-2 col-md-2 col-12">
                <div class="mb-3">
                    <label class="form-label">Payment Mode<span class="text-danger">*</span></label>
                    <select class="form-select " wire:model="paymentmodeid" @if ($disabled) disabled @endif>
                        <option value="">Select Payment Option</option>
                        <option value="3">SALARY DEDUCTION</option>
                        <option value="4">NTIF BOARDING LOAN</option>
                    </select>
                    @error('selected_batch')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-12 py-5">
                <button class="btn btn-dark" wire:click="generateData" @if ($disabled) disabled @endif>GENERATE DATA</button>
                <button class="btn btn-dark" wire:click="reset_button">CLEAR DATA</button>
                <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#settings_modal_atd"><i class="nav-icon fe fe-settings fs-3"></i></button>

            </div>

            <h3><i>Summary of Billing</i></h3>
            <hr>
            <div class="col-12 col-lg-12">
                <table class="table table-bordered table-dark">
                    <thead style="font-size: 11px;">
                        <th>ACTION</th>
                        <th>SERIAL N0.</th>
                        <th>NAME OF CREW</th>
                        <th>RANK</th>
                        <th>TEAM/FLEET</th>
                        <th>TRAINING</th>
                        <th>TRAINING DATE</th>
                        <th>ASSESSMENT FEE</th>
                        @if ($paymentmodeid == 3)
                        <th>DORM PRICE</th>
                        <th>MEAL PRICE</th>
                        <th>TOTAL</th>
                        @endif

                    </thead>
                    <tbody>
                        @if($atds)
                        @foreach ($atds as $index => $atd)
                        <tr style="font-size: 11px;">
                            <td>
                                <div class="btn-group" role="group">
                                    <button id="" type="button" class="btn btn-dark btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Edit
                                    </button>
                                    <div class="dropdown-menu">
                                        <button class="dropdown-item" data-bs-toggle="modal" wire:click="edit_enroll({{$atd->enroledid}})" data-bs-target="#editenrollment"><i class="bi bi-person-check"></i>&nbsp;Enrollment</button>
                                    </div>
                                </div>
                            </td>
                            <td>{{$atd->enroledid}}</td>
                            <td>{{strtoupper($atd->trainee->formal_name())}}</td>
                            <td>{{$atd->trainee->rank->rank}}</td>
                            <td>{{optional($atd->trainee->fleet)->fleet}}</td>
                            <td>{{$atd->course->coursecode}} - {{$atd->course->coursename}}</td>
                            <td>
                                @if($atd->schedule->startdateformat == $atd->schedule->enddateformat)
                                {{ \Carbon\Carbon::parse($atd->schedule->startdateformat)->format('M d,Y') }}
                                @else
                                {{ \Carbon\Carbon::parse($atd->schedule->startdateformat)->format('M d,Y') }} - {{
                                \Carbon\Carbon::parse($atd->schedule->enddateformat)->format('M d,Y') }}
                                @endif
                            </td>
                            <td>
                                <div wire:click.prevent="enableAssessment({{$index}}, {{$atd->t_fee_price}})" wire:keydown.enter="save_assessment({{$atd->enroledid}})">
                                    @if ($editIndex1 === $index)
                                    <input type="number" wire:model.defer="input_assessment" placeholder="{{$atd->t_fee_price}}" value="{{$atd->t_fee_price}}" class="form-control form-control-sm" />
                                    @else
                                    ₱ {{ number_format($atd->t_fee_price, 2) }}
                                    @endif
                                </div>
                            </td>
                            @if ($paymentmodeid == 3)
                            <td>
                                <div wire:click.prevent="enableDorm({{$index}}, {{$atd->dorm_price}})" wire:keydown.enter="save_dorm({{$atd->enroledid}})">
                                    @if ($editIndex2 === $index)
                                    <input type="number" wire:model.defer="input_dorm" placeholder="{{$atd->dorm_price}}" value="{{$atd->dorm_price}}" class="form-control form-control-sm" />
                                    @else
                                    ₱ {{number_format($atd->dorm_price,2)}}
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div wire:click.prevent="enableMeal({{$index}}, {{$atd->meal_price}})" wire:keydown.enter="save_meal({{$atd->enroledid}})">
                                    @if ($editIndex3 === $index)
                                    <input type="number" wire:model.defer="input_meal" placeholder="{{$atd->meal_price}}" value="{{$atd->meal_price}}" class="form-control form-control-sm" />
                                    @else
                                    ₱ {{number_format($atd->meal_price,2)}}
                                    @endif
                                </div>
                            </td>
                            <td>₱ {{number_format($atd->total,2)}}</td>
                            @endif


                        </tr>
                        @endforeach
                        <tr style="font-size: 11px;">
                            <td colspan="7"></td>
                            <td colspan="1">₱ {{number_format($grand_fee_price,2)}}</td>
                            @if ($paymentmodeid == 3)
                            <td colspan="1">₱ {{number_format($grand_dorm,2)}}</td>
                            <td colspan="1">₱ {{number_format($grand_meal,2)}}</td>
                            <td colspan="1">₱ {{number_format($grand_total,2)}}</td>
                            @endif
                        </tr>
                        @else
                        <tr style="font-size: 11px;">
                            <td class="text-center" colspan="12">No Data Found</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
                <br>
            </div>
        </div>

        @if($atds)
        <div class="row">
            <div class="col-md-12">
                <a class="btn btn-dark float-end" target="_blank" href="{{route('a.billing-generate-credit-memo')}}">F-NETI-082: DEBIT/CREDIT MEMO</a>
            </div>
        </div>
        @endif
    </div>
    @include('livewire.admin.billing.a-billing-settings-atd-modal')
    @include('livewire.admin.enrollment.a-edit-enrollment-modal');

</section>