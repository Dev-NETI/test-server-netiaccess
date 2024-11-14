<section class="container-fluid p-4">

    <div class="row">
        <!-- Page Header -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-flex justify-content-between align-items-center">
                <div class="mb-2 mb-lg-0">
                    <h1 class="mb-1 h2 fw-bold">
                        List of Trainees
                        <span class="fs-5 text-muted">({{$c_trainees}})</span>
                    </h1>
                    <!-- Breadcrumb  -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="../dashboard/admin-dashboard.html">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">User</a></li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Trainees
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- basic table -->
        <div class="col-md-12 col-12 mb-3">
            <div class="card">
                <!-- card header  -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 gap-2 text-center">
                            <button class="btn btn-primary mt-1 mb-1" data-bs-toggle="modal" data-bs-target="#addinstructormodal"><i class="bi bi-person-fill-add"></i> Add Instructor</button>
                            <button class="btn btn-danger mt-1 mb-1"><i class="bi bi-filetype-pdf"></i>&nbsp;Y-BOD-012</button>
                            <a class="btn btn-warning mt-1 mb-1" href="{{route('a.instructor-history')}}">Instructor History</a>
                            <button class="btn btn-info mt-1 mb-1" data-bs-toggle="modal" data-bs-target="#inactiveinstructor"> Inactive Instructor</button>
                            <button class="btn btn-danger mt-1 mb-1"><i class="bi bi-filetype-pdf"></i>&nbsp;Y-BOD-012</button>
                            <button class="btn btn-success mt-1 mb-1">License Summary</button>
                            <button class="btn btn-secondary mt-1 mb-1">Logs</button>
                            <button class="btn btn-warning mt-1 mb-1">Instructor Attachments</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- basic table -->
        <div class="col-md-12 col-12 mb-5">
            <div class="card">
                <!-- card header  -->
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-5">
                            <h4 class="mb-1">List of Trainees Account</h4>
                            <!-- <p class="mb-0">DataTables is a plug-in for the jQuery Javascript library. It is a
                        highly flexible tool, built upon the foundations of progressive enhancement, that
                        adds all of these advanced features to any HTML table.</p> -->
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
                    <table class="table table-sm text-nowrap table-hover mb-0 table-centered" width="100%" height="100%">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Name</th>
                                <th>Rank</th>
                                <th>Contact No</th>
                                <th>Address</th>
                                <th>Date of Birth</th>
                            </tr>
                        </thead>
                        <tbody class="" style="font-size: 11px;">
                            @if ($t_accounts->count())
                            @foreach ($t_accounts as $t_account)
                            <tr class="mt-1 mb-2">
                                <td>
                                    <div class="btn-group" role="group">
                                        <button id="" type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Edit
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="">
                                            <a class="dropdown-item" href=""><i class="bi bi-pencil-square" style="font-size: 1em;"></i>&nbsp;Edit Instructor</a>
                                            @if ($t_account->regularid != 1)
                                            <a class="dropdown-item" href="#" wire:click=""><i class="bi bi-bookmark-plus-fill"></i>&nbsp;Tag as Regular</a>
                                            @else
                                            <a class="dropdown-item" href="#" wire:click=""><i class="bi bi-bookmark-dash-fill"></i>&nbsp;Remove as Regular</a>
                                            @endif
                                            <a class="dropdown-item" href="#" wire:click=""><i class="bi bi-person-fill-slash"></i>&nbsp;Deactivate</a>
                                            <a class="dropdown-item" href=""><i class="bi bi-archive-fill"></i>&nbsp;View Archives</a>
                                        </div>
                                    </div>
                                </td>
                                @if ($t_account)
                                <td>{{ strtoupper($t_account->l_name)}}, {{ strtoupper($t_account->f_name) }} {{ strtoupper($t_account->m_name)}}</td>
                                @else
                                <td class="text-danger">Not Specified</td>
                                @endif
                                @if ($t_account->rank)
                                <td>{{strtoupper($t_account->rank)}}</td>
                                @else
                                <td class="text-danger">Not Specified</td>
                                @endif
                                @if ($t_account->mobilenumber)
                                <td>{{strtoupper($t_account->mobilenumber)}}</td>
                                @else
                                <td class="text-danger">Not Specified</td>
                                @endif
                                @if ($t_account->address)
                                <td>{{strtoupper($t_account->address)}}</td>
                                @else
                                <td class="text-danger">Not Specified</td>
                                @endif
                                @if ($t_account->birthday)
                                <td>{{ \Carbon\Carbon::parse($t_account->birthday)->format('F j, Y') }}</td>
                                @else
                                <td class="text-danger">Not Specified</td>
                                @endif
                                @if ($t_account->regularid == 1)
                                <td class="text-success text-center"><i class="bi bi-check-circle-fill"></i></td>
                                @else
                                <td class="text-default text-center"><i class="bi bi-x-circle"></i></td>
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
                            {{ $t_accounts->appends(['search' => $search])->links('livewire.components.customized-pagination-link')}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>