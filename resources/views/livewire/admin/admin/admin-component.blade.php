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
                        Manage Account
                        <span class="fs-5 text-muted">({{ count($a_accounts) }})</span>
                    </h1>
                    <!-- Breadcrumb  -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="../dashboard/admin-dashboard.html">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">User</a></li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Admin
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-12 mb-3 float-end">
            <div class="col-lg-12 gap-2">
                <button class="btn btn-primary mt-1 mb-1" data-bs-toggle="modal" data-bs-target="#addadminmodal"><i class="bi bi-person-fill-add"></i> Add Administrator</button>
            </div>
        </div>
    </div>

    <div class="col-md-12 col-12 mb-5">
        <div class="card">
            <!-- card header  -->
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-5">
                        <h4 class="mb-1">List of Administrator</h4>
                    </div>
                    <div class="col-lg-3 text-end">
                        <label for="" class="form-label pt-2">Search:</label>
                    </div>
                    <div class="col-lg-4 float-end">
                        <input type="text" placeholder="search in name .." wire:model.debounce.3000ms="search" class="form-control">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive text-center">
                    <table class="table table-sm text-nowrap table-hover mb-0 table-centered" width="100%" height="100%">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Contact No</th>
                                <th>User type</th>
                                <th>Department</th>
                            </tr>
                        </thead>
                        <tbody class="" style="font-size: 11px;">
                            @if ($a_accounts->count())
                            @foreach ($a_accounts as $a_account)
                            <tr class="mt-1 mb-2">
                                <td>
                                    <div class="btn-group" role="group">
                                        <button id="" type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Edit
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="">
                                            <button wire:click="edit({{$a_account->id}})" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editadminmodal"><i class="bi bi-person-check"></i>&nbsp;Edit Profile</button>
                                            <button wire:click="assignRoles({{$a_account->user_id}})" class="dropdown-item" ><i class="bi bi-check2-circle"></i>&nbsp;Edit Roles</button>
                                        </div>
                                    </div>
                                </td>
                                @if ($a_account)
                                <td>{{ strtoupper($a_account->formal_name())}}</td>
                                @else
                                <td class="text-danger">Not Specified</td>
                                @endif
                                @if ($a_account->email)
                                <td>{{$a_account->email}}</td>
                                @else
                                <td class="text-danger">Not Specified</td>
                                @endif
                                @if ($a_account->contact_num)
                                <td>{{strtoupper($a_account->contact_num)}}</td>
                                @else
                                <td class="text-danger">Not Specified</td>
                                @endif
                                @if ($a_account->u_type == 1)
                                <td>ADMINISTRATOR</td>
                                @elseif ($a_account->u_type == 2)
                                <td>INSTRUCTOR</td>
                                @elseif ($a_account->u_type == 3)
                                @if ($a_account->company)
                                    <td>COMPANY ({{$a_account->company->company}})</td>
                                @endif
                                @elseif ($a_account->u_type == 4)
                                <td>Technical</td>
                                @else
                                <td class="text-danger">Not Specified</td>
                                @endif

                                @if ($a_account->dep_type == 1)
                                <td>NOC</td>
                                @elseif ($a_account->dep_type == 2)
                                <td>PRPD</td>
                                @elseif ($a_account->dep_type == 3)
                                <td>BOD</td>
                                @elseif ($a_account->dep_type == 4)
                                <td>DORM</td>
                                @elseif ($a_account->dep_type == 5)
                                <td>QAD</td>
                                @elseif ($a_account->dep_type == 6)
                                <td>FINANCE</td>
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
                            {{ $a_accounts->appends(['search' => $search])->links('livewire.components.customized-pagination-link')}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="addadminmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalScrollableTitle">Add Administrator</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form id="addadmin" wire:submit.prevent="addadmin">
                            @csrf
                            <div class="col-md-12">
                                <label class="form-label" for="">Firstname</label>
                                <input required type="text" wire:model.defer="firstname" class="form-control">
                                @error('firstname')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="">Middlename</label>
                                <input type="text" wire:model.defer="middlename" class="form-control">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="">Lastname</label>
                                <input required type="text" wire:model.defer="lastname" class="form-control">
                                @error('lastname')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="">Suffix</label>
                                <input required type="text" wire:model.defer="suffix" class="form-control">
                                @error('suffix')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="">Contact Number</label>
                                <input required type="text" wire:model.defer="contact_num" class="form-control">
                                @error('contact_num')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="">User account type</label>
                                <select class="form-select" id="" wire:model="u_type">
                                    <option value="0">Not Specified</option>
                                    <option value="1">Administrator</option>
                                    <option value="3">Company</option>
                                    <option value="4">Technical</option>
                                    <option value="5">Non-Technical</option>
                                </select>
                                @error('u_type')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="">Department</label>
                                <select class="form-select" id="" wire:model.defer="dep_type">
                                    <option value="1">Networking Operation Center</option>
                                    <option value="2">Planning Research & Program Department </option>
                                    <option value="3">Business Operation Department</option>
                                    <option value="4">Dormitory</option>
                                    <option value="5">Quality Assurance Department</option>
                                    <option value="6">Finance Department</option>
                                    <option value="7">Registrar</option>
                                    <option value="8">Billing</option>
                                </select>
                                @error('u_type')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            @if ($u_type == 3)
                            <div class="col-md-12">
                                <label class="form-label" for="">Assign Company</label>
                                <select class="form-select" id="" wire:model.defer="company_id">
                                    @foreach ($all_company as $company)
                                    <option value="{{$company->companyid}}">{{$company->company}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <div class="col-lg-12 mt-2">
                                <label class="form-label" for="">Email</label>
                                <input required type="email" wire:model.defer="email" class="form-control" placeholder="example@mail.etc">
                                @error('email')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="col-lg-12 mt-2">
                                <label class="form-label" for="">Password <span class="text-danger" style="font-size: 10px;">(Click generate password)</span></label>
                                <div class="input-group">
                                            <input required type="password" wire:model.defer="password" onmouseover="this.type='text'" onmouseout="this.type='password'" class="form-control" readonly>
                                            @error('password')
                                            <small class="text-danger">{{$message}}</small>
                                            @enderror

                                            <div class="input-group-append">
                                                    <button class="btn btn-primary" type="button" wire:click="generatePassword()">Generate Password</button>
                                            </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="addadmin" type="button" class="btn btn-info">Add</button>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="editadminmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalScrollableTitle">Edit Administrator</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form id="update" wire:submit.prevent="update">
                            @csrf
                            <div class="col-md-12">
                                <label class="form-label" for="">Firstname</label>
                                <input required type="text" wire:model.defer="firstname" class="form-control">
                                @error('firstname')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="">Middlename</label>
                                <input type="text" wire:model.defer="middlename" class="form-control">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="">Lastname</label>
                                <input required type="text" wire:model.defer="lastname" class="form-control">
                                @error('lastname')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="">Suffix</label>
                                <input  type="text" wire:model.defer="suffix" class="form-control">
                                @error('suffix')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="">Contact Number</label>
                                <input required type="text" wire:model.defer="contact_num" class="form-control">
                                @error('contact_num')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="">User account type</label>
                                <select class="form-select" id="" wire:model.defer="u_type">
                                    <option value="0">Not Specified</option>
                                    <option value="1">Administrator</option>
                                <option value="2">Instructor</option>
                                    <option value="3">Company</option>
                                    <option value="4">Technical</option>
                                    <option value="5">Non-Techical</option>
                                </select>
                                @error('u_type')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="">Department</label>
                                <select class="form-select" id="" wire:model.defer="dep_type">
                                    <option value="0">Not Specified</option>
                                    <option value="1">Networking Operation Center</option>
                                    <option value="2">Planning Research & Program Department </option>
                                    <option value="3">Business Operation Department</option>
                                    <option value="4">Dormitory</option>
                                    <option value="5">Quality Assurance Department</option>
                                    <option value="6">Finance Department</option>
                                    <option value="7">Registrar</option>
                                    <option value="8">Billing</option>
                                </select>
                                @error('u_type')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            @if ($u_type == 3)
                            <div class="col-md-12">
                                <label class="form-label" for="">Assign Company</label>
                                <select class="form-select" id="" wire:model.defer="company_id">
                                    @foreach ($all_company as $company)
                                    <option value="{{$company->companyid}}">{{$company->company}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <div class="col-lg-12 mt-2">
                                <label class="form-label" for="">Email</label>
                                <input required type="email" wire:model.defer="email" class="form-control" placeholder="example@mail.etc">
                                @error('email')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="col-lg-12 mt-2">
                                <label class="form-label" for="">Password <span class="text-danger" style="font-size: 10px;">(Default value: administrator)</span></label>
                                <div class="input-group">
                                    <input required type="password" wire:model.defer="password" onmouseover="this.type='text'" onmouseout="this.type='password'" class="form-control">
                                    @error('password')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-primary" wire:click="generatePassword()">Generate Password</button>
                                    </div>
                                </div>
                                
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="update" type="button" class="btn btn-info">Add</button>
                </div>
            </div>
        </div>
    </div>
</section>