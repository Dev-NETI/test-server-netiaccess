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
                        <span class="fs-5 text-muted"></span>
                    </h1>
                    <!-- Breadcrumb  -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{route('a.dashboard')}}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('a.adminusers')}}">User</a></li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Admin
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-12 col-12 mb-5">
        <div class="card">
            <!-- card header  -->
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-5">
                        <h4 class="mb-1">Assign roles to {{ $user->l_name }}, {{ $user->f_name }} {{ $user->m_name }}
                            {{ $user->suffix }}</h4>
                    </div>
                </div>
            </div>
            <div class="card-body row">

                @if (Session::has('alert-success'))
                <div class="alert alert-success" role="alert">
                    {{ Session::get('alert-success') }}
                </div>
                @elseif(Session::has('alert-danger'))
                <div class="alert alert-danger" role="alert">
                    {{ Session::get('alert-danger') }}
                </div>
                @endif



                <div class="col-md-5 row">

                    <div class="col-md-6 offset-md-6">
                        <input type="text" class="form-control" wire:model.debounce.500ms="search_role"
                            placeholder="Search Role">
                    </div>

                    <div class="table-responsive col-md-12 mt-3">
                        <table class="table table-bordered table-hover" width="100%">
                            <thead>
                                <tr>
                                    <th colspan="2" class="text-center">Select Roles</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $checkcount = 1; @endphp
                                @foreach ($selectRoles as $role)
                                <tr>
                                    <td>
                                        <input type="checkbox" wire:model.defer="selectedRoles" value="{{ $role->id }}"
                                            id="checkBox{{ $checkcount }}" form="formAddRoles">
                                    </td>
                                    <td>
                                        <label class="fs-6" for="checkBox{{ $checkcount }}">{{ $role->rolename
                                            }}</label>
                                    </td>
                                </tr>
                                @php $checkcount++; @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-12">
                        {{ $selectRoles->links('livewire.components.customized-pagination-link') }}
                        Total: {{ $t_allschedules }}
                    </div>

                </div>

                <div class="col-md-2 d-flex align-items-center">
                    <form id="formAddRoles" wire:submit.prevent="addRoles">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-block ">
                            <i class="bi bi-chevron-double-right"></i>
                            Add roles
                        </button>
                    </form>
                </div>

                <div class="col-md-5">
                    <div class="container mb-5">
                        <div class="row">
                            <div class="col-md-6 offset-md-6">
                                <input type="text" class="form-control" wire:model.debounce.500ms="search_role_user"
                                    placeholder="Search Role">
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-hover" width="100%">
                        <thead>
                            <tr>
                                <th colspan="2" class="text-center">Current Roles</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($adminroles as $adminrole)
                            <tr>
                                <td>
                                    <button wire:click="deleteRole({{ $adminrole->id }})" class="btn btn-sm btn-danger"
                                        title="delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                                <td class="fs-6">{{ optional($adminrole->roles)->rolename }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="row">
                        {{ $adminroles->links('livewire.components.customized-pagination-link') }}
                        Total: {{ $Admint_allschedules }}
                    </div>

                </div>

            </div>
        </div>
    </div>


</section>