<section class="pt-5">
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
                                <img src="/storage/uploads/traineepic/{{$trainee->imagepath}}" class="avatar-xl rounded-circle border border-4 border-white" alt="avatar">
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

                            @if(Auth::user()->u_type == 1)

                            <ul class="list-unstyled ms-n2 mb-0">
                                <!-- Nav item -->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('a.history', ['traineeid' => $trainee->traineeid])}}"><i class="fe fe-user nav-icon"></i>View History</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('a.editprofile', ['traineeid' => $trainee->traineeid])}}"><i class="fe fe-settings nav-icon"></i>Edit Profile</a>
                                </li>
                                <!-- Nav item -->
                                <li class="nav-item active">
                                    <a class="nav-link" href="{{route('a.editsecurity', ['traineeid' => $trainee->traineeid])}}"><i class="fe fe-user nav-icon"></i>Security</a>
                                </li>
                            </ul>
                            
                            @elseif (Auth::user()->u_type == 4)
                              <ul class="list-unstyled ms-n2 mb-0">
                                    <!-- Nav item -->
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('te.history', ['traineeid' => $trainee->traineeid])}}"><i class="fe fe-user nav-icon"></i>View History</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('te.editprofile', ['traineeid' => $trainee->traineeid])}}"><i class="fe fe-settings nav-icon"></i>Edit Profile</a>
                                    </li>
                                    <!-- Nav item -->
                                    <li class="nav-item active">
                                        <a class="nav-link" href="{{route('te.editsecurity', ['traineeid' => $trainee->traineeid])}}"><i class="fe fe-user nav-icon"></i>Security</a>
                                    </li>
                                </ul>
                            @else
                            <ul class="list-unstyled ms-n2 mb-0">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('c.history', ['traineeid' => $trainee->traineeid])}}"><i class="fe fe-user nav-icon"></i>View History</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('c.editprofile', ['traineeid' => $trainee->traineeid])}}"><i class="fe fe-settings nav-icon"></i>Edit Profile</a>
                                </li>
                                <!-- Nav item -->
                                <li class="nav-item active">
                                    <a class="nav-link" href="{{route('c.editsecurity', ['traineeid' => $trainee->traineeid])}}"><i class="fe fe-user nav-icon"></i>Security</a>
                                </li>
                            </ul>
                            @endif
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
                        </p>
                        <form class="row">
                            @csrf
                            <div class="mb-3 col-lg-6 col-md-12 col-12">
                                <label class="form-label" for="email">New email address</label>
                                <input id="email" type="email" name="email" class="form-control" wire:model="email" placeholder="">
                                <button type="submit" wire:click.prevent="update_email" class="btn btn-primary mt-2">
                                    Update Details
                                </button>
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
                                    <!-- New password -->
                                    <div class="mb-3 password-field">
                                        <label class="form-label" for="newpassword">New password</label>
                                        <div class="input-group">
                                                <input id="newpassword" type="password" onmouseover="this.type='text'"  name="newpassword" class="form-control mb-2" placeholder="" wire:model.defer="new_password" readonly>
                                                
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary" type="button" wire:click="generatePassword()">Generate Password</button>
                                                </div>
                                        </div>
                                        @error('new_password') <span class="text-danger">{{$message}}</span> @enderror
                                        <div class="row align-items-center g-0">
                                            <div class="col-6">
                                                <span data-bs-toggle="tooltip" data-placement="right" title="Test it by typing a password in the field below. To reach full strength, use at least 6 characters, a capital letter and a digit, e.g. 'Test01'">Password
                                                    strength
                                                    <i class="fe fe-help-circle ms-1"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    
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