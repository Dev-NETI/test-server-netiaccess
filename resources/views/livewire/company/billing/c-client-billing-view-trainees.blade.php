<div>
    <div class="py-lg-14 bg-light pt-8 pb-10">
        <div class="container">




            <div class="card text-center">

                <div class="card-header">
                    <h1 class="card-title">Payment Monitoring</h1>
                    <p class="card-text">{{ $billingstatus_data->billingstatus }}</p>
                    <p class="float-start text-danger">{{ $schedule_data->course->coursecode." /
                        ".$schedule_data->course->coursename }}</p>
                    <p class="float-end text-danger">{{ $schedule_data->training_date }}</p>
                </div>

                <div class="card-body row">
                    <div class="col-md-7">
                        <x-request-message />
                        <div class="card bg-info" style="min-width: 50em;" id="courseAccordion" style="">
                            <!-- List group -->
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item p-0 bg-transparent">
                                    <!-- Toggle -->
                                    <a class="d-flex align-items-center text-inherit text-decoration-none py-3 px-4"
                                        data-bs-toggle="collapse" href="#courseTwo" role="button" aria-expanded="false"
                                        aria-controls="courseTwo">
                                        <div class="me-auto">
                                            <p class="mb-0 text-muted fs-3"><strong class="text-white">Billing
                                                    Functions</strong><br>
                                            </p>
                                        </div>
                                        <!-- Chevron -->
                                        <span class="chevron-arrow ms-4">
                                            <i class="fe fe-chevron-down fs-4 text-white"></i>
                                        </span>
                                    </a>
                                    <!-- / .row -->
                                    <!-- Collapse -->
                                    <div class="collapse show" id="courseTwo" data-bs-parent="#courseAccordion">
                                        <!-- List group -->
                                        <ul class="list-group list-group-flush">
                                            @if($this->billingstatusid != 1)
                                            <livewire:admin.billing.child.generate-billing.generate-billing-component
                                                :scheduleid="$scheduleid" :companyid="$companyid"
                                                :billingstatusid="$billingstatusid" />
                                            <livewire:admin.billing.child.generate-billing.view-attachment-component />
                                            @endif

                                            @if($this->billingstatusid == 6)
                                            @php
                                            $subject = "Billing Statement Received by ".$company_name."
                                            ".$schedule_data->course->coursecode." ".$schedule_data->training_date;
                                            @endphp
                                            <livewire:admin.billing.child.generate-billing.update-billing-status-component
                                                title="Confirm" :scheduleid="$scheduleid" :companyid="$companyid"
                                                defaultBankId="{{$defaultBank_id}}" updateStatus="7"
                                                msgTitle="Billing Statement Confirmation sent to NETI!"
                                                :subject="$subject" :recipient="env('EMAIL_BOD_DEPT')" />
                                            @elseif($this->billingstatusid == 7)
                                            @php
                                            $subject = "Payment Slip Sent by ".$company_name."
                                            ".$schedule_data->course->coursecode." ".$schedule_data->training_date;
                                            @endphp
                                            <livewire:admin.billing.child.generate-billing.add-attachment-component />
                                            @if (count($is_payment_slip_uploaded) > 0)
                                            <livewire:admin.billing.child.generate-billing.update-billing-status-component
                                                title="Send Payment Slip" :scheduleid="$scheduleid"
                                                :companyid="$companyid" defaultBankId="{{$defaultBank_id}}"
                                                updateStatus="8" msgTitle="Payment Slip sent to NETI!"
                                                :subject="$subject" :recipient="env('EMAIL_BOD_DEPT')" />
                                            @endif
                                            @elseif ($this->billingstatusid == 9)
                                            @php
                                            $subject = "O.R. received by ".$company_name."
                                            ".$schedule_data->course->coursecode." ".$schedule_data->training_date;
                                            @endphp
                                            <livewire:admin.billing.child.generate-billing.update-billing-status-component
                                                title="Confirm O.R." :scheduleid="$scheduleid" :companyid="$companyid"
                                                defaultBankId="{{$defaultBank_id}}" updateStatus="10"
                                                msgTitle="Official Receipt Confirmed" :subject="$subject"
                                                :recipient="env('EMAIL_BOD_DEPT')" />
                                            @endif

                                            <li class="list-group-item">
                                                <div class="row h-100">
                                                    <div class="col-md-6">
                                                        <a href="{{route($attendance_route, ['scheduleid' => $scheduleid, 'companyId' => $companyid])}}"
                                                            class="d-flex justify-content-between align-items-center text-inherit text-decoration-none">
                                                            <div class="text-truncate">
                                                                <span
                                                                    class="icon-shape bg-success text-white icon-sm rounded-circle me-2"><i
                                                                        class="bi bi-receipt-cutoff"></i></span>
                                                                <span>Attendance Sheet</span>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-12 mt-2 table-responsive">
                        <livewire:admin.billing.child.generate-billing.trainee-list-component :trainees="$trainees" />
                    </div>

                </div>

                <div class="card-footer text-body-secondary">
                </div>

            </div>


            {{-- upload attachment modal --}}
            <livewire:admin.billing.child.generate-billing.add-attachment-modal-component :scheduleid="$scheduleid"
                :companyid="$companyid" :billingstatusid="$billingstatusid" />
            {{-- view attachment modal --}}
            <livewire:admin.billing.child.generate-billing.view-attachment-modal-component :scheduleid="$scheduleid"
                :companyid="$companyid" />



        </div>
    </div>
</div>