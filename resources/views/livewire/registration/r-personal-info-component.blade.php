<main>
    <section class="container d-flex flex-column">
        <span wire:loading>
            <livewire:components.loading-screen-component />
        </span>
        <div class="row mt-10 justify-content-center g-0 min-vh-100 mb-10">
            <div class="col-lg-10 col-md-8 py-8 py-xl-0">
                <!-- Card -->
                <div class="card shadow">
                    <!-- Card body -->
                    <div class="card-body p-3">
                        <div class="mb-4">
                            <h1 class="mb-1 fw-bold">Fill up your personal information</h1>
                            @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible" role="alert" id="liveAlert">
                                <strong>"Oops! It seems like there were some issues with your submission. Please review the following error messages to ensure all required fields are filled correctly."</strong>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            @endif
                        </div>
                        <!-- Form -->
                        <form action="" method="" enctype="multipart/form-data">
                            @csrf

                            @if ($next_page == 1)
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
                                    <input type="text" id="m_name" class="form-control" name="m_name" placeholder="Enter middlename .." wire:model.defer="m_name" required>
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
                                <div class="mb-3 col-md-4">
                                    <label for="email" class="form-label">Suffix</label>
                                    <input type="text" id="suffix" class="form-control" name="suffix" placeholder="Enter suffix e.g. jr, sr and etc .." wire:model.defer="suffix">
                                    @error('suffix')
                                    <p class="text-danger">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="email" class="form-label">Birthday <text style="color:red">*</text></label>
                                    <input type="date" id="birth_day" class="form-control" name="birth_day" wire:model.defer="birth_day" required>
                                    @error('birth_day')
                                    <p class="text-danger">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="text" class="form-label">Birthplace <text style="color:red">*</text></label>
                                    <input class="form-control" name="birth_place" id="birth_place" cols="30" rows="2" wire:model.defer="birth_place" placeholder="Enter your birthplace .."></input>
                                    @error('birth_place')
                                    <p class="text-danger">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label for="email" class="form-label">Gender <text style="color:red">*</text></label>
                                    <select class="form-select" name="" id="" wire:model.defer="selectedGender">
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
                                    <select class="form-select" name="" id="" wire:model.defer="selectedNationality">
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
                                    <h1 class="fw-bold">Address</h1>
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
                                    @if($showAnotherForm)
                                    <!-- Display your another form here -->
                                    <div class="mb-3 col-md-12">
                                        <label for="email" class="form-label">Full Address</label>
                                        <input type="text" class="form-control" name="" placeholder="Enter here your address .." wire:model.defer="address">
                                    </div>
                                    @else
                                    <div class="mb-3 col-md-4">
                                        <label for="email" class="form-label">Region</label>
                                        <select class="form-select" name="" id="" wire:model="selectedRegion">
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
                                        <select class="form-select" name="" id="" wire:model="selectedProvince">
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
                                        <select class="form-select" name="" id="" wire:model="selectedCity">
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
                                        <select class="form-select" name="" id="" wire:model="selectedBrgy">
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
                                <div class="text-end">
                                    <button type="submit" wire:click.prevent="prev_page" class="btn btn-danger">
                                        Back
                                    </button>
                                    <button type="submit" wire:click.prevent="next_page" class="btn btn-primary">
                                        Next
                                    </button>
                                </div>
                            </div>
                            @elseif ($next_page == 2)
                            <div class="mb-2">
                                <h1 class="mb-1 fw-bold">Employment Information</h1>
                            </div>
                            <div class="row">
                                <div class="mb-3 col-md-4">
                                    <label for="email" class="form-label">Experienced Rank <text style="color:red">*</text></label>
                                    <select class="form-select" name="" id="" wire:model.defer="s_exp_rank">
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
                                    <select class="form-select" name="" id="" wire:model="s_company">
                                        <option value="">Select company</option>
                                        @foreach ($companys as $company)
                                        <option value="{{$company->companyid}}"> {{$company->company}}</option>
                                        @endforeach
                                    </select>
                                    @error('s_company')
                                    <p class="text-danger">{{$message}}</p>
                                    @enderror
                                </div>
                                @if ($s_company == 1)
                                <div class="mb-3 col-md-4">
                                    <label for="email" class="form-label">Fleet <text style="color:red">*</text></label>
                                    <select class="form-select" name="" id="" wire:model.defer="s_fleet">
                                        <option value="16">Select Fleet</option>
                                        @foreach ($fleets as $fleet)
                                        <option value="{{$fleet->fleetid}}"> {{$fleet->fleet}}</option>
                                        @endforeach
                                    </select>
                                    @error('s_fleet')
                                    <p class="text-danger">{{$message}}</p>
                                    @enderror
                                </div>
                                @endif
                                <hr>
                            </div>
                            <div>
                                <div class="mb-2">
                                    <h1 class="mb-1 fw-bold">Login Credentials</h1>
                                </div>
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label for="email" class="form-label">Email </label>
                                        <input type="email" class="form-control" wire:model="email" disabled>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="email" class="form-label">Contact Number </label>
                                        <input type="email" class="form-control" wire:model="contact_num" disabled>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label for="email" class="form-label">Create Password <text style="color:red">*</text></label>
                                        <input type="password" class="form-control" placeholder="********" wire:model.defer="i_password">
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="email" class="form-label">Re-Type Password <text style="color:red">*</text></label>
                                        <input type="password" class="form-control" placeholder="********" wire:model.defer="c_password">
                                    </div>
                                    <div class="mb-3 col-md-12">
                                        <!-- <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#exampleModalScrollable">
                                            I accept the Terms and Conditions
                                        </button> -->
                                        <!-- Modal -->

                                    </div>
                                </div>
                                <div>
                                    <hr>
                                    <!-- Button -->
                                    <div class="text-end">
                                        <button type="submit" wire:click.prevent="prev_page" class="btn btn-danger">
                                            Back
                                        </button>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalScrollable">
                                            Proceed
                                        </button>

                                    </div>
                                </div>
                        </form>
                    </div>
                </div>

                @endif
            </div>
        </div>
    </section>
    @include('modals.data-privacy-modal')
</main>