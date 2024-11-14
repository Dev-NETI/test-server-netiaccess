<nav class=" navbar navbar-expand-lg">
    <div class="container px-0">
        <a class="navbar-brand" href="/"><img src="{{ asset('assets/images/oesximg/logo/logo.svg') }}" alt="" style="height: 3rem;">
            <small class="d-none d-sm-inline-block fs-6 text-secondary">| ONLINE ENROLLMENT SYSTEM</small>
        </a>
        

        <!--Navbar nav -->
        <div class="ms-auto  align-items-center d-flex">
            <a href="#" class="form-check form-switch theme-switch btn btn-light btn-icon rounded-circle ">
                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                <label class="form-check-label" for="flexSwitchCheckDefault"></label>

            </a>
            <ul class="navbar-nav navbar-right-wrap d-flex nav-top-wrap ms-2">


                <!-- List -->
                <li class="dropdown ms-2">
                    <a class="rounded-circle" href="#" role="button" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="avatar avatar-md avatar-indicators avatar-online">
                            @if (Auth::user())
                            <img src="{{ asset('assets/images/avatar/avatar.jpg') }}" class="rounded-circle" alt="avatar">
                            @elseif (Auth::guard('trainee')->user()->imagepath)
                            <img src="{{asset('storage/traineepic/'. Auth::guard('trainee')->user()->imagepath)}}"  class="rounded-circle" alt="avatar">
                            @else
                            <img src="{{ asset('assets/images/avatar/avatar.jpg') }}" class="rounded-circle" alt="avatar">
                            @endif
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser">
                        <div class="dropdown-item">
                            <div class="d-flex">
                                <div class="avatar avatar-md avatar-indicators avatar-online">
                                    @if (Auth::user())
                                    <img src="{{ asset('assets/images/avatar/avatar.jpg') }}" class="rounded-circle" alt="avatar">
                                    @elseif (Auth::guard('trainee')->user()->imagepath)
                                    <img src="{{asset('storage/traineepic/'. Auth::guard('trainee')->user()->imagepath)}}"  class="rounded-circle" alt="avatar">
                                    @else
                                    <img src="{{ asset('assets/images/avatar/avatar.jpg') }}" class="rounded-circle" alt="avatar">
                                    @endif
                                </div>
                                <div class="ms-3 lh-1">
                                    @if (Auth::user())
                                    <h5 class="mb-1">{{ Auth::user()->f_name }} {{ Auth::user()->l_name }}</h5>
                                    <p class="mb-0 text-muted">{{ Auth::user()->email }}</p>
                                    @else
                                    <h5 class="mb-1">{{ Auth::guard('trainee')->user()->f_name }}
                                        {{ Auth::guard('trainee')->user()->l_name }}
                                    </h5>
                                    <p class="mb-0 text-muted">{{ Auth::guard('trainee')->user()->email }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <ul class="list-unstyled">
                            <li>
                                <a class="dropdown-item" href="{{route('t.editprofile')}}">
                                    <i class="fe fe-user me-2"></i> Profile
                                </a>
                            </li>
                        </ul>
                        <div class="dropdown-divider"></div>
                        <ul class="list-unstyled">
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();
                                    document.cookie = 'data_privacy_accepted=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;'">
                                    <i class="fe fe-power me-2"></i>Sign Out
                                </a>
                                <form id="logout-form" method="POST" action="{{ route('logout') }}">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
        <!-- Button -->
        <button class="navbar-toggler collapsed ms-2 " type="button" data-bs-toggle="collapse" data-bs-target="#navbar-default" aria-controls="navbar-default" aria-expanded="false" aria-label="Toggle navigation">
            <span class="icon-bar top-bar mt-0"></span>
            <span class="icon-bar middle-bar"></span>
            <span class="icon-bar bottom-bar"></span>
        </button>

    </div>
</nav>

@if (Session::get('otp_verified'))
<nav class="navbar navbar-expand-lg navbar-default py-0 py-lg-2 ">
    <div class="container px-0 ">


        <!-- Collapse -->


        <div class="collapse navbar-collapse" id="navbar-default">
            <ul class="navbar-nav">
                <li class="nav-item ">
                    <a class="nav-link" href="{{ route('t.dashboard') }}">
                        Dashboard
                    </a>

                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDashboard" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-bs-display="static">
                        Enrollment
                    </a>
                    <ul class="dropdown-menu dropdown-menu-arrow" aria-labelledby="navbarDashboard">
                        <li>
                            <a class="dropdown-item" href="{{ route('t.enroll-cards') }}">
                                Enroll now
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle"  id="navbarDashboard" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-bs-display="static">
                        Courses
                    </a>
                    <ul class="dropdown-menu dropdown-menu-arrow" aria-labelledby="navbarDashboard">
                        <li>
                            <a class="dropdown-item" href="{{ route('t.courses') }}">
                                My courses</a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDashboard" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-bs-display="static">
                        Certificates
                    </a>
                    <ul class="dropdown-menu dropdown-menu-arrow" aria-labelledby="navbarDashboard">
                        <li>
                            <a class="dropdown-item" href="{{route('t.certificates')}}">
                                Generate Certificates
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- <li class="nav-item dropdown">
                    <button type="button" class="nav-link" data-bs-toggle="modal" data-bs-target="#exampleModalCenter">
                        Barcode
                    </button>
                </li> --}}      

                {{-- <li class="nav-item dropdown">
                    <a class="nav-link" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fe fe-more-horizontal"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-md" aria-labelledby="navbarDropdown">
                        <div class="list-group">
                            <a class="list-group-item list-group-item-action border-0" href="../../../docs/changelog.html">
                                <div class="d-flex align-items-center">
                                    <i class="fe fe-layers fs-3 text-primary"></i>
                                    <div class="ms-3">
                                        <h5 class="mb-0">
                                            Changelog <span class="text-primary ms-1" id="changelog"></span>
                                        </h5>
                                        <p class="mb-0 fs-6">See what's new</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </li> --}}
            </ul>
        </div>

        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">My Barcode</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="text-center">
                            <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG(strval(Auth::guard('trainee')->user()->traineeid), 'C128') }}" height="80" alt="Barcode">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
</nav>
@endif