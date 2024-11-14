<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-6">
                            <h3>Search Billing</h3>
                        </div>
                        <div wire:loading class="col-lg-6" style="height: 2em;">
                            <span style="position: relative;" class="float-end me-4">
                                <livewire:components.loading-screen-v2 />
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="input-groups">
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1">
                                        <i class="h3 m-1 bi bi-search"></i>
                                    </span>
                                    <input type="text" class="form-control" wire:model="searchBar"
                                        placeholder="Search by serial number, company, coursename.."
                                        aria-label="Username" aria-describedby="basic-addon1">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="input-group mb-3 h-75">
                                <span class="input-group-text" id="basic-addon2">
                                    Select Board
                                </span>
                                <select name="" id="" wire:model="selectedBoards" class="form-select"
                                    aria-describedby="basic-addon2">
                                    <option value="">Select Board</option>
                                    @foreach ($billingBoards as $item)
                                    <option value="{{$item->id}}">{{$item->billingstatus}}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Training Week</th>
                                <th>Scheduleid</th>
                                <th>Date Billed</th>
                                <th style="max-width: 15em; min-width: 15em;">Serial Number</th>
                                <th>Company</th>
                                <th>Course</th>
                                <th>Status</th>
                                <th>Last Modified By</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($billinglist as $item)
                            <tr>
                                <td>{{ $item['batchno'] }}</td>
                                <td>{{ implode(',',$item['scheduleid']) }}</td>
                                @php
                                $sn_no = $this->getSerialNumber($item['scheduleid'], $item['companyid'])
                                @endphp
                                <td>
                                    {{ $item['datebilled'] == '0000-00-00' || $item['datebilled'] == NULL ? 'Not
                                    Specified'
                                    : date('F d, Y',
                                    strtotime($item['datebilled'])) }}
                                </td>
                                <td style="max-width: 15em; min-width: 15em;">
                                    @foreach ($sn_no as $sn)
                                    {{$sn}}
                                    @endforeach
                                </td>
                                <td>{{ $item['company'] }}</td>
                                <td>{{ $item['coursecode'] }} / {{ $item['coursename'] }}</td>
                                <td>
                                    @php
                                    switch ($item['billingstatusid']) {
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
                                    case 10:
                                    echo 'Transaction Closed';
                                    break;

                                    default:
                                    echo 'For Billing';
                                    break;
                                    }
                                    @endphp
                                </td>
                                <td>{{ $item['billing_modified_by'] }}</td>
                                <td>
                                    <button class="btn btn-info"
                                        wire:click="passSessionData({{ json_encode($item['scheduleid']) }}, {{$item['companyid']}}, {{ $item['billingstatusid'] }})">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $billinglist->links('livewire.components.customized-pagination-link') }}
                </div>
            </div>
        </div>
    </div>
</div>