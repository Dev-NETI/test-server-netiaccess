<section class="container-fluid p-4">
    <span wire:loading>
        <livewire:components.loading-screen-component />
    </span>
    <div class="row">
        <!-- Page Header -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-flex justify-content-between align-items-center">
                <div class="mb-2 mb-lg-0">
                    <h1 class="mb-1 h2 fw-bold">
                        List of Instructor Rate
                    </h1>
                    <!-- Breadcrumb  -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('a.instructor') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">User</li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Instructor
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="row">

        <div wire:ignore.self class="modal fade" id="addeditrate" tabindex="-1" role="dialog" aria-labelledby="addeditrate" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="exampleModalScrollableTitle">EDIT RATE</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <form wire:submit.prevent="submit_rate">
                                @csrf
                                <div class="mt-2 col-lg-12">
                                    <label class="form-label" for="">Rank</label><br>
                                    <select class="col-lg-12 form-select" wire:model="selectedRank" required>
                                        <option value="">Select option</option>
                                        @foreach ($ranks_data as $rank)
                                        <option value="{{ $rank->rankid }}">{{ $rank->rank }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mt-2 col-lg-12">
                                    <label class="form-label" for="">Rate</label><br>
                                    <select class="col-lg-12 form-select" wire:model.defer="selectedRate" required>
                                        <option value="">Select option</option>
                                        @foreach ($rates as $rate)
                                        <option value="{{ $rate->id }}">{{ $rate->rate->rate_name }} - ₱{{number_format($rate->daily_rate,2)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mt-2 col-lg-12">
                                    <label class="form-label" for="">Tax Identification Number</label><br>
                                    <input type="text" class="form-control" wire:model.defer="tin" placeholder="Tax Identification Number">
                                </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" type="button" class="btn btn-info">Add</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-12 mb-5">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-5">
                            <h4 class="mb-1">List of Instructor Account</h4>
                        </div>
                        <div class="col-lg-3 text-end">
                            <label for="" class="form-label pt-2">Search:</label>
                        </div>
                        <div class="col-lg-4 float-end">
                            <input type="text" placeholder="search in name & rank .." wire:model.debounce.500ms="search" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="table-responsive text-center">
                    <table class="table table-sm text-nowrap  mb-0 table-centered table-dark" width="100%" height="100%">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Name</th>
                                <th>Rank</th>
                                <th>Rate</th>
                                <th>Tax Identification Number</th>
                            </tr>
                        </thead>
                        <tbody class="" style="font-size: 11px;">
                            @if ($i_accounts->count())
                            @foreach ($i_accounts as $i_account)
                            <tr class="mt-1 mb-2">
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Edit
                                        </button>
                                        <div class="dropdown-menu">
                                            <button data-bs-toggle="modal" data-bs-target="#addeditrate" wire:click="EditUser({{$i_account->instructorid}})" class="dropdown-item"><i class="bi bi-archive-fill"></i>&nbsp;Edit Rate</button>
                                        </div>
                                    </div>
                                </td>
                                @if ($i_account)
                                <td>{{ strtoupper($i_account->l_name)}}, {{ strtoupper($i_account->f_name) }} {{ strtoupper($i_account->m_name)}}</td>
                                @else
                                <td class="text-danger">Not Specified</td>
                                @endif
                                @if ($i_account->rank)
                                <td>{{strtoupper($i_account->rank)}}</td>
                                @else
                                <td class="text-danger">Not Specified</td>
                                @endif
                                @if ($i_account->rate)
                                <td class=""><b>{{strtoupper($i_account->rate->rate->rate_name)}} - ₱{{number_format($i_account->rate->daily_rate,2)}}</b></td>
                                @else
                                <td class="text-danger">Not Specified</td>
                                @endif
                                @if($i_account->tin)
                                <td class="">{{($i_account->tin)}}</td>
                                @else
                                <td class="text-danger">Not Specified</td>
                                @endif
                            </tr>
                            @endforeach
                            @else
                            <tr class="text-center">
                                <td colspan="6">-----No Records Found-----</td>
                            </tr>
                            @endif

                        </tbody>
                    </table>
                    <div class="card-footer">
                        <div class="row mt-5" style="padding-bottom: 6.5em;">
                            {{ $i_accounts->appends(['search' => $search])->links('livewire.components.customized-pagination-link')}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>