<div class="col-lg-12 col-md-12 col-12 mt-3">
    <h3>Certificate Releasing</h3>
    <div class="tab-content">
        <div class="tab-pane fade active show" id="tabPaneList" role="tabpanel" aria-labelledby="tabPaneList">
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-sm text-nowrap mb-0 table-centered">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col"></th>
                                <th scope="col">Name</th>
                                <th scope="col">Certificate Number</th>
                                <th scope="col">Registration Number</th>
                                <th scope="col">CLN</th>
                                <th scope="col">Control Number</th>
                                <th scope="col">Issued By</th>
                                <th scope="col">Approved By</th>
                                <th scope="col">Remarks</th>
                                <th scope="col">Status</th>
                                <th scope="col">Reason</th>
                                <th scope="col">Adjustment</th>
                                <th scope="col" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($enrolled as $enrol)
                            <tr>
                                <td>
                                    <span>
                                        <small>
                                            @if (optional($enrol->certificate_history)->certificatehistoryid)
                                            <i>#{{optional($enrol->certificate_history)->certificatehistoryid}} </i>
                                            @else
                                            ---
                                            @endif
                                        </small>
                                    </span>
                                </td>
                                <td>
                                    @if (optional($enrol->trainee)->imagepath)
                                    <img src="{{asset('storage/traineepic/'. $enrol->trainee->imagepath)}}" alt="" height="200" class="avatar-xl me-2 zoom">
                                    @else
                                    <img src="{{asset('assets/images/avatar/noimageavailable.jpg')}}" alt="" height="200" class="avatar-xl me-2 zoom">
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        {{optional($enrol->trainee)->certificate_name()}}
                                    </div>
                                </td>

                                <td>
                                    @if($editingCertId && $editingCertId == optional($enrol->certificate_history)->certificatehistoryid)
                                    <input type="text" class="form-control form-control-sm text-dark" wire:model.defer="cert_num" placeholder="insert here.." wire:loading.remove>
                                    @elseif(optional($enrol->certificate_history)->certificatenumber)
                                    {{optional($enrol->certificate_history)->certificatenumber}}
                                    @else
                                    NO RECORD FOUND
                                    @endif

                                </td>

                                <td>
                                    @if($editingCertId && $editingCertId == optional($enrol->certificate_history)->certificatehistoryid)
                                    <input type="text" class="form-control form-control-sm text-dark" wire:model.defer="reg_num" placeholder="insert here.." wire:loading.remove>
                                    @elseif(optional($enrol->certificate_history)->certificatenumber)
                                    {{optional($enrol->certificate_history)->registrationnumber}}
                                    @else
                                    NO RECORD FOUND
                                    @endif
                                </td>

                                <td>
                                    @if (optional($enrol->certificate_history)->cln_type)
                                    {{optional($enrol->certificate_history)->cln_type}}
                                    @else
                                    NO RECORD FOUND
                                    @endif
                                </td>

                                <td>
                                    @php
                                    $cert_history = optional($enrol->certificate_history);
                                    @endphp

                                    @if ($cert_history->controlnumber)
                                    @php
                                    switch (strlen($cert_history->controlnumber)) {
                                    case 1:
                                    $cert_serial = '0000' . $cert_history->controlnumber;
                                    break;
                                    case 2:
                                    $cert_serial = '000' . $cert_history->controlnumber;
                                    break;
                                    case 3:
                                    $cert_serial = '00' . $cert_history->controlnumber;
                                    break;
                                    case 4:
                                    $cert_serial = '0' . $cert_history->controlnumber;
                                    break;
                                    case 5:
                                    $cert_serial = '' . $cert_history->controlnumber;
                                    break;
                                    }
                                    @endphp
                                    {{ $cert_serial }}
                                    @else
                                    NO RECORD FOUND
                                    @endif
                                </td>

                                <td>
                                    @if (optional($enrol->certificate_history)->issued_by)
                                    {{optional($enrol->certificate_history)->issued_by}}
                                    @else
                                    NO RECORD FOUND
                                    @endif
                                </td>

                                <td>
                                    @if (optional($enrol->certificate_history)->approved_by)
                                    {{optional($enrol->certificate_history)->approved_by}}
                                    @else
                                    NO RECORD FOUND
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
                                    @if (optional($enrol->certificate_history)->is_approve == 2)
                                    <span class="badge bg-danger">INVALID</span>
                                    @else
                                    <span class="badge bg-warning">NEED APPROVAL</span>
                                    @endif
                                    @else
                                    NO RECORD FOUND
                                    @endif
                                </td>

                                <td>
                                    @if(optional($enrol->certificate_history)->invalid_comment)
                                    <span class="text-danger">{{$enrol->certificate_history->invalid_comment}}</span>
                                    @else
                                    <div class="mb-3 mt-3">
                                        <textarea class="form-control" id="textarea-input" rows="3" wire:model.defer="comments.{{$enrol->certificate_history->certificatehistoryid}}"></textarea>
                                    </div>
                                    @endif
                                </td>

                                <td>
                                    <div class="mb-3 mt-3">
                                        <textarea class="form-control" id="textarea-input" rows="3" wire:model.defer="adjustment.{{$enrol->certificate_history->certificatehistoryid}}"></textarea>
                                    </div>
                                </td>

                                <td class="text-center">
                                    @if (optional($enrol->certificate_history)->certificatehistoryid)
                                    @can('authorizeAdminComponents', 112)
                                    <button class="btn btn-sm btn-info" wire:click="approval_cert({{$enrol->certificate_history->certificatehistoryid}})">FOR APPROVAL</button>
                                    @endcan
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
        </div>
    </div>

</div>