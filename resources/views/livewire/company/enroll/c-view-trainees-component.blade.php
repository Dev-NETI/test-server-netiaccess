<section class="container-fluid p-4">
    <span wire:loading>
        <livewire:components.loading-screen-component />
    </span>
    @push('scripts')
    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.on('validationErrors', (messages) => {
                const errorToastContainer = document.getElementById('error-toast-container');
                // Iterate over the properties of the messages object
                for (const field in messages) {
                    if (messages.hasOwnProperty(field)) {
                        messages[field].forEach(message => {
                            // Create a new toast element for each error
                            const toastElement = document.createElement('div');
                            toastElement.classList.add('toast', 'mb-1', 'bg-light-warning',
                                'border-warning');
                            toastElement.setAttribute('role', 'alert');
                            toastElement.setAttribute('aria-live', 'assertive');
                            toastElement.setAttribute('aria-atomic', 'true');

                            const flexElement = document.createElement('div');
                            flexElement.classList.add('d-flex');
                            flexElement.classList.add('align-items-center');

                            const icon = document.createElement('i');
                            icon.classList.add('bi', 'bi-exclamation-circle', 'ms-3',
                                'text-warning');

                            flexElement.appendChild(icon);

                            const toastBody = document.createElement('div');
                            toastBody.classList.add('toast-body', 'fs-12', 'text-warning');
                            toastBody.textContent = `${message}`;

                            flexElement.appendChild(toastBody);
                            toastElement.appendChild(flexElement);
                            errorToastContainer.appendChild(toastElement);

                            // Create a Bootstrap toast from the toast element and show it
                            const bsToast = new bootstrap.Toast(toastElement);
                            bsToast.show();
                        });
                    }
                }
            });
        });
    </script>
    @endpush


    <div class="toast-container align-items-center position-absolute bottom-0 end-0 p-3" id="error-toast-container">
    </div>

    <div class="row">
        <!-- Page Header -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-flex justify-content-between align-items-center">
                <div class="mb-2 mb-lg-0">
                    <h1 class="mb-1 h2 fw-bold">
                        List of Trainees
                        <span class="fs-5 text-muted">({{ number_format($c_trainees, 0, '.', ',') }})</span>
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

            <div>
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addTraineeModal"> Add Trainee</button>

                <!-- Modal -->
                <div wire:ignore.self class="modal fade" id="addTraineeModal" tabindex="-1">
                    <div class="modal-dialog modal-xl ">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Trainee</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div id="form-container" class="row">
                                    <div class="modal-body">
                                        <form wire:submit.prevent="createTrainee">
                                            @csrf
                                            <div class="row">
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label">Firstname <text style="color:red">*</text>
                                                    </label>
                                                    <input type="text" id="f_name" class="form-control" name="f_name" placeholder="Enter firstname .." wire:model.defer="f_name" required>
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label">Middlename</label>
                                                    <input type="text" id="m_name" class="form-control" name="m_name" placeholder="Enter middlename .." wire:model.defer="m_name" required>
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label">Lastname <text style="color:red">*</text></label>
                                                    <input type="text" id="l_name" class="form-control" name="l_name" placeholder="Enter lastname .." wire:model.defer="l_name" required>

                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label">Suffix</label>
                                                    <input type="text" id="suffix" class="form-control" name="suffix" placeholder="Enter suffix e.g. jr, sr and etc .." wire:model.defer="suffix">

                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label">Birthday <text style="color:red">*</text></label>
                                                    <input type="date" id="birth_day" class="form-control" name="birth_day" wire:model.defer="birth_day" required>
                                                </div>

                                                <div class="mb-3 col-md-4">
                                                    <label for="text" class="form-label">Birthplace <text style="color:red">*</text></label>
                                                    <input class="form-control" name="birth_place" id="birth_place" cols="30" rows="2" wire:model.defer="birth_place" placeholder="Enter your birthplace .."></input>


                                                </div>
                                                <div class="mb-3 col-md-3">
                                                    <label class="form-label">Gender <text style="color:red">*</text></label>
                                                    <select class="form-select" name="" id="" wire:model.defer="selectedGender">
                                                        <option value="">Select Gender</option>
                                                        <option value="1">Male</option>
                                                        <option value="2">Female</option>
                                                    </select>

                                                </div>
                                                <div class="mb-3 col-md-3">
                                                    <label class="form-label">Nationality</label>
                                                    <select class="form-select" name="" id="" wire:model.defer="selectedNationality">
                                                        @foreach ($nationalities as $nationality)
                                                        <option value="{{ $nationality->nationalityid }}">
                                                            {{ $nationality->nationality }}
                                                        </option>
                                                        @endforeach
                                                    </select>

                                                </div>
                                                <div class="mb-3 col-md-3">
                                                    <label for="text" class="form-label">SRN Number <i><small>(if
                                                                applicable)</small></i></label>
                                                    <input class="form-control" name="srn_num" id="srn_num" cols="30" rows="2" wire:model.defer="srn_num" placeholder="Enter your SRN number .."></input>

                                                </div>
                                                <div class="mb-3 col-md-3">
                                                    <label for="text" class="form-label">TIN Number <i><small>(if
                                                                applicable)</small></i></label>
                                                    <input class="form-control" name="tin_num" id="tin_num" cols="30" rows="2" wire:model.defer="tin_num" placeholder="Enter your TIN number .."></input>

                                                </div>
                                                <hr>
                                                <div>
                                                    <h3 class="fw-bold">Address</h1>
                                                </div>
                                                <div class="row">
                                                    <div class="mb-3 col-md-12">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="" id="invalidCheck" wire:model="showAnotherForm" required>
                                                            <label class="form-check-label" for="invalidCheck">
                                                                If you are from another country, please check this box.
                                                            </label>
                                                        </div>
                                                    </div>

                                                </div>
                                                @if ($showAnotherForm)
                                                <div class="mb-3 col-md-12">
                                                    <label class="form-label">Full Address</label>
                                                    <input type="text" class="form-control" name="" placeholder="Enter here your address .." wire:model.defer="address">
                                                </div>

                                                @else
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label">Region</label>
                                                    <select class="form-select" name="" id="" wire:model="selectedRegion">
                                                        <option default>Select Region</option>
                                                        @foreach ($regions as $region)
                                                        <option value="{{ $region->regCode }}">
                                                            {{ $region->regDesc }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    @error('selectedRegion')
                                                    <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label">Province</label>
                                                    <select class="form-select" name="" id="" wire:model="selectedProvince">
                                                        <option default>Select province</option>
                                                        @foreach ($provinces as $province)
                                                        <option value="{{ $province->provCode }}">
                                                            {{ $province->provDesc }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    @error('selectedProvince')
                                                    <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label">City</label>
                                                    <select class="form-select" name="" id="" wire:model="selectedCity">
                                                        <option default>Select city</option>
                                                        @foreach ($citys as $city)
                                                        <option value="{{ $city->citymunCode }}">
                                                            {{ $city->citymunDesc }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    @error('selectedCity')
                                                    <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label">Brgy</label>
                                                    <select class="form-select" name="" id="" wire:model="selectedBrgy">
                                                        <option default>Select Brgy</option>
                                                        @foreach ($brgys as $brgy)
                                                        <option value="{{ $brgy->brgyCode }}">{{ $brgy->brgyDesc }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    @error('selectedBrgy')
                                                    <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label">Street/House No./etc.</label>
                                                    <input class="form-control" type="text" placeholder="Enter street/house no./etc." wire:model.defer="street">
                                                    @error('street')
                                                    <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label">Postal Code</label>
                                                    <input class="form-control" type="text" placeholder="Enter postal code .. " wire:model.defer="postal">
                                                    @error('postal')
                                                    <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                @endif
                                                <div class="mb-2">
                                                    <h3 class="mb-1 fw-bold">Employment Information</h1>
                                                </div>
                                                <div class="row">
                                                    <div class="mb-3 col-md-4">
                                                        <label class="form-label">Experienced Rank <text style="color:red">*</text></label>
                                                        <select class="form-select" name="" id="" wire:model.defer="s_exp_rank">
                                                            <option value="">Select rank</option>
                                                            @foreach ($exp_ranks as $rank)
                                                            <option value="{{ $rank->rankid }}"> {{ $rank->rankacronym }}
                                                                - {{ $rank->rank }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('s_exp_rank')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3 col-md-4">
                                                        <label class="form-label">Company <text style="color:red">*</text></label>
                                                        <select class="form-select" name="" id="" wire:model="s_company">
                                                            <option value="">Select company</option>
                                                            @foreach ($companys as $company)
                                                            <option value="{{ $company->companyid }}">
                                                                {{ $company->company }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                        @error('s_company')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    @if ($s_company == 1)
                                                    <div class="mb-3 col-md-4">
                                                        <label class="form-label">Fleet <text style="color:red">*</text></label>
                                                        <select class="form-select" name="" id="" wire:model.defer="s_fleet">
                                                            <option value="">Select Fleet</option>
                                                            @foreach ($fleets as $fleet)
                                                            <option value="{{ $fleet->fleetid }}">
                                                                {{ $fleet->fleet }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                        @error('s_fleet')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    @endif
                                                    <hr>
                                                </div>

                                                <div class="mb-2">
                                                    <h3 class="mb-1 fw-bold">Login Credentials</h1>
                                                </div>
                                                <div class="row">
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">Email </label>
                                                        <input type="email" class="form-control" wire:model.defer="email">
                                                    </div>
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">Contact Number </label>
                                                        <input type="email" class="form-control" wire:model.defer="contact_num">
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">Create Password <text style="color:red">*</text></label>
                                                        <input type="password" class="form-control" placeholder="********" wire:model.defer="i_password">
                                                    </div>
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">Re-Type Password <text style="color:red">*</text></label>
                                                        <input type="password" class="form-control" placeholder="********" wire:model.defer="c_password">
                                                    </div>
                                                </div>

                                                <!-- Modal footer -->
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-primary" data-bs-target="#add-trainee-modal" wire:click.prevent="createTrainee">Add</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 col-12 mb-5">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-5">
                                        <h4 class="mb-1">List of Trainees Account</h4>
                                    </div>
                                    <div class="col-lg-3 text-end">
                                        <label for="" class="form-label pt-2">Search:</label>
                                    </div>
                                    <div class="col-lg-4 float-end">
                                        <input type="text" placeholder="search in name, rank or email .." wire:model.debounce.500ms="search" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive text-center">
                                <table class="table table-sm text-nowrap table-hover mb-0 table-centered" width="100%" height="100%">
                                    <thead>
                                        <tr>
                                            <th>Action &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                            <th>Profile</th>
                                            <th>Name</th>
                                            <th>Rank</th>
                                            <th>Email</th>
                                            <th>Company</th>
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
                                                        <button class="dropdown-item" wire:click="getTrainee({{ $t_account->traineeid }})" data-bs-toggle="modal" data-bs-target="#exampleModal-2"><i class="bi bi-plus" style="font-size: 1em;"></i>&nbsp;Enrol
                                                            Crew</button>
                                                        <a class="dropdown-item" href="{{ route('c.editprofile', ['traineeid' => $t_account->traineeid]) }}"><i class="bi bi-person-check"></i>&nbsp;Edit
                                                            Profile</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if ($t_account->imagepath)
                                                <img src="{{asset('storage/traineepic/'.$t_account->imagepath)}}" width="50" alt="avatar">
                                                @else
                                                <img src="{{ asset('assets/images/avatar/avatar.jpg') }}" width="50" alt="avatar">
                                                @endif
                                            </td>
                                            @if ($t_account)
                                            <td>{{ strtoupper($t_account->certificate_name()) }}
                                            </td>
                                            @else
                                            <td class="text-danger">Not Specified</td>
                                            @endif
                                            @if ($t_account->rank)
                                            <td>{{ strtoupper($t_account->rank->rank) }}</td>
                                            @else
                                            <td class="text-danger">Not Specified</td>
                                            @endif
                                            @if ($t_account->email)
                                            <td>{{ $t_account->email }}</td>
                                            @else
                                            <td class="text-danger">Not Specified</td>
                                            @endif
                                            @if ($t_account->company)
                                            <td>{{ strtoupper($t_account->company->company) }}</td>
                                            @else
                                            <td class="text-danger">Not Specified</td>
                                            @endif
                                            @if ($t_account->contact_num)
                                            <td>{{ strtoupper($t_account->contact_num) }}</td>
                                            @else
                                            <td class="text-danger">Not Specified</td>
                                            @endif
                                            @if ($t_account->address)
                                            <td>{{ strtoupper($t_account->address) }}</td>
                                            @else
                                            <td class="text-danger">Not Specified</td>
                                            @endif
                                            @if ($t_account->birthday)
                                            {{-- <td>{{ Carbon::parse($t_account->birthday)->format('F j, Y') }}</td> --}}
                                            <td>{{ $t_account->birthday_parse }}</td>
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
                                        {{ $t_accounts->appends(['search' => $search])->links('livewire.components.customized-pagination-link') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('livewire.admin.trainee.a-t-create-trainee-modal')

</section>