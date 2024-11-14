<div class="header">
    <!-- navbar -->
    <nav class="navbar-default navbar navbar-expand-lg">
        <a id="nav-toggle" href="#">
            <i class="fe fe-menu"></i>
        </a>

        <!--Navbar nav -->
        <div class="ms-auto d-flex">
            <ul class="navbar-nav navbar-right-wrap ms-2 d-flex nav-top-wrap border-top-1">
            {{-- <livewire:chat.chat-component /> --}}
               <livewire:notification.notification-component wire:poll.750ms /> 
                <!-- List -->
                <li class="dropdown ms-2">
                    <a class="rounded-circle" href="#" role="button" id="dropdownUser" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <div class="avatar avatar-md avatar-indicators avatar-online">
                            @if (Auth::user()->imagepath == NULL || Auth::user()->imagepath == "")
                                <img alt="avatar" src="{{ asset('assets/images/avatar/avatar.jpg') }}"
                                class="rounded-circle">
                            @else
                                <img alt="avatar" src="{{asset('storage/useradminpic/'.Auth::user()->imagepath)}}"
                                class="rounded-circle">
                            @endif
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser">
                        <div class="dropdown-item">
                            <div class="d-flex">
                                <div class="avatar avatar-md avatar-indicators avatar-online">
                                    @if (Auth::user()->imagepath == NULL || Auth::user()->imagepath == "")
                                        <img alt="avatar" src="{{ asset('assets/images/avatar/avatar.jpg') }}"
                                        class="rounded-circle">
                                    @else
                                        <img alt="avatar" src="{{asset('storage/useradminpic/'.Auth::user()->imagepath)}}"
                                        class="rounded-circle">
                                    @endif
                                </div>
                                <div class="ms-3 lh-1">
                                    <h5 class="mb-1">{{ Auth::user()->formal_name() }}</h5>
                                    <p class="mb-0 text-muted">{{ Auth::user()->email }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <ul class="list-unstyled">
                            <li>
                                <a class="dropdown-item" href="{{ route('all.edit-profile') }}">
                                    <i class="bi bi-person-lines-fill me-2"></i> Edit Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); 
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
    </nav>
</div>
