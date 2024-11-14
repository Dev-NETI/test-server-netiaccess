<div class="col-lg-12 col-md-12 col-12 mt-3">
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
        <div class="col-6">
            <h3>Certificate Approval</h3>
        </div>
        <div class="col-6">
            @can('authorizeAdminComponents', 111)
            <button class="btn btn-info float-end mb-3" wire:click.prevent="performAction">APPROVE SELECTED</button>
            @endcan
        </div>
    </div>
    <div class="tab-content">
        <div class="tab-pane fade active show" id="tabPaneList" role="tabpanel" aria-labelledby="tabPaneList">
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-sm text-nowrap mb-0 table-centered">
                        <thead class="table-light">
                            <tr>
                                <th scope="col"> SELECT </th>
                                <th scope="col">#</th>
                                <th scope="col"></th>
                                <th scope="col">Name</th>
                                <th scope="col">Birthday</th>
                                <th scope="col">Certificate Number</th>
                                <th scope="col">Registration Number</th>
                                <th scope="col">CLN</th>
                                <th scope="col">Control Number</th>
                                <th scope="col">Issued By</th>
                                <th scope="col">Remarks</th>
                                <th scope="col">Status</th>
                                <th scope="col">Reason <i class="text-danger">(Applicable for Invalid Cert)</i></th>
                                <th scope="col" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($enrolled as $enrol)
                            <tr wire:key="enrol-{{ $enrol->enroledid }}">
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input ms-2" type="checkbox" wire:model.defer="selectedItems" value="{{ $enrol->enroledid }}">
                                    </div>
                                </td>
                                <td>
                                    <span>
                                        <small>
                                            @if (optional($enrol->certificate_history)->certificatehistoryid)
                                            <i>#{{optional($enrol->certificate_history)->certificatehistoryid}} </i>
                                            @else
                                            N/A
                                            @endif
                                        </small>
                                    </span>
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
                                    @if(optional($enrol->certificate_history)->certificatenumber)
                                    @if($editingCertId && $editingCertId == optional($enrol->certificate_history)->certificatehistoryid)
                                    <input type="text" class="form-control form-control-sm text-dark" wire:model.defer="cert_num" placeholder="insert here.." wire:loading.remove>
                                    @else
                                    {{optional($enrol->certificate_history)->certificatenumber}}
                                    @endif
                                    @else
                                    <span class="text-muted">N/A</span>
                                    @endif
                                </td>

                                <td>
                                    @if(optional($enrol->certificate_history)->registrationnumber)
                                    @if($editingCertId && $editingCertId == optional($enrol->certificate_history)->certificatehistoryid)
                                    <input type="text" class="form-control form-control-sm text-dark" wire:model.defer="reg_num" placeholder="insert here.." wire:loading.remove>
                                    @else
                                    {{optional($enrol->certificate_history)->registrationnumber}}
                                    @endif
                                    @else
                                    <span class="text-muted">N/A</span>
                                    @endif
                                </td>

                                <td>
                                    @if (optional($enrol->certificate_history)->cln_type)
                                    {{optional($enrol->certificate_history)->cln_type}}
                                    @else
                                    <span class="text-muted">N/A</span>
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
                                    <span class="text-muted">N/A</span>
                                    @endif
                                </td>

                                <td>
                                    @if (optional($enrol->certificate_history)->issued_by)
                                    {{optional($enrol->certificate_history)->issued_by}}
                                    @else
                                    <span class="text-muted">N/A</span>
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
                                    <span class="text-muted">N/A</span>
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
                                    <span class="text-muted">N/A</span>
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

                                <td class="text-center">
                                    @if (optional($enrol->certificate_history)->certificatehistoryid)
                                    @if($editingCertId == optional($enrol->certificate_history)->certificatehistoryid)
                                    <button class="btn btn-sm btn-success" wire:click="save_edit_cert({{ $enrol->certificate_history->certificatehistoryid }})">UPDATE</button>
                                    @else
                                    <button class="btn btn-sm btn-warning" wire:click="edit_cert({{ $enrol->certificate_history->certificatehistoryid }})">EDIT #</button>
                                    <button class="btn btn-sm btn-warning" wire:click="edit_remarks({{ $enrol->enroledid }})" data-bs-toggle="modal" data-bs-target="#certificateModal">EDIT REMARKS</button>
                                    @endif
                                    @can('authorizeAdminComponents', 111)
                                    <button class=" btn btn-sm btn-info" wire:click="approved_cert({{$enrol->certificate_history->certificatehistoryid}})">APPROVED CERT.</button>
                                    @endcan
                                    <button class="btn btn-sm btn-danger" wire:click="dis_cert({{$enrol->certificate_history->certificatehistoryid}})" @if($enrol->certificate_history->invalid_comment) disabled @endif>INVALID CERT.</button>
                                    @else
                                    <span class="text-muted">No actions available</span>
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
    <div wire:ignore.self class="modal fade" id="certificateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Remarks</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="save_remarks" id="disapprove">
                        <div class="row gx-3">
                            <div class="col-md-12 col-12">
                                <div class="alert alert-info">
                                    Please enter your remarks below. These remarks will be displayed on the certificate.
                                </div>
                            </div>
                            <div class="col-md-12 col-12">
                                <label for="">Remarks:</label>
                                <textarea class="form-control" name="" id="" cols="30" rows="10" wire:model.defer="remarks"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="disapprove" class="btn btn-primary">Proceed</button>
                </div>
            </div>
        </div>
    </div>
</div>