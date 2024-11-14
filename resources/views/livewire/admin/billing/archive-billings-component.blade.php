<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Archives Billing</h4>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Bill #</th>
                                <th>Client</th>
                                <th>Course</th>
                                <th>Status</th>
                                <th>Archived By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($billings as $item)
                            <tr>
                                <td>{{ $item->batchno }}</td>
                                @php
                                $sn_no = $this->getSerialNumber($item->scheduleid, $item->companyid)
                                @endphp
                                {{-- <td style="max-width: 15em; min-width: 15em;">
                                    @foreach ($sn_no as $sn)
                                    {{$sn}}
                                    @endforeach
                                </td> --}}
                                <td>{{ $item->company }}</td>
                                <td>{{ $item->coursecode }} / {{ $item->coursename }}</td>
                                <td>
                                    @php
                                    switch ($item->billingstatusid) {
                                    case 2:
                                    echo 'Billing Statement Review Board';
                                    break;
                                    case 3:
                                    echo 'BOD Manager Review Board';
                                    break;
                                    case 4:
                                    echo 'GM Review Board';
                                    break;
                                    case 5:
                                    echo 'BOD Manager Dispatch Board';
                                    break;
                                    case 6:
                                    echo 'Client Confirmation Board';
                                    break;
                                    case 7:
                                    echo 'Proof of Payment Upload Board';
                                    break;
                                    case 8:
                                    echo 'Official Receipt Issuance Board';
                                    break;
                                    case 9:
                                    echo 'Official Receipt Confirmation Board';
                                    break;
                                    case 9:
                                    echo 'Transaction Closed';
                                    break;

                                    default:
                                    echo 'For Billing';
                                    break;
                                    }
                                    @endphp
                                </td>
                                <td>{{ $item->billing_modified_by }}</td>
                                <td>
                                    <button class="btn btn-success"
                                        wire:click="unArchived({{$item->scheduleid}}, {{$item->companyid}})">
                                        <i class="bi bi-box-arrow-in-up-right"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $billings->links('livewire.components.customized-pagination-link') }}
                </div>
            </div>
        </div>
    </div>
</div>