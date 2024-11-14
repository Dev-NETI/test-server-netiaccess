<div class="container-fluid">
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
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($enrolled as $certificate_history)
                                <tr>
                                    <td>
                                        <span>
                                            <small>
                                                <i>#{{optional($certificate_history)->certificatehistoryid}} </i>
                                            </small>
                                        </span>
                                    </td>
                                    <td>
                                        @if (optional($certificate_history->trainee)->imagepath)
                                        <img src="{{asset('storage/traineepic/'. $certificate_history->trainee->imagepath)}}" alt="" height="200" class="avatar-xl me-2">
                                        @else
                                        <img src="{{asset('assets/images/avatar/noimageavailable.jpg')}}" alt="" height="200" class="avatar-xl me-2">
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            {{optional($certificate_history->trainee)->certificate_name()}}
                                        </div>
                                    </td>

                                    <td>
                                        {{optional($certificate_history)->certificatenumber}}
                                    </td>

                                    <td>
                                        {{optional($certificate_history)->registrationnumber}}
                                    </td>

                                    <td>
                                        {{optional($certificate_history)->cln_type}}
                                    </td>

                                    <td>
                                        @php
                                        $cert_history = optional($certificate_history);
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
                                        @if (optional($certificate_history)->issued_by)
                                        {{optional($certificate_history)->issued_by}}
                                        @else
                                        NO RECORD FOUND
                                        @endif
                                    </td>

                                    <td>
                                        @if (optional($certificate_history)->approved_by)
                                        {{optional($certificate_history)->approved_by}}
                                        @else
                                        NO RECORD FOUND
                                        @endif
                                    </td>

                                    <td>
                                        @if (optional($certificate_history)->registrationnumber)
                                        @if (optional($certificate_history)->is_approve == 1 && optional($certificate_history)->is_released == 1 )
                                        <span class="badge bg-success">RELEASED ({{$certificate_history->is_released_date}})</span>
                                        @elseif(optional($certificate_history)->is_approve == 1 && optional($certificate_history)->is_released == 0)
                                        <span class="badge bg-danger">NOT RELEASE</span>
                                        @else
                                        <span class="badge bg-warning">NEED APPROVAL</span>
                                        @endif
                                        @else
                                        NO RECORD FOUND
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
</div>