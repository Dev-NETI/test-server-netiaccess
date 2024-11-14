<section>

    <div class="py-lg-14 bg-light pt-8 pb-10">


        <div class="container">
            <div class="row">


                <div class="col-md-12 col-12">

                    <div class="row text-center">
                        <div class="col-md-12 px-lg-10 mb-8 mt-6">


                            <span class="text-uppercase text-primary fw-semibold ls-md">Payment Monitoring
                            </span>


                            <h2 class="h1 fw-bold mt-3">{{ $billingstatus_data->billingstatus }}
                            </h2>


                            <p class="mb-0 fs-4">{{ $billingstatus_data->billingstatus }}</p>

                        </div>
                    </div>

                    <div class="row table-responsive bg-white ">

                        @if ($checkallStatus == 1)
                        <div class="col-md-6 mt-5">
                            <button class="btn btn-primary" wire:click="toggleBulkPayment()">
                                Upload Proof of Payment
                            </button>
                        </div>
                        @endif

                        <div class="col-md-6 {{ $checkallStatus ? '' : 'offset-md-6' }}  mt-5">
                            <input type="search" class="form-control float-right" placeholder="Search..."
                                wire:model.debounce.500ms="search">
                        </div>

                        <div class="col-md-12">
                            <table class="table table-hover table-bordered mt-3 ml-3 mr-3 mb-3">
                                <thead>
                                    <tr class="text-center">
                                        @if ($billingstatusid == 7)
                                        <th style="min-width: 10px;" scope="col"><button
                                                class="btn btn-sm btn-{{$allcheck ? 'danger' : 'primary'}}"
                                                type="checkbox" title="{{$allcheck ? 'Remove All' : 'Check All'}}"
                                                wire:click="checkAll({{$allcheck}})">
                                                @if (!$allcheck)
                                                <i class="bi bi-check2-all"></i>
                                                @else
                                                <i class="bi bi-x"></i>
                                                @endif</button>
                                        </th>
                                        @endif
                                        <th scope="col">Course</th>
                                        <th scope="col">Training Date</th>
                                        <th scope="col">Company</th>
                                        <th scope="col">Serial Number</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @if(!empty($schedules))
                                    @foreach ($schedules as $schedule)
                                    <tr>
                                        @if ($billingstatusid == 7)
                                        <td class="text-center" style="min-width: 10px;" scope="col"><input
                                                type="checkbox" wire:model="inputTdCheck.{{$schedule['scheduleid']}}">
                                        </td>
                                        @endif
                                        <td>{{ $schedule['coursecode'] }} / {{ $schedule['coursename'] }}</td>
                                        <td>{{ $schedule['startdateformat'] }} {{ $schedule['enddateformat'] }}</td>
                                        @php
                                        if ($schedule['enroledcompanyid'] != NULL) {
                                        $company = $this->getCompanydetails($schedule['enroledcompanyid']);
                                        }
                                        @endphp
                                        <td>{{ $schedule['enroledcompanyid'] != NULL ? $company->company :
                                            $schedule['company'] }}</td>
                                        <td>{{ $schedule['billingserialnumber'] }}</td>
                                        <td><a wire:click="passSessionData({{$schedule['scheduleid']}},{{ $schedule['enroledcompanyid'] != NULL ? $company->companyid :
                                            $schedule['companyid'] }})" class="btn btn-primary" title="View"><i
                                                    class="bi bi-eye"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="6">No schedules found.</td>
                                    </tr>
                                    @endif

                                </tbody>
                            </table>
                        </div>

                        <div class="row">
                            {{-- {{ $schedules->links('livewire.components.customized-pagination-link')}} --}}
                        </div>

                        <div wire:ignore.self class="modal fade" tabindex="-1" role="dialog" id="uploadProofPayment">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Upload Proof of Payment</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="uploadForm" action="" enctype="multipart/form-data"
                                            wire:submit.prevent="uploadProof">
                                            <div class="row p-0 gap-1">
                                                <div class="col-lg-12">
                                                    <label for="">Title</label>
                                                    <input type="text" wire:model="proofTitle" class="form-control"
                                                        placeholder="Enter file title ..">
                                                </div>
                                                <div class="col-lg-12">
                                                    <label for="">Upload File</label>
                                                    <input type="file" wire:model="proofPaymentForm"
                                                        class="form-control" required>
                                                </div>
                                            </div>
                                        </form>
                                        @error('proofPaymentForm')
                                        <small class="text-danger">
                                            {{$message}}
                                        </small>
                                        @enderror
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-sm btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="submit" form="uploadForm" class="btn btn-sm btn-primary">
                                            <span wire:loading.remove wire:target="proofPaymentForm">Upload</span>
                                            <span wire:loading wire:target="proofPaymentForm">
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                    aria-hidden="true"></span>
                                                <span class="me-1">Uploading</span>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

</section>