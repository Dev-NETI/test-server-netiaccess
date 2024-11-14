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
                                @if ($user->imagepath)
                                    <img src="{{asset('storage/useradminpic/'.$user->imagepath)}}" class="avatar-xl rounded-circle border border-4 border-white" alt="avatar">
                                @else
                                    <img src="{{asset('assets/images/avatar/avatar.jpg')}}" class="avatar-xl rounded-circle border border-4 border-white" alt="avatar">
                                @endif
                            </div>
                            <div class="lh-1">
                                <h2>{{$user->l_name}},  {{$user->f_name}} {{$user->m_name}}</h2> 
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
                            <ul class="list-unstyled ms-n2 mb-0">
                                <!-- Nav item -->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('all.changepassword') }}"><i class="fe fe-user nav-icon"></i>Security</a>
                                </li>
                                <li class="nav-item active">
                                    <a class="nav-link" href="{{ route('all.edit-profile') }}"><i class="fe fe-settings nav-icon"></i>Edit Profile</a>
                                </li>
                            </ul>
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
                            <div class="d-flex align-items-center mb-4 mb-lg-0">
                                @if ($user->imagepath)
                                    <img src="{{asset('storage/useradminpic/'.$user->imagepath)}}" id="img-uploaded" class="avatar-xl rounded-circle" alt="avatar">
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

                        <div>
                            <h1 class="mb-0">PERSONAL INFORMATION</h1>
                            <p class="mb-4">
                                Edit your personal information and address.
                            </p>
                            <!-- Form -->
                            <form wire:submit.prevent="updateprofile({{ $user->user_id }})" class="row gx-3">
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
                                        <input type="date" id="birth_day" class="form-control flatpickr" name="birth_day" wire:model.defer="birth_day" placeholder="Enter date ..">
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
                                                <input class="form-control" name="contact_num" id="contact_num" cols="30" rows="2" wire:model.defer="contact_num" 
                                                placeholder="Enter your contact number ..">
                                        </div>
                                        @error('contact_num') <p class="text-danger">{{$message}}</p> @enderror
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label" for="fleet">Fleet</label>
                                        <select class="form-select text-black" data-width="100%" wire:model.defer="selectedFleet" >
                                            <option value="">Select Fleet</option>
                                         
                                            @foreach ($fleets as $fleet)
                                                <option value="{{ $fleet->fleetid }}">{{ $fleet->fleet }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <hr>
                                    <div>
                                        <h1>ADDRESS</h1>
                                        @if ($user->regCode || $user->provCode )
                                            <h4>{{ $user->street }}, {{ optional($user->barangay)->brgyDesc }} {{ optional($user->citymun)->citymunDesc }} {{ optional($user->province)->provDesc }}, {{ optional($user->region)->regDesc }} {{ $user->postal }}  </h4>
                                        @elseif ($user->address)
                                            <h4 class="text-uppercase"> {{ $user->address}} </h4>
                                        @else
                                            <h4 class="text-uppercase"> THE ADDRESS NOT SPECIFIED. </h4>
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
                                            <select class="form-control" name="" id="" wire:model.defer="selectedBrgy" wire:loading.attr="disabled" wire:target="selectedBrgy">
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
                                            <input class="form-control" type="text" placeholder="Enter street/house no./etc." wire:model.defer="street">
                                            @error('street')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label for="email" class="form-label">Postal Code</label>
                                            <input class="form-control" type="text" placeholder="Enter postal code .. " wire:model.defer="postal">
                                            @error('postal')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        @endif
                                    </div>
                                    <hr>
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