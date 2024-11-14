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
                        <span class="fs-5 text-muted">( {{ $a_accounts->total() }} )</span>
                    </h1>
                    <!-- Breadcrumb  -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="#">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">User</a></li>
                            <li class="breadcrumb-item active" aria-current="page">
                                All acounts
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
                <button class="btn btn-primary mt-1 mb-1" data-bs-toggle="modal" data-bs-target="#addadminmodal"><i
                        class="bi bi-person-fill-add"></i> Add User</button>
            </div>
        </div>
    </div>

    <div class="col-md-12 col-12 mb-5">
        <div class="card">
            <!-- card header  -->
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-5">
                        <h4 class="mb-1">List of Users, Instructor</h4>
                    </div>
                    <div class="col-lg-3 text-end">
                        <label for="" class="form-label pt-2">Search:</label>
                    </div>
                    <div class="col-lg-4 float-end">
                        <div class="input-group">
                            <input type="text" wire:model.debounce.500ms="search"
                                placeholder="search in name, company, email .." class="form-control">
                            <div class="input-group-append">
                                <select class="form-control" wire:model="u_type_filter">
                                    <option value="0">All</option>
                                    <option value="1">Admin</option>
                                    <option value="2">Instructor</option>
                                    <option value="3">Company</option>
                                    <option value="4">Technical</option>
                                    <option value="5">Non-technical</option>
                                </select>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">           
                <div class="table-responsive text-center">
                    <table class="table table-sm text-nowrap table-hover mb-0 table-centered" width="100%"
                        height="100%">
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
                                    <tr class="mt-1 mb-2 @if ($a_account->is_active == 0) bg-light-danger @endif">
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button id="" type="button"
                                                    class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    Edit
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="">
                                                    @if ($a_account->u_type == 2)
                                                        <button class="dropdown-item" wire:click="setApprover({{ $a_account->user_id }})">
                                                            <i class="bi bi-person"></i>&nbsp;Add Overtime Approver 
                                                        </button>
                                                    @endif
                                                    <button wire:click="edit({{ $a_account->id }})"
                                                        class="dropdown-item" data-bs-toggle="modal"
                                                        data-bs-target="#editadminmodal"><i
                                                            class="bi bi-person-check"></i>&nbsp;Edit Profile</button>
                                                    <button wire:click="assignRoles({{ $a_account->user_id }})"
                                                        class="dropdown-item"><i
                                                            class="bi bi-check2-circle"></i>&nbsp;Edit Roles</button>

                                                    @if ($a_account->is_active == 1 || $a_account->is_active == '')
                                                        <button wire:click="inactive({{ $a_account->id }})"
                                                            class="dropdown-item"><i
                                                                class="bi bi-lock"></i></i>&nbsp;Deactivate</button>
                                                    @else
                                                        <button wire:click="isactive({{ $a_account->id }})"
                                                            class="dropdown-item"><i
                                                                class="bi bi-unlock"></i>&nbsp;Activate</button>
                                                    @endif
                                                    
                                                    {{-- <button data-bs-toggle="modal" data-bs-target="#exampleModalCenter"
                                                        class="dropdown-item" ><i
                                                            class="bi bi-qr-code"></i>&nbsp;Qr Code 2 </button> --}}

                                                    <button wire:click="setUserId({{ $a_account->user_id }})" data-bs-toggle="modal" data-bs-target="#qrcodeModal" class="dropdown-item">
                                                        <i class="bi bi-qr-code"></i>&nbsp;Qr Code 
                                                    </button>
                                                    </div>
                                            </div>
                                        </td>
                                        @if ($a_account)
                                            <td>{{ strtoupper($a_account->formal_name()) }}</td>
                                        @else
                                            <td class="text-danger">Not Specified</td>
                                        @endif
                                        @if ($a_account->email)
                                            <td>{{ $a_account->email }}</td>
                                        @else
                                            <td class="text-danger">Not Specified</td>
                                        @endif
                                        @if ($a_account->contact_num)
                                            <td>{{ strtoupper($a_account->dialing_code->dialing_code . $a_account->contact_num) }}
                                            </td>
                                        @else
                                            <td class="text-danger">Not Specified</td>
                                        @endif
                                        @if ($a_account->u_type == 1)
                                            <td>ADMINISTRATOR</td>
                                        @elseif ($a_account->u_type == 2)
                                            <td>INSTRUCTOR</td>
                                        @elseif ($a_account->u_type == 3)
                                            @if ($a_account->company)
                                                <td>COMPANY ({{ $a_account->company->company }})</td>
                                            @endif
                                        @elseif ($a_account->u_type == 4)
                                            <td>Technical</td>
                                        @elseif ($a_account->u_type == 5)
                                            <td>NON-TECHNICAL</td>
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
                            {{ $a_accounts->links('livewire.components.customized-pagination-link') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="qrcodeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">QR Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        {{-- @dd($userIdlabas); --}}
                        {{-- <img src="data:image/png;base64,{{ base64_encode($userIdlabas) }}" alt="QR Code"> --}}
                        {{-- <img src="data:image/png;base64, {{ base64_encode(QrCode::generate('Sample QRCODE')) }}">    --}}
                        {{-- @if ($userIdlabas)
                            {{ QrCode::generate(''.$userIdlabas.'') }}
                        @endif --}}
                    @if ($userIdlabas)
                    {{ QrCode::size(300)->generate($userIdlabas) }}
                    @endif
                    </div>
                </div>
                <div class="modal-footer">
                    {{-- <button type="button" class="btn btn-primary" wire:click="downloadQRCode" >Download</button> --}}
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    
    <div wire:ignore.self class="modal fade" id="addadminmodal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalScrollableTitle">Add User</h3>
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
                                    <small class="text-danger">{{ $message }}</small>
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
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="">Suffix</label>
                                <input type="text" wire:model.defer="suffix" class="form-control">
                                @error('suffix')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="">Contact Number</label>
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <select wire:model="d_code" class="form-control">
                                            <option value="">Select</option>
                                            @foreach ($dialing_code_data as $data)
                                                <option value="{{ $data->id }}">
                                                    {{ $data->country_code }}(+{{ $data->dialing_code }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input required type="text" wire:model.defer="contact_num"
                                        class="form-control">
                                </div>
                                @error('d_code')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                                @error('contact_num')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="">User account type</label>
                                <select class="form-select" id="" wire:model="u_type">
                                    <option value="0">Not Specified</option>
                                    <option value="1">Administrator</option>
                                    <option value="2">Instructor</option>
                                    <option value="3">Company</option>
                                    <option value="4">Technical</option>
                                    <option value="5">Non-Technical</option>
                                    <option value="6">JISS Company</option>
                                </select>
                                @error('u_type')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            @if ($u_type != 3 && $u_type != 6)
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
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            @endif
                            @if ($u_type == 3)
                                <div class="col-md-12">
                                    <label class="form-label" for="">Assign Company</label>
                                    <select class="form-select" id="" wire:model.defer="company_id">
                                        @foreach ($all_company as $company)
                                            <option value="{{ $company->companyid }}">{{ $company->company }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            @if ($u_type == 6)
                                <div class="col-md-12">
                                    <label class="form-label" for="">Assign Company</label>
                                    <select class="form-select" id="" wire:model.defer="jisscompany_id">
                                        <option value="0" selected>(select option)</option>
                                        @foreach ($all_company as $company)
                                            <option value="{{ $company->companyid }}">{{ $company->company }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <div class="col-lg-12 mt-2">
                                <label class="form-label" for="">Email</label>
                                <input required type="email" wire:model.defer="email" class="form-control"
                                    placeholder="example@mail.etc">
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-lg-12 mt-2">
                                <label class="form-label" for="">Password <span class="text-danger"
                                        style="font-size: 10px;">(Click generate password button)</span></label>
                                <div class="input-group">
                                    <input required type="password" wire:model.defer="password"
                                        onmouseover="this.type='text'" onmouseout="this.type='password'"
                                        class="form-control" readonly>
                                    @error('password')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror

                                    <div class="input-group-append">
                                        <button class="btn btn-primary" wire:click="generatePassword()"
                                            type="button">Generate Password</button>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="">Route</label>
                                <input required type="text" wire:model.defer="route" class="form-control">
                                @error('route')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
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

    <div wire:ignore.self class="modal fade" id="editadminmodal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalScrollableTitle">Edit User</h3>
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
                                    <small class="text-danger">{{ $message }}</small>
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
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="">Suffix</label>
                                <input type="text" wire:model.defer="suffix" class="form-control">
                                @error('suffix')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="">Contact Number</label>
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <select wire:model="d_code_edit" class="form-control">
                                            @foreach ($dialing_code_data as $data)
                                                <option value="{{ $data->id }}">
                                                    {{ $data->country_code }}(+{{ $data->dialing_code }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input required type="text" wire:model.defer="contact_num"
                                        class="form-control">
                                </div>
                                @error('contact_num')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="">User account type</label>
                                <select class="form-select" id="" wire:model="u_type">
                                    <option value="0">Not Specified</option>
                                    <option value="1">Administrator</option>
                                    <option value="2">Instructor</option>
                                    <option value="3">Company</option>
                                    <option value="4">Technical</option>
                                    <option value="5">Non-Technical</option>
                                    <option value="6">JISS Company</option>
                                </select>
                                @error('u_type')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            @if ($u_type != 3 && $u_type != 6)
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
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            @endif
                            @if ($u_type == 3)
                                <div class="col-md-12">
                                    <label class="form-label" for="">Assign Company</label>
                                    <select class="form-select" id="" wire:model.defer="company_id">
                                        @foreach ($all_company as $company)
                                            <option value="{{ $company->companyid }}">{{ $company->company }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            @if ($u_type == 6)
                                <div class="col-md-12">
                                    <label class="form-label" for="">Assign Company</label>
                                    <select class="form-select" id="" wire:model.defer="jisscompany_id">
                                        <option value="0" selected>(select option)</option>
                                        @foreach ($all_company as $company)
                                            <option value="{{ $company->id }}">{{ $company->company }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <div class="col-lg-12 mt-2">
                                <label class="form-label" for="">Email</label>
                                <input required type="email" wire:model.defer="email" class="form-control"
                                    placeholder="example@mail.etc">
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-lg-12 mt-2">
                                <label class="form-label" for="">Password <span class="text-danger"
                                        style="font-size: 10px;">(Click generate password button)</span></label>
                                <div class="input-group">
                                    <input required type="password" wire:model.defer="password"
                                        onmouseover="this.type='text'" onmouseout="this.type='password'"
                                        class="form-control">
                                    @error('password')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror

                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-primary"
                                            wire:click="generatePassword()">Generate Password</button>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="">Route</label>
                                <input required type="text" wire:model.defer="route" class="form-control">
                                @error('route')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
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

    <div wire:ignore.self class="modal fade" id="selectOTApprover" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalCenterTitle">Select Approver:</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
              </button>
            </div>
            <div class="modal-body">
              <select name="" id="" wire:model="approverId" class="form-select">
                <option selected value="">Select Approver</option>
                @foreach ($userFotOTApproval as $user)
                    <option value="{{$user->user_id}}">{{ $user->formal_name() }}</option>
                @endforeach
              </select>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button wire:click="assignApprover" type="button" class="btn btn-primary">Assign</button>
            </div>
          </div>
        </div>
      </div>

</section>
