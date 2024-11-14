<div class="container-fluid mt-5">

    <div class="card text-center">

        <div class="card-header">
            <h1 class="h1 fw-bold mt-3"> {{ $billingstatus_data->billingstatus }}</h1>
            <h5> {{ $company_data->company }}</h5>
            @can('authorizeAdminComponents', 13)
            <a wire:click='passSessionData({{ $companyid2 }},"a.billing-pricematrix")'
                class="btn btn-outline-success btn-sm">Price Matrix</a>
            @endcan
            @if ($billingstatusid == 2 && count($revision_count) > 0)
            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                data-bs-target="#RevisionListModal">
                Revisions
            </button>
            @endif
            <span class="badge text-bg-info float-start">{{ $schedule_data->course->Full_Course_Name }}</span>
            <span class="badge text-bg-info float-end">Training Date: {{ $schedule_data->training_date }}</span>
        </div>
        <style>
            .position-absolute {
                position: relative;
                /* Adjust the z-index as needed to ensure it appears above other elements */
                width: 100%;
                border: 1px solid #e3e6f0;
                background-color: rgb(27, 135, 193);
                /* Ensure it takes the full width of its container */
            }
        </style>
        <div class="col-md-12 m-4">
            <div class="col-md-4 float-start">
                <x-request-message />
                <div class="card" id="courseAccordion">
                    <!-- List group -->
                    <ul class="list-group list-group-flush position-absolute">
                        <li class="list-group-item p-0 bg-transparent">
                            <!-- Toggle -->

                            <a class="d-flex align-items-center text-inherit text-decoration-none py-3 px-4"
                                data-bs-toggle="collapse" href="#courseTwo" role="button" aria-expanded="false"
                                aria-controls="courseTwo">
                                <div class="me-auto text-start">
                                    <h4 class="mb-0 text-white"> <i class="bi bi-receipt me-1"></i> Billing Functions
                                    </h4>
                                    <p class="mb-0 text-white">Click me to show more options.</p>
                                </div>
                                <!-- Chevron -->
                                <span class="chevron-arrow ms-4 text-white">
                                    <i class="bi bi-plus-lg"></i>
                                </span>
                            </a>
                            <!-- / .row -->
                            <!-- Collapse -->
                            <div class="collapse" id="courseTwo" data-bs-parent="#courseAccordion">
                                <!-- List group -->
                                <style>
                                    .list-group-item {
                                        background-color: #f1f5f9;
                                    }
                                </style>
                                <ul class="list-group list-group-flush">

                                    @if ($billingstatusid != 1)
                                    <livewire:admin.billing.child.generate-billing.generate-billing-component
                                        :scheduleid="$scheduleid" :transfercompanyID="$transfercompanyID"
                                        :companyid="$companyid" :companyid2="$companyid2"
                                        :billingstatusid="$billingstatusid" />
                                    <livewire:admin.billing.child.generate-billing.view-attachment-component />
                                    @endif

                                    @if ($billingstatusid == 2)
                                    <livewire:admin.billing.child.generate-billing.change-serial-number-component />
                                    <livewire:admin.billing.child.generate-billing.add-notes-component />
                                    @endif

                                    @can('authorizeAdminComponents', 159)
                                    @if ($billingstatusid == 7)
                                    <livewire:admin.billing.child.generate-billing.add-attachment-component />

                                    @php
                                    $subject = "Payment Slip Sent by ".$authFullname."
                                    ".$schedule_data->course->coursecode." ".$schedule_data->training_date;
                                    @endphp

                                    @if (count($is_payment_slip_uploaded) > 0)
                                    <livewire:admin.billing.child.generate-billing.update-billing-status-component
                                        title="Send Payment Slip" :scheduleid="$scheduleid" :companyid="$companyid"
                                        defaultBankId="1" updateStatus="8" msgTitle="Payment Slip sent to NETI!"
                                        :subject="$subject" :recipient="env('EMAIL_BOD_DEPT')" />
                                    @endif

                                    @endif
                                    @endcan


                                    @if ($billingstatusid == 1)

                                    <livewire:admin.billing.child.generate-billing.start-billing-component
                                        :scheduleid="$scheduleid" :companyid="$companyid" :companyid2="$companyid2"
                                        :billingstatusid="$billingstatusid" />
                                    @elseif ($billingstatusid == 2)
                                    <livewire:admin.billing.child.generate-billing.add-attachment-component />

                                    {{-- Attach/Remove Signature --}}
                                    <livewire:admin.billing.child.generate-billing.attach-billing-staff-signature-component
                                        :scheduleid="$scheduleid" :companyid="$companyid"
                                        is_SignatureAttached="{{ $enroled_data->is_SignatureAttached }}"
                                        signed_By="1" />


                                    @if ($enroled_data->is_SignatureAttached)
                                    <livewire:admin.billing.child.generate-billing.update-billing-status-component
                                        title="Send to BOD Manager" :scheduleid="$scheduleid" :companyid="$companyid"
                                        :companyid2="$companyid2" defaultBankId="{{ $company_data->defaultBank_id }}"
                                        updateStatus="3" msgTitle="Billing Statement sent to BOD Manager!"
                                        subject="BOD Manager Review Process for {{ $schedule_data->Schedule_With_Training_Date }}"
                                        :recipient="env('EMAIL_BOD_MANAGER')" />
                                    @endif
                                    @elseif ($billingstatusid == 3)
                                    <livewire:admin.billing.child.generate-billing.add-attachment-component />
                                    <livewire:admin.billing.child.generate-billing.attach-billing-staff-signature-component
                                        :scheduleid="$scheduleid" :companyid="$companyid"
                                        is_SignatureAttached="{{ $enroled_data->is_Bs_Signed_BOD_Mgr }}"
                                        signed_By="2" />
                                    <livewire:admin.billing.child.generate-billing.send-back-component title="Send Back"
                                        updateStatus="2" :scheduleid="$scheduleid" :companyid="$companyid" />

                                    @if ($enroled_data->is_Bs_Signed_BOD_Mgr)
                                    <livewire:admin.billing.child.generate-billing.update-billing-status-component
                                        title="Send to GM" :scheduleid="$scheduleid" :companyid="$companyid"
                                        defaultBankId="{{ $company_data->defaultBank_id }}" updateStatus="4"
                                        msgTitle="Billing Statement sent to GM!"
                                        subject="General Manager Billing Review for {{ $schedule_data->Schedule_With_Training_Date }}"
                                        :recipient="env('EMAIL_GM')" />
                                    @endif

                                    @elseif ($billingstatusid == 4)
                                    <livewire:admin.billing.child.generate-billing.attach-billing-staff-signature-component
                                        :scheduleid="$scheduleid" :companyid="$companyid"
                                        is_SignatureAttached="{{ $enroled_data->is_GmSignatureAttached }}"
                                        signed_By="3" />
                                    <livewire:admin.billing.child.generate-billing.send-back-component title="Send Back"
                                        updateStatus="2" :scheduleid="$scheduleid" :companyid="$companyid" />

                                    @if ($enroled_data->is_GmSignatureAttached)
                                    <livewire:admin.billing.child.generate-billing.update-billing-status-component
                                        title="Send to BOD Manager" :scheduleid="$scheduleid" :companyid="$companyid"
                                        defaultBankId="{{ $company_data->defaultBank_id }}" updateStatus="5"
                                        msgTitle="Billing Statement sent to BOD Manager!"
                                        subject="BOD Manager Dispatch Operations for {{ $schedule_data->Schedule_With_Training_Date }}"
                                        :recipient="env('EMAIL_BOD_MANAGER')" />
                                    @endif

                                    @elseif ($billingstatusid == 5)
                                    @php
                                    $subject =
                                    'Billing Statement for ' . $schedule_data->Schedule_With_Training_Date;
                                    @endphp
                                    <livewire:admin.billing.child.generate-billing.update-billing-status-component
                                        title="Send to Client" :scheduleid="$scheduleid" :companyid="$companyid"
                                        defaultBankId="{{ $company_data->defaultBank_id }}" :generatestatus="5"
                                        updateStatus="6" msgTitle="Billing Statement sent to Client!"
                                        :subject="$subject" :recipient="$company_email" />
                                    <livewire:admin.billing.child.generate-billing.send-back-component title="Send Back"
                                        updateStatus="2" :scheduleid="$scheduleid" :companyid="$companyid" />

                                    @elseif($billingstatusid == 6)
                                    @can('authorizeAdminComponents', 160)
                                    @php
                                    $subject = "Billing Statement Received by ".$company_data->company."
                                    ".$schedule_data->course->coursecode." ".$schedule_data->training_date;
                                    @endphp
                                    <livewire:admin.billing.child.generate-billing.update-billing-status-component
                                        title="Confirm" :scheduleid="$scheduleid" :companyid="$companyid"
                                        defaultBankId="{{$company_data->defaultBank_id}}" updateStatus="7"
                                        msgTitle="Billing Statement Confirmation sent to NETI!" :subject="$subject"
                                        :recipient="env('EMAIL_BOD_DEPT')" />
                                    @endcan

                                    @elseif ($billingstatusid == 8)
                                    @php
                                    $subject =
                                    'Official Receipt Issuance for ' .
                                    $schedule_data->Schedule_With_Training_Date;
                                    @endphp
                                    <livewire:admin.billing.child.generate-billing.add-attachment-component />

                                    @if ($is_OR_uploaded != 0)
                                    <livewire:admin.billing.child.generate-billing.update-billing-status-component
                                        title="Send O.R. to Client" :scheduleid="$scheduleid" :companyid="$companyid"
                                        defaultBankId="{{ $company_data->defaultBank_id }}" updateStatus="9"
                                        msgTitle="O.R. sent to Client!" :subject="$subject"
                                        :recipient="$company_email" />
                                    @endif

                                    @elseif ($this->billingstatusid == 9)
                                    @can('authorizeAdminComponents', 161)
                                    @php
                                    $subject = "O.R. received by ".$company_data->company."
                                    ".$schedule_data->course->coursecode." ".$schedule_data->training_date;
                                    @endphp
                                    <livewire:admin.billing.child.generate-billing.update-billing-status-component
                                        title="Confirm O.R." :scheduleid="$scheduleid" :companyid="$companyid"
                                        defaultBankId="{{$company_data->defaultBank_id}}" updateStatus="10"
                                        msgTitle="Official Receipt Confirmed" :subject="$subject"
                                        :recipient="env('EMAIL_BOD_DEPT')" />
                                    @endif
                                    @endcan

                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="card-body row mt-8">

            <div class="col-md-12 table-responsive">
                {{-- Attendance Sheet Button HERE!!! --}}
                <a href="{{ route($attendance_route, ['scheduleid' => implode(',',$scheduleid), 'companyId' => $companyid]) }}"
                    class="btn btn-outline-primary mb-2 me-2 float-start btn-sm">Attendance Sheet</a>

                @if (!in_array($companyid, $nykComps))
                <a href="#" wire:click="openModalBreakdown(0)" class="btn btn-outline-info mb-2 float-start btn-sm">View
                    Breakdown</a>
                @else
                <a href="#" wire:click="openModalBreakdown(1)" class="btn btn-outline-info mb-2 float-start btn-sm">View
                    Breakdown</a>
                @endif

                <livewire:admin.billing.child.generate-billing.trainee-list-component :trainees="$trainees" />
            </div>

            <div wire:ignore.self class="modal fade" id="billedModal" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalScrollableTitle">Mark as Billed
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <label for="billedRemarks" class="form-label text-left">Remarks</label>
                            <textarea type="text" name="" id="billedRemarks" wire:model="billedRemarks"
                                class="form-control" placeholder="Enter your remarks here.."></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" wire:click="saveBilled" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            <livewire:admin.billing.child.generate-billing.trainee-list-component-modals />
            <livewire:admin.billing.child.generate-billing.add-notes-modal-component :scheduleid="$scheduleid" />

        </div>

        <div class="card-footer text-body-secondary">
        </div>

        {{-- upload attachment modal --}}
        <livewire:admin.billing.child.generate-billing.add-attachment-modal-component :scheduleid="$scheduleid"
            :companyid="$companyid" :billingstatusid="$billingstatusid" :trainees="$trainees" />

        {{-- view attachment modal --}}
        <livewire:admin.billing.child.generate-billing.view-attachment-modal-component :scheduleid="$scheduleid"
            :companyid="$companyid" />

        {{-- change serial number modal --}}
        <livewire:admin.billing.child.generate-billing.change-serial-number-modal :scheduleid="$scheduleid"
            :companyid="$companyid" />

        {{-- revision list modal --}}
        <livewire:admin.billing.child.generate-billing.revision-list-component :scheduleId="$scheduleid"
            companyId="{{ $companyid }}" />

        {{-- Price Breakdown Modal --}}
        <livewire:admin.billing.child.generate-billing.breakdown-prices-modal wire:key="{{rand(0,19)}}"
            :scheduleid="$scheduleid" :companyid="$companyid" :trainees="$trainees" :foreign="$foreign" />
    </div>

</div>