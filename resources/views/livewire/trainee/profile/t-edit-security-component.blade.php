<main>

    <section class="pt-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                    <!-- Bg -->
                    <div class=" pt-16 rounded-top-md " style="
                    background: url(../assets/images/background/profile-bg.jpg) no-repeat;
                    background-size: cover;">
                    </div>
                    <div class="card rounded-0 rounded-bottom  px-4  pt-2 pb-4 ">
                        <div class="d-flex align-items-end justify-content-between  ">
                            <div class="d-flex align-items-center">
                                <div class="me-2 position-relative d-flex justify-content-end align-items-end mt-n5">
                                    @if ($trainee->imagepath)
                                    <img src="{{asset('storage/traineepic/'. $trainee->imagepath)}}" class="avatar-xl rounded-circle border border-4 border-white" alt="avatar">
                                    @else
                                    <img src="{{asset('assets/images/avatar/avatar.jpg')}}" class="avatar-xl rounded-circle border border-4 border-white" alt="avatar">
                                    @endif
                                </div>
                                <div class="lh-1">
                                    <h2 class="mb-0">{{Auth::guard('trainee')->user()->formal_name()}}
                                    </h2>
                                    <p class=" mb-0 d-block"><i>RANK: {{Auth::guard('trainee')->user()->rank->rank}} - {{Auth::guard('trainee')->user()->rank->rankacronym}}</i> </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
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
                                <ul class="list-unstyled ms-n2 mb-0">
                                    <!-- Nav item -->
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('t.editprofile')}}"><i class="fe fe-settings nav-icon"></i>Edit Profile</a>
                                    </li>
                                    <!-- Nav item -->
                                    <li class="nav-item active">
                                        <a class="nav-link" href="{{route('t.editsecurity')}}"><i class="fe fe-user nav-icon"></i>Security</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
                <div class="col-lg-9 col-md-8 col-12 mb-3">
                    <!-- Card -->
                    <div class="card">
                        <!-- Card header -->
                        <div class="card-header">
                            <h3 class="mb-0">Security</h3>
                            <p class="mb-0">
                                Edit your account settings and change your password here.
                            </p>
                        </div>
                        <!-- Card body -->
                        <div class="card-body">
                            <h4 class="mb-0">Email Address</h4>
                            <p>
                                Your current email address is
                                <span class="text-success">{{$trainee->email}}</span>
                                and contact number is
                                <span class="text-success">{{$trainee->dialing_code->dialing_code}}{{$trainee->contact_num}}</span>
                            </p>
                            <form wire:submit.prevent="update_email">
                                @csrf
                                <div class="row">
                                    <div class="mb-3 col-6">
                                        <label class="form-label" for="email">New email address</label>
                                        <input id="email" type="email" name="email" class="form-control" wire:model="email" placeholder="">
                                        @error('email') <span class="text-danger">{{$message}}</span> @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-3 col-6">
                                        <label class="form-label" for="email">New contact number</label>
                                        <div class="input-group">
                                                <div class="input-group-append">
                                                        <select class="form-control" wire:model="d_code">
                                                            <option value="">Select</option>
                                                            @foreach ($dialing_code_data as $data)
                                                                <option value="{{$data->id}}">{{$data->country_code}}(+{{$data->dialing_code}})</option>
                                                            @endforeach
                                                        </select>
                                                </div>
                                                <input wire:model="p_num" type="text" class="form-control">
                                        </div>
                                        @error('d_code') <span class="text-danger">{{$message}}</span> @enderror
                                        @error('p_num') <span class="text-danger">{{$message}}</span> @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-3 col-6">
                                        <button type="submit" class="btn btn-primary mt-2">
                                            Update Details
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <hr class="my-5">
                            <div>
                                <h4 class="mb-0">Change Password</h4>
                                <p>
                                    We will email you a confirmation when changing your
                                    password, so please expect that email after submitting.
                                </p>
                                <!-- Form -->
                                <form wire:submit.prevent="update_pass" class="row">
                                    @csrf
                                    <div class="col-lg-6 col-md-12 col-12">
                                        <!-- Current password -->
                                        <div class="mb-3">
                                            <label class="form-label" for="currentpassword">Current password</label>
                                            <input id="currentpassword" type="password" name="currentpassword" class="form-control" placeholder="" wire:model.defer="input_current">
                                        </div>
                                        <!-- New password -->
                                        <div class="mb-3 password-field">
                                            <x-success-message />
                                            <label class="form-label" for="newpassword">New password</label>
                                            <div class="input-group">
                                                    <input id="newpassword" type="password" name="newpassword" onmouseover="this.type='text'" class="form-control mb-2" placeholder="" wire:model.defer="new_password" >
                                                    
                                                    <div class="input-group-append">
                                                         <button type="button" class="btn btn-primary" wire:click="generatePassword()">Generate Password</button>
                                                    </div>
                                            </div>
                                            <div class="row align-items-center g-0">
                                                <div class="col-6">
                                                    <span data-bs-toggle="tooltip" data-placement="right" title="Test it by typing a password in the field below. To reach full strength, use at least 6 characters, a capital letter and a digit, e.g. 'Test01'">Password
                                                        strength
                                                        <i class="fe fe-help-circle ms-1"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <div class="mb-3">
                                            <!-- Confirm new password -->
                                            <label class="form-label" for="confirmpassword">Confirm New Password</label>
                                            <input id="confirmpassword" type="password" name="confirmpassword" class="form-control mb-2" placeholder="" wire:model.defer="confirm_password">
                                        </div> --}}
                                        <!-- Button -->
                                        <button type="submit" class="btn btn-primary">
                                            Save Password
                                        </button>
                                        <div class="col-6"></div>
                                    </div>
                                    <div class="col-12 mt-4">
                                        <p class="mb-0">
                                            Can't remember your current password?
                                            <a href="#">Reset your password via email</a>
                                        </p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>

</main>