<div class="container-fluid">
    <span wire:loading>
        <livewire:components.loading-screen-component />
    </span>

    <div class="row">
        <div class="col-lg-6 col-md-6 col-6 mt-3 mb-3">
            <style>
                .zoom {
                    /* background-color: green; */
                    transition: transform .2s;
                    margin: 0 auto;
                }

                .zoom:hover {
                    -ms-transform: scale(5);
                    /* IE 9 */
                    -webkit-transform: scale(5);
                    /* Safari 3-8 */
                    transform: scale(5);
                }
            </style>
            <div class="row">
                <div class="col-12">
                    <h3>Certificate Approval</h3>
                </div>
                <div class="col-12">

                    @can('authorizeAdminComponents', 111)
                    <button class="btn btn-info float-end mb-3 ms-3" wire:click.prevent="performAction">APPROVE SELECTED</button>
                    <div x-data="{ url: '{{ route('a.viewattendance', ['scheduleid' => $scheduleid]) }}' }">
                        <button @click="window.open(url, '_blank')" class="btn btn-primary float-end mb-3 ms-3">
                            <i class="bi bi-filetype-pdf"></i> F-NETI-026: ATTENDANCE
                        </button>
                    </div>
                    <button wire:click.prevent="sendApproval({{$scheduleid}})" class="btn btn-success float-end mb-3">
                        <i class="bi bi-filetype-pdf "></i> SEND EMAIL FOR RELEASING
                    </button>
                    @endcan
                </div>
                <div class="col-12">
                    <h4>STATUS:
                        @if($schedule->status_releasing === 0)
                        <span class="badge bg-danger">Email Not Sent</span>
                        @else
                        <span class="badge bg-success">Email Successfully Sent on {{ \Carbon\Carbon::parse($schedule->updated_at)->format('F j, Y, g:i a') }}</span>
                        @endif
                    </h4>
                </div>
            </div>

            <div class="card">
                <div class="table-responsive">
                    <table class="table table-sm text-nowrap mb-0 table-centered">
                        <thead class="table-light">
                            <tr>
                                <th>
                                    <div class="form-check">
                                        <input class="form-check-input ms-2" type="checkbox" wire:click="toggleSelectAll({{$scheduleid}})">
                                    </div>
                                </th>
                                <th scope="col" class="text-center">Action</th>
                                <th scope="col">Picture</th>
                                <th scope="col">Name</th>
                                <th scope="col">Birthday</th>
                                <th scope="col">Remarks</th>
                                <th scope="col">Status</th>
                                <th scope="col">Reason <i class="text-danger">(Applicable for Invalid Cert)</i></th>
                                <th scope="col"> Remedial </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($enrolled as $enrol)
                            <tr wire:key="enrol-{{ $enrol->enroledid }}">
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input ms-2" id="enrol-{{ optional($enrol->certificate_history)->certificatehistoryid }}" type="checkbox" wire:model.defer="selectedItems" value="{{ optional($enrol->certificate_history)->certificatehistoryid }}">
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if (optional($enrol->certificate_history)->certificatehistoryid)
                                    @can('authorizeAdminComponents', 111)
                                    @if(optional($enrol)->passid != 2)
                                    @if($enrol->certificate_history->is_approve == 2 || $enrol->certificate_history->is_approve == null )
                                    <button class="btn btn-md btn-success" wire:click="approved_cert({{$enrol->certificate_history->certificatehistoryid}})" @if(optional($enrol)->pendingid == 3) disabled @endif>
                                        <i class="bi bi-check2-circle"></i>
                                    </button>
                                    @endif
                                    @endif
                                    @endcan
                                    @if($enrol->certificate_history->is_approve != 1)
                                    <button class="btn btn-md btn-danger" wire:click="dis_cert({{$enrol->certificate_history->certificatehistoryid}})">
                                        <i class="bi bi-x-circle-fill"></i>
                                    </button>
                                    @endif
                                    @endif
                                </td>
                                <td>
                                    @if (optional($enrol->trainee)->imagepath)
                                    <img src="{{asset('storage/traineepic/'. $enrol->trainee->imagepath)}}" alt="" height="200" class="avatar-md me-2 zoom">
                                    @else
                                    <img src="{{asset('assets/images/avatar/noimageavailable.jpg')}}" alt="" height="200" class="avatar-md me-2 zoom">
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex align-items-center">
                                        {{optional($enrol->trainee)->certificate_name()}}
                                    </div>
                                </td>

                                <td>
                                    @if ($schedule->course->coursetypeid != 7)
                                    <div class="d-flex align-items-center">
                                        {{date('F d, Y', strtotime(optional($enrol->trainee)->birthday))}}
                                    </div>
                                    @else
                                    N/A
                                    @endif
                                </td>



                                <td>
                                    @if (optional($enrol->certificate_history)->registrationnumber)
                                    @if (optional($enrol)->passid == 1)
                                    <span class="badge bg-success">PASSED</span>
                                    @elseif(optional($enrol)->passid == 2)
                                    <span class="badge bg-danger">FAILED</span>
                                    @elseif(optional($enrol)->pendingid == 3)
                                    <span class="badge bg-warning">REMEDIAL</span>
                                    @else
                                    <span class="badge bg-info">ON-GOING</span>
                                    @endif
                                    @else
                                    NO RECORD FOUND
                                    @endif
                                </td>

                                <td>
                                    @if (optional($enrol->certificate_history)->registrationnumber)
                                    @if (optional($enrol->certificate_history)->is_approve == 1)
                                    <span class="badge bg-success">APPROVED</span>
                                    @elseif(optional($enrol->certificate_history)->is_approve == 2)
                                    <span class="badge bg-danger">INVALID</span>
                                    @else
                                    <span class="badge bg-warning">FOR APPROVAL</span>
                                    @endif
                                    @else
                                    NO RECORD FOUND
                                    @endif
                                </td>

                                <td>
                                    @if(optional($enrol->certificate_history)->invalid_comment)
                                    <span class="text-danger">{{$enrol->certificate_history->invalid_comment}}</span>
                                    @else
                                    <div class="">
                                        <textarea class="form-control" id="textarea-input" rows="1" wire:model.defer="comments.{{optional($enrol->certificate_history)->certificatehistoryid}}"></textarea>
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    @if($enrol->remedial_sched && $enrol->enroledid)
                                    <a class="btn btn-md btn-primary" href="{{ route('a.viewsoloattendance', ['scheduleid' => $enrol->remedial_sched, 'enroledid' => $enrol->enroledid]) }}" target="_blank">
                                        <i class="bi bi-filetype-pdf"></i>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="card-footer">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-center mb-0">
                                @if ($enrolled)
                                {{ $enrolled->links('livewire.components.customized-pagination-link')}}
                                @endif
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="table-responsive">
                    <table class="table table-sm text-nowrap mb-0 table-centered">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Adjustment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($enrolled->where('certificate_history.invalid_comment', '!=', null) as $enrol)
                            <tr wire:key="enrol-{{ optional($enrol->certificate_history)->certificatehistoryid }}">
                                <td>
                                    <div class="d-flex align-items-center">
                                        {{optional($enrol->trainee)->certificate_name()}}
                                    </div>
                                </td>

                                <td>
                                    @if(optional($enrol->certificate_history)->adjustment_comment)
                                    <span class="text-dark">{{$enrol->certificate_history->adjustment_comment}}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <div class="col-lg-6 col-md-6 col-6 mt-3 mb-3">

            <livewire:admin.approval.a-certificate-frame-component :training_id="$scheduleid" :key="$scheduleid" />

        </div>
    </div>

</div>