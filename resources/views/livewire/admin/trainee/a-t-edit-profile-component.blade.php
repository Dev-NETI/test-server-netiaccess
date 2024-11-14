<section class="pt-5">
    <span wire:loading>
        <livewire:components.loading-screen-component />
    </span>
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                <!-- Bg -->
                <div class=" pt-16 rounded-top-md " style="
                    background: url({{asset('assets/images/background/profile-bg.jpg')}}) no-repeat;
                    background-size: cover;">
                </div>
                <div class="card rounded-0 rounded-bottom  px-4  pt-2 pb-4 ">
                    <div class="d-flex align-items-end justify-content-between  ">
                        <div class="d-flex align-items-center">
                            <div class="me-2 position-relative d-flex justify-content-end align-items-end mt-n5">
                                @if ($trainee->imagepath)
                                <img src="{{asset('storage/traineepic/'.$trainee->imagepath)}}" class="avatar-xl rounded-circle border border-4 border-white" alt="avatar">
                                @else
                                <img src="{{asset('assets/images/avatar/avatar.jpg')}}" class="avatar-xl rounded-circle border border-4 border-white" alt="avatar">
                                @endif
                            </div>
                            <div class="lh-1">
                                <h2 class="mb-0">{{$trainee->formal_name()}}
                                </h2>
                                <p class=" mb-0 d-block"><i>RANK: {{$trainee->rank->rank}} - {{$trainee->rank->rankacronym}}</i> </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row mt-0 mt-md-4">
            <div class="col-lg-3 col-md-4 col-12">
                <!-- Side navbar -->
                <nav class="navbar navbar-expand-md navbar-light shadow-sm mb-4 mb-lg-0 sidenav">
                    <!-- Menu -->
                    <a class="d-xl-none d-lg-none d-md-none text-inherit fw-bold" href="#">Menu</a>
                    <!-- Button -->
                    <button class="navbar-toggler d-md-none icon-shape icon-sm rounded bg-primary text-light" type="button" data-bs-toggle="collapse" data-bs-target="#sidenav" aria-controls="sidenav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="fe fe-menu"></span>
                    </button>
                    <!-- Collapse navbar -->
                    <div class="collapse navbar-collapse" id="sidenav">
                        <div class="navbar-nav flex-column">
                            <span class="navbar-header">Account Settings</span>
                            <!-- List -->
                            @if(Auth::user()->u_type == 1)

                            <ul class="list-unstyled ms-n2 mb-0">
                                <!-- Nav item -->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('a.history', ['traineeid' => $trainee->traineeid])}}"><i class="fe fe-user nav-icon"></i>View History</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" href="{{route('a.editprofile', ['traineeid' => $trainee->traineeid])}}"><i class="fe fe-settings nav-icon"></i>Edit Profile</a>
                                </li>
                                <!-- Nav item -->
                                <li class="nav-item ">
                                    <a class="nav-link" href="{{route('a.editsecurity', ['traineeid' => $trainee->traineeid])}}"><i class="fe fe-user nav-icon"></i>Security</a>
                                </li>

                            </ul>

                            @elseif (Auth::user()->u_type == 4)
                            <ul class="list-unstyled ms-n2 mb-0">
                                <!-- Nav item -->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('te.history', ['traineeid' => $trainee->traineeid])}}"><i class="fe fe-user nav-icon"></i>View History</a>
                                </li>
                                <li class="nav-item active">
                                    <a class="nav-link" href="{{route('te.editprofile', ['traineeid' => $trainee->traineeid])}}"><i class="fe fe-settings nav-icon"></i>Edit Profile</a>
                                </li>
                                <!-- Nav item -->
                                <li class="nav-item ">
                                    <a class="nav-link" href="{{route('te.editsecurity', ['traineeid' => $trainee->traineeid])}}"><i class="fe fe-user nav-icon"></i>Security</a>
                                </li>
                            </ul>
                            @else
                            <ul class="list-unstyled ms-n2 mb-0">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('c.history', ['traineeid' => $trainee->traineeid])}}"><i class="fe fe-user nav-icon"></i>View History</a>
                                </li>
                                <li class="nav-item active">
                                    <a class="nav-link" href="{{route('c.editprofile', ['traineeid' => $trainee->traineeid])}}"><i class="fe fe-settings nav-icon"></i>Edit Profile</a>
                                </li>
                                <!-- Nav item -->
                                <li class="nav-item ">
                                    <a class="nav-link" href="{{route('c.editsecurity', ['traineeid' => $trainee->traineeid])}}"><i class="fe fe-user nav-icon"></i>Security</a>
                                </li>
                            </ul>
                            @endif
                        </div>
                    </div>
                </nav>
            </div>
            <div class="col-lg-9 col-md-8 col-12 mb-5">
                <!-- Card -->
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header">
                        <h3 class="mb-0">Profile Details</h3>
                        <p class="mb-0">
                            You have full control to manage your own account setting.
                        </p>
                    </div>
                    <!-- Card body -->
                    <div class="card-body">
                        <div class="d-lg-flex align-items-center justify-content-between">

                            @if(Auth::user()->u_type == 1)
                            <div class="d-flex align-items-center mb-4 mb-lg-0">
                                @if ($trainee->imagepath)
                                <img src="{{asset('storage/traineepic/'.$trainee->imagepath)}}" id="img-uploaded" class="avatar-xl rounded-circle" alt="avatar">
                                @else
                                <img src="{{asset('assets/images/avatar/avatar.jpg')}}" id="img-uploaded" class="avatar-xl rounded-circle" alt="avatar">
                                @endif
                                <div class="ms-3">
                                    <h4 class="mb-0">Your avatar</h4>
                                    <p class="mb-0">
                                        PNG or JPG no bigger than 800px wide and tall.
                                    </p>
                                </div>
                            </div>
                            <div>
                                <form wire:submit.prevent="upload" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <p class="mb-1 text-dark">Upload the file here: <small style="color: red;"><i>(It only accept png,jpg,jpeg.)</i></small></p>
                                        <div class="input-group mb-1">
                                            <input type="file" class="form-control" wire:model.defer="file" wire:loading.attr="disabled" accept="image/png, image/jpeg" wire:target="file">
                                            <button class="input-group-text" type="submit">Upload</button>
                                        </div>
                                        @error('file') <small class="text-muted">{{$message}} </small>@enderror
                                    </div>
                                </form>
                            </div>
                        </div>
                        <hr class="my-5">
                        @endif

                        <div>
                            <h1 class="mb-0">PERSONAL INFORMATION</h1>
                            <p class="mb-4">
                                Edit your personal information and address.
                            </p>
                            <!-- Form -->
                            <form wire:submit.prevent="update" class="row gx-3">
                                @csrf
                                <!-- First name -->
                                <div class="row">
                                    <!-- Email Address -->
                                    <div class="mb-3 col-md-4">
                                        <label for="email" class="form-label">Firstname <text style="color:red">*</text> </label>
                                        <input type="text" id="f_name" class="form-control" name="f_name" placeholder="Enter firstname .." wire:model.defer="f_name" required>
                                        @error('f_name')
                                        <p class="text-danger">{{$message}}</p>
                                        @enderror

                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label for="email" class="form-label">Middlename</label>
                                        <input type="text" id="m_name" class="form-control" name="m_name" placeholder="Enter middlename .." wire:model.defer="m_name">
                                        @error('m_name')
                                        <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label for="email" class="form-label">Lastname <text style="color:red">*</text></label>
                                        <input type="text" id="l_name" class="form-control" name="l_name" placeholder="Enter lastname .." wire:model.defer="l_name" required>
                                        @error('l_name')
                                        <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label for="email" class="form-label">Suffix</label>
                                        <input type="text" id="suffix" class="form-control" name="suffix" placeholder="Enter suffix e.g. jr, sr and etc .." wire:model.defer="suffix">
                                        @error('suffix')
                                        <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label for="email" class="form-label">Birthday <text style="color:red">*</text></label>
                                        <input type="date" id="birth_day" class="form-control" name="birth_day" wire:model.defer="birth_day">
                                        @error('birth_day')
                                        <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label for="text" class="form-label">Birthplace <text style="color:red">*</text></label>
                                        <input class="form-control" name="birth_place" id="birth_place" cols="30" rows="2" wire:model.defer="birth_place" placeholder="Enter your birthplace .."></input>
                                        @error('birth_place')
                                        <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label for="text" class="form-label">Contact #:<text style="color:red">*</text></label>
                                        <div class="input-group">
                                            <div class="input-group-append">
                                                <select class="form-control" wire:model="dialing_code">
                                                    @foreach ($dialing_code_data as $data)
                                                    <option value="{{$data->id}}">{{$data->country_code}}(+{{$data->dialing_code}})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <input class="form-control" name="contact_num" id="contact_num" cols="30" rows="2" wire:model.defer="contact_num" placeholder="Enter your contact number .."></input>
                                        </div>
                                        @error('contact_num') <p class="text-danger">{{$message}}</p> @enderror
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label for="email" class="form-label">Gender <text style="color:red">*</text></label>
                                        <select class="form-control" name="" id="" wire:model.defer="selectedGender">
                                            <option value="">Select Gender</option>
                                            <option value="1">Male</option>
                                            <option value="2">Female</option>
                                        </select>
                                        @error('selectedGender')
                                        <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label for="email" class="form-label">Nationality</label>
                                        <select class="form-control" name="" id="" wire:model.defer="selectedNationality">
                                            @foreach ($nationalities as $nationality)
                                            <option value="{{$nationality->nationalityid}}">{{$nationality->nationality}}</option>
                                            @endforeach
                                        </select>
                                        @error('selectedNationality')
                                        <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label for="text" class="form-label">SRN Number <i><small>(if applicable)</small></i></label>
                                        <input class="form-control" name="srn_num" id="srn_num" cols="30" rows="2" wire:model.defer="srn_num" placeholder="Enter your SRN number .."></input>
                                        @error('srn_num')
                                        <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label for="text" class="form-label">TIN Number <i><small>(if applicable)</small></i></label>
                                        <input class="form-control" name="tin_num" id="tin_num" cols="30" rows="2" wire:model.defer="tin_num" placeholder="Enter your TIN number .."></input>
                                        @error('tin_num')
                                        <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <hr>
                                    <div>
                                        <h1>ADDRESS</h1>
                                        @if ($address)
                                        <small>YOUR CURRENT ADDRESS: </small>
                                        <h4 class="text-uppercase"> <i>{{$address}}</i></h4>
                                        @else
                                        <small>YOUR CURRENT ADDRESS: </small>
                                        @if(optional($trainee->brgy)->brgyDesc)
                                        <h4 class="text-uppercase"> <i>{{$trainee->street}} , {{$trainee->brgy->brgyDesc}}, {{$trainee->city->citymunDesc}}, {{$trainee->prov->provDesc}}, {{$trainee->reg->regDesc}}, {{$trainee->postal}}</i></h4>
                                        @else
                                        <h4 class="text-uppercase"> Not specified the address. </h4>
                                        @endif
                                        @endif
                                    </div>
                                    <div class="row">
                                        <div class="mb-3 col-md-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="invalidCheck" wire:model="showAnotherForm">
                                                <label class="form-check-label" for="invalidCheck">
                                                    Check this box if the trainees are from another country.
                                                </label>
                                            </div>
                                        </div>
                                        @if($showAnotherForm)
                                        <!-- Display your another form here -->
                                        <div class="mb-3 col-md-12">
                                            <label for="email" class="form-label">Full Address</label>
                                            <input type="text" class="form-control" name="" placeholder="Enter here your address .." wire:model.defer="address">
                                        </div>
                                        @else
                                        <div class="mb-3 col-md-4">
                                            <label for="email" class="form-label">Region</label>
                                            <select class="form-control" wire:model="selectedRegion" wire:loading.attr="disabled" wire:target="selectedRegion">
                                                <option value="">Select Region</option>
                                                @foreach ($regions as $region)
                                                <option value="{{$region->regCode}}">{{$region->regDesc}}</option>
                                                @endforeach
                                            </select>
                                            @error('selectedRegion')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label for="email" class="form-label">Province</label>
                                            <select class="form-control" name="" id="" wire:model="selectedProvince" wire:loading.attr="disabled" wire:target="selectedProvince">
                                                <option value="">Select province</option>
                                                @foreach ($provinces as $province)
                                                <option value="{{$province->provCode}}">{{$province->provDesc}}</option>
                                                @endforeach
                                            </select>
                                            @error('selectedProvince')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label for="email" class="form-label">City</label>
                                            <select class="form-control" name="" id="" wire:model="selectedCity" wire:loading.attr="disabled" wire:target="selectedCity">
                                                <option value="">Select city</option>
                                                @foreach ($citys as $city)
                                                <option value="{{$city->citymunCode}}">{{$city->citymunDesc}}</option>
                                                @endforeach
                                            </select>
                                            @error('selectedCity')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label for="email" class="form-label">Brgy</label>
                                            <select class="form-control" name="" id="" wire:model="selectedBrgy" wire:loading.attr="disabled" wire:target="selectedBrgy">
                                                <option value="">Select Brgy</option>
                                                @foreach ($brgys as $brgy)
                                                <option value="{{$brgy->brgyCode}}">{{$brgy->brgyDesc}}</option>
                                                @endforeach
                                            </select>
                                            @error('selectedBrgy')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label for="email" class="form-label">Street/House No./etc.</label>
                                            <input class="form-control" type="text" placeholder="Enter street/house no./etc." wire:model="street">
                                            @error('street')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label for="email" class="form-label">Postal Code</label>
                                            <input class="form-control" type="text" placeholder="Enter postal code .. " wire:model="postal">
                                            @error('postal')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        @endif
                                    </div>
                                    <hr>
                                    <div class="mb-2">
                                        <h1 class="mb-1">EMPLOYMENT INFORMATION</h1>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3 col-md-4">
                                            <label for="email" class="form-label">Experienced Rank <text style="color:red">*</text></label>
                                            <select class="form-control" name="" id="" wire:model="s_exp_rank">
                                                <option value="">Select rank</option>
                                                @foreach ($exp_ranks as $rank)
                                                <option value="{{$rank->rankid}}"> {{$rank->rankacronym}} - {{$rank->rank}}</option>
                                                @endforeach
                                            </select>
                                            @error('s_exp_rank')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label for="email" class="form-label">Company <text style="color:red">*</text></label>
                                            <select class="form-control" name="" id="" wire:model="s_company">
                                                <option value="">Select company</option>
                                                @foreach ($companys as $company)
                                                <option value="{{$company->companyid}}"> {{$company->company}}</option>
                                                @endforeach
                                            </select>
                                            @error('s_company')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label for="email" class="form-label">Fleet <text style="color:red">*</text></label>
                                            <select class="form-control" name="" id="" wire:model="s_fleet">
                                                <option value="">Select Fleet</option>
                                                @foreach ($fleets as $fleet)
                                                <option value="{{$fleet->fleetid}}"> {{$fleet->fleet}}</option>
                                                @endforeach
                                            </select>
                                            @error('s_fleet')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <hr>
                                    </div>
                                    <div class="col-12">
                                        <!-- Button -->
                                        <button class="btn btn-primary" type="submit">
                                            Update Profile
                                        </button>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>