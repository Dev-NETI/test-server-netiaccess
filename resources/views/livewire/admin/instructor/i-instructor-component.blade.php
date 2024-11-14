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
                        List of Instructor
                        <span class="fs-5 text-muted">({{$instructoracc->count()}})</span>
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
        <!-- basic table -->
        <div class="col-md-12 col-12 mb-3">
            <div class="card">
                <!-- card header  -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 gap-2 text-center">
                            @if (Auth::user()->u_type == 3)
                            <button class="btn btn-primary mt-1 mb-1" data-bs-toggle="modal"
                                data-bs-target="#addinstructormodal"><i class="bi bi-person-fill-add"></i> Add
                                Instructor</button>
                            {{-- <a class="btn btn-danger mt-1 mb-1" href="{{ route('a.instructor-list') }}"
                                target="_blank"><i class="bi bi-filetype-pdf"></i>&nbsp;Y-BOD-012</a> --}}
                            <a class="btn btn-warning mt-1 mb-1" href="{{route('c.instructor-history')}}">Instructor
                                History</a>
                            <button class="btn btn-info mt-1 mb-1" data-bs-toggle="modal"
                                data-bs-target="#inactiveinstructor"> Inactive Instructor</button>
                            {{-- <button class="btn btn-danger mt-1 mb-1"><i
                                    class="bi bi-filetype-pdf"></i>&nbsp;Y-BOD-012</button> --}}
                            <a class="btn btn-primary mt-1 mb-1" href="{{ route('c.email-notifications') }}">&nbsp;Email
                                Notification</a>
                            {{-- <button class="btn btn-success mt-1 mb-1">License Summary</button>
                            <button class="btn btn-secondary mt-1 mb-1">Logs</button> --}}
                            <a href="{{route('c.generateinstructorattachmentsummary')}}" target="_blank"
                                class="btn btn-success mt-1 mb-1">Instructor Attachments</a>
                            @else
                            <button class="btn btn-primary mt-1 mb-1" data-bs-toggle="modal"
                                data-bs-target="#addinstructormodal"><i class="bi bi-person-fill-add"></i> Add
                                Instructor</button>
                            {{-- <a class="btn btn-danger mt-1 mb-1" href="{{ route('a.instructor-list') }}"
                                target="_blank"><i class="bi bi-filetype-pdf"></i>&nbsp;Y-BOD-012</a> --}}
                            <a class="btn btn-warning mt-1 mb-1" href="{{route('a.instructor-history')}}">Instructor
                                History</a>
                            <button class="btn btn-info mt-1 mb-1" data-bs-toggle="modal"
                                data-bs-target="#inactiveinstructor"> Inactive Instructor</button>
                            {{-- <button class="btn btn-danger mt-1 mb-1"><i
                                    class="bi bi-filetype-pdf"></i>&nbsp;Y-BOD-012</button> --}}
                            <a class="btn btn-primary mt-1 mb-1" href="{{ route('a.email-notifications') }}">&nbsp;Email
                                Notification</a>
                            {{-- <button class="btn btn-success mt-1 mb-1">License Summary</button>
                            <button class="btn btn-secondary mt-1 mb-1">Logs</button> --}}
                            <a href="{{route('a.generateinstructorattachmentsummary')}}" target="_blank"
                                class="btn btn-success mt-1 mb-1">Instructor Attachments</a>
                            <a href="#" wire:click="openModal" class="btn btn-warning mt-1 mb-1">Y-BOD-12</a>
                            @endif

                            <!-- Modal -->
                            <div wire:ignore.self class="modal fade" id="modalYBOD12" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalCenterTitle">Select Date</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close">
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <label for="" class="form-label">From</label>
                                                    <input wire:model="fromYBOD" type="text"
                                                        class="form-control flatpickr">
                                                    @error('fromYBOD')
                                                    <span class="text-danger">{{$message}}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-lg-12 mt-2">
                                                    <label for="" class="form-label">To</label>
                                                    <input wire:model="toYBOD" type="text"
                                                        class="form-control flatpickr">
                                                    @error('toYBOD')
                                                    <span class="text-danger">{{$message}}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" wire:click="exportYBOD"
                                                class="btn btn-primary">Export</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Add Instructor Modal --}}
        <!-- Modal -->
        <div wire:ignore.self class="modal fade" id="addinstructormodal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="exampleModalScrollableTitle">Add Instructor</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <form id="addinstructor" wire:submit.prevent="addinstructor">
                                @csrf
                                <label class="form-label" for="">Firstname</label>
                                <input required type="text" wire:model.defer="firstname" class="form-control">
                                <label class="form-label" for="">Middlename</label>
                                <input required type="text" wire:model.defer="middlename" class="form-control">
                                <label class="form-label" for="">Lastname</label>
                                <input required type="text" wire:model.defer="lastname" class="form-control">
                                <div class="col-lg-12 mt-2">
                                    <label class="form-label" for="">Email</label>
                                    <input required type="email" wire:model.defer="email" class="form-control"
                                        placeholder="example@mail.etc">
                                </div>
                                <div class="col-lg-12 mt-2">
                                    <label class="form-label" for="">Password <span class="text-danger"
                                            style="font-size: 10px;">(Default value: instructor)</span></label>
                                    <input required type="password" wire:model.defer="password"
                                        onmouseover="this.type='text'" onmouseout="this.type='password'"
                                        class="form-control">
                                </div>
                                <div class="mt-2 col-lg-12">
                                    <label class="form-label" for="">Rank</label><br>
                                    <select name="" wire:model.defer="rankid" id="" class="col-lg-12 form-select"
                                        required>
                                        <option value="">Select option</option>

                                        @foreach ($ranks as $rank)
                                        <option value="{{$rank->rankid}}">{{$rank->rank}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" form="addinstructor" type="button" class="btn btn-info">Add</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- End Add Instructor Modal --}}


        <div wire:ignore.self class="modal fade" id="inactiveinstructor" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="exampleModalScrollableTitle">Inactive Instructor</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <input type="text" class="form-control"
                                    wire:model.debounce.500ms.prevent="searchinactive"
                                    placeholder="Search name/rank...">
                            </div>
                            <div class="col-lg-12 pt-7">
                                <div class="table-card">
                                    <table class="table table-hover second" style="width:100%">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Action</th>
                                                <th>Name</th>
                                                <th>Rank</th>
                                                <th>Mobile No.</th>
                                                <th>Status</th>
                                                <th>Service Agreement Exp.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($inactiveins)
                                            @foreach ($inactiveins as $inactivein)
                                            <tr>
                                                <td>
                                                    @if ($inactivein->regularid == 1)
                                                    <button class="btn btn-sm btn-danger mt-1"
                                                        wire:click.prevent="removeregular({{$inactivein->instructorid}})">Remove
                                                        as regular</button>
                                                    @else
                                                    <button class="btn btn-sm btn-warning mt-1"
                                                        wire:click.prevent="tagregular({{$inactivein->instructorid}})">Tag
                                                        as regular</button>
                                                    @endif
                                                    <a class="btn btn-sm btn-info mt-1"
                                                        href="{{ route('a.edit-instructor', ['hashid' => $inactivein->user->hash_id]) }}">View</a>
                                                    <button class="btn btn-sm btn-success mt-1"
                                                        wire:click.prevent="activateins({{$inactivein->instructorid}})">Activate</button>
                                                </td>
                                                <td>{{$inactivein->user->l_name}}, {{$inactivein->user->f_name}}
                                                    {{$inactivein->user->m_name}}</td>
                                                <td>{{$inactivein->rank}}</td>
                                                <td>{{$inactivein->mobilenumber}}</td>
                                                <td>
                                                    @if ($inactivein->regularid == 1)
                                                    Regular
                                                    @else
                                                    Guest Faculty
                                                    @endif
                                                </td>
                                                <td>{{$inactivein->scato}}</td>
                                            </tr>
                                            @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {{ $inactiveins->appends(['search' =>
                        $searchinactive])->links('livewire.components.customized-pagination-link')}}
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                            <h4 class="mb-1">List of Instructor Account</h4>
                            <!-- <p class="mb-0">DataTables is a plug-in for the jQuery Javascript library. It is a
                        highly flexible tool, built upon the foundations of progressive enhancement, that
                        adds all of these advanced features to any HTML table.</p> -->
                        </div>
                        <div class="col-lg-3 text-end">
                            <label for="" class="form-label pt-2">Search:</label>
                        </div>
                        <div class="col-lg-4 float-end">
                            <input type="text" placeholder="search in name & rank .." wire:model.debounce.500ms="search"
                                class="form-control">
                        </div>
                    </div>
                </div>
                <div class="table-responsive text-center">
                    <table class="table table-sm text-nowrap table-hover mb-0 table-centered" width="100%"
                        height="100%">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Name</th>
                                <th>Rank</th>
                                <th>Contact No</th>
                                <th>Address</th>
                                <th>Date of Birth</th>
                                <th>Regular</th>
                            </tr>
                        </thead>
                        <tbody class="" style="font-size: 11px;">
                            @if ($i_accounts->count())
                            {{-- @foreach ($i_accounts as $i_account) --}}
                            @foreach ($i_accounts as $i_account)
                            <tr class="mt-1 mb-2">
                                <td>
                                    <div class="btn-group" role="group">
                                        <button id="btnGroupDrop{{$i_account->instructorid}}" type="button"
                                            class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            Edit
                                        </button>
                                        <div class="dropdown-menu"
                                            aria-labelledby="btnGroupDrop{{$i_account->instructorid}}">
                                            @if (Auth::user()->u_type == 3)
                                            <a class="dropdown-item"
                                                href="{{ route('c.editins', ['hashid' => $i_account->hash_id]) }}"><i
                                                    class="bi bi-pencil-square" style="font-size: 1em;"></i>&nbsp;Edit
                                                Instructor</a>
                                            @else
                                            <a class="dropdown-item"
                                                href="{{ route('a.edit-instructor', ['hashid' => $i_account->hash_id]) }}"><i
                                                    class="bi bi-pencil-square" style="font-size: 1em;"></i>&nbsp;Edit
                                                Instructor</a>
                                            @endif
                                            @if ($i_account->regularid != 1)
                                            <a class="dropdown-item" href="#"
                                                wire:click="tagregular({{$i_account->instructorid}})"><i
                                                    class="bi bi-bookmark-plus-fill"></i>&nbsp;Tag as Regular</a>
                                            @else
                                            <a class="dropdown-item" href="#"
                                                wire:click="removeregular({{$i_account->instructorid}})"><i
                                                    class="bi bi-bookmark-dash-fill"></i>&nbsp;Remove as Regular</a>
                                            @endif
                                            <a class="dropdown-item" href="#"
                                                wire:click="deactivate('{{$i_account->instructorid}}', '{{$i_account->f_name}} {{$i_account->l_name}}')"><i
                                                    class="bi bi-dash-circle-fill me-2"></i>&nbsp;Deactivate</a>
                                            <a class="dropdown-item"
                                                href="{{ route('a.view-archives', ['hashid' => $i_account->hash_id]) }}"><i
                                                    class="bi bi-archive-fill"></i>&nbsp;View Archives</a>
                                        </div>
                                    </div>
                                </td>
                                @if ($i_account)
                                <td>{{ strtoupper($i_account->l_name)}}, {{ strtoupper($i_account->f_name) }} {{
                                    strtoupper($i_account->m_name)}}</td>
                                @else
                                <td class="text-danger">Not Specified</td>
                                @endif
                                @if ($i_account->rank)
                                <td>{{strtoupper($i_account->rank)}}</td>
                                @else
                                <td class="text-danger">Not Specified</td>
                                @endif
                                @if ($i_account->mobilenumber)
                                <td>{{strtoupper($i_account->mobilenumber)}}</td>
                                @else
                                <td class="text-danger">Not Specified</td>
                                @endif
                                @if ($i_account->address)
                                <td>{{strtoupper($i_account->address)}}</td>
                                @else
                                <td class="text-danger">Not Specified</td>
                                @endif
                                @if ($i_account->user->birthday)
                                <td>{{ \Carbon\Carbon::parse($i_account->user->birthday)->format('F j, Y') }}</td>
                                @else
                                <td class="text-danger">Not Specified</td>
                                @endif
                                @if ($i_account->regularid == 1)
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
                            {{ $i_accounts->appends(['search' =>
                            $search])->links('livewire.components.customized-pagination-link')}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>