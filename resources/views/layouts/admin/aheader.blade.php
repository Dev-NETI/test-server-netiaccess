<nav class="navbar-vertical navbar bg-white">
    <style>
        #scrollbar {
            overflow-y: auto;
            overflow-x: hidden;

        }

        #scrollbar::-webkit-scrollbar {
            display: none;
        }
    </style>
    <div class="vh-100" data-simplebar id="scrollbar">
        <!-- Brand logo -->

        <a class="navbar-brand text-center" href="#">
            <img src="{{ asset('assets\images\oesximg\logo\logo-min.png') }}" style="width: 150px; height:auto;">
            <br>
            <br>
            <span class="text-muted mt-4" style="font-size: small;">Online Enrollment X</span>
        </a>


        @if (Auth::user()->u_type === 1)
        <div class="navbar-brand bg-dark text-center">
            <h4 class="text-white mt-3">ADMINISTRATOR PANEL</h4>
        </div>

        <!-- Navbar nav -->
        <ul class="navbar-nav flex-column small-text" id="sideNavbar">
            <!-- Nav item -->
            @can('authorizeAdminComponents', 125)
            <li class="nav-item">
                <a class="nav-link" href="{{ route('a.dashboard') }}">
                    <i class="nav-icon fe fe-home me-2"></i> Dashboard


                </a>
            </li>
            @endcan
            <!-- Nav item -->
            @can('authorizeAdminComponents', 3)
            <li class="nav-item">
                <a class="nav-link   collapsed " href="#" data-bs-toggle="collapse" data-bs-target="#navEnrollment"
                    aria-expanded="false" aria-controls="navEnrollment">
                    <i class="nav-icon fe fe-user me-2"></i> Enrollment
                </a>
                <div id="navEnrollment" class="collapse " data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        {{-- nested --}}
                        @can('authorizeAdminComponents', 4)
                        <x-sidebar-item label="Applications" route="a.confirmenroll">
                            <i class="bi bi-card-checklist me-2"></i>
                        </x-sidebar-item>
                        @endcan

                        @can('authorizeAdminComponents', 45)
                        <x-sidebar-item label="Remedial" route="a.remedial">
                            <i class="bi bi-clock-history me-2"></i>
                        </x-sidebar-item>
                        @endcan

                        @can('authorizeAdminComponents', 46)
                        <x-sidebar-item label="Logs" route="a.enrollog" />
                        @endcan
                    </ul>
                </div>
            </li>
            @endcan
            <!-- Nav item -->
            @can('authorizeAdminComponents', 5)
            <li class="nav-item">
                <a class="nav-link  collapsed " href="#" data-bs-toggle="collapse" data-bs-target="#navCourses"
                    aria-expanded="false" aria-controls="navCourses">
                    <i class="nav-icon fe fe-book me-2"></i> Courses
                </a>
                <div id="navCourses" class="collapse " data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        @can('authorizeAdminComponents', 6)
                        <x-sidebar-item label="All Courses" route="a.courses">
                            <i class="bi bi-file-earmark-medical-fill me-2"></i>
                        </x-sidebar-item>
                        @endcan
                    </ul>
                </div>
            </li>
            @endcan

            <!-- Nav item -->
            @can('authorizeAdminComponents', 7)
            <li class="nav-item">
                <a class="nav-link   collapsed " href="#" data-bs-toggle="collapse"
                    data-bs-target="#navInstructorManagement" aria-expanded="false"
                    aria-controls="navInstructorManagement">
                    <i class="nav-icon fe fe-user me-2"></i> Instructor Management
                </a>
                <div id="navInstructorManagement" class="collapse " data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        @can('authorizeAdminComponents', 8)
                        <x-sidebar-item label="Instructor List" route="a.instructor">
                            <i class="bi bi-person-lines-fill me-2"></i>
                        </x-sidebar-item>
                        @endcan
                    </ul>
                </div>
            </li>
            @endcan

            <!-- Nav item -->
            @can('authorizeAdminComponents', 9)
            <li class="nav-item">
                <a class="nav-link   collapsed " href="#" data-bs-toggle="collapse" data-bs-target="#navBilling"
                    aria-expanded="false" aria-controls="navBilling">
                    <i class="bi bi-receipt me-2"></i>Billing
                </a>
                <div id="navBilling" class="collapse " data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        @can('authorizeAdminComponents', 11)
                        <x-sidebar-item label="Dashboard" route="a.billing-monitoring">
                            <i class="bi bi-calendar2-check me-2"></i>
                        </x-sidebar-item>
                        @endcan
                        @can('authorizeAdminComponents', 10)
                        <x-sidebar-item label="Bank Accounts" route="a.bank-management">
                            <i class="bi bi-bank me-2"></i>
                        </x-sidebar-item>
                        @endcan
                        @can('authorizeAdminComponents', 13)
                        <x-sidebar-item label="Client Management" route="a.billing-pricematrix">
                            <i class="bi bi-person-lines-fill me-2"></i>
                        </x-sidebar-item>
                        @endcan
                        @can('authorizeAdminComponents', 94)
                        <li class="nav-item">
                            <a class="nav-link " href="{{ route('a.exchange-rate') }}">
                                Exchange Rate
                            </a>
                        </li>
                        @endcan
                        @can('authorizeAdminComponents', 86)
                        <x-sidebar-item label="Vessels" route="a.manage-vessel">
                            <i class="bi bi-train-freight-front-fill me-2"></i>
                        </x-sidebar-item>
                        @endcan
                        @can('authorizeAdminComponents', 12)
                        <x-sidebar-item label="Billing Drop Logs" route="a.billing-drop">
                            <i class="bi bi-sort-down-alt me-2"></i>
                        </x-sidebar-item>
                        @endcan
                        @can('authorizeAdminComponents', 141)
                        <x-sidebar-item label="Search Billing" route="a.search-billing">
                            <i class="bi bi-search me-2"></i>
                        </x-sidebar-item>
                        @endcan

                        @can('authorizeAdminComponents', 142)
                        <x-sidebar-item label="Archives" route="a.archive-billing">
                            <i class="bi bi-archive-fill me-2"></i>
                        </x-sidebar-item>
                        @endcan

                        @can('authorizeAdminComponents', 89)
                        <li class="nav-item">
                            <a class="nav-link " href="{{ route('a.billing-atd') }}">
                                ATD/SLAF
                            </a>
                        </li>
                        @endcan

                    </ul>
                </div>
            </li>
            @endcan

            @can('authorizeAdminComponents', 109)
            <li class="nav-item">
                <a class="nav-link   collapsed " href="#" data-bs-toggle="collapse" data-bs-target="#JISSBilling"
                    aria-expanded="false" aria-controls="JISSBilling">
                    <i class="bi bi-receipt me-2"></i>Billing (JISS)
                </a>
                <div id="JISSBilling" class="collapse " data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        @can('authorizeAdminComponents', 96)
                        <x-sidebar-item label="Dashboard" route="a.jiss-billing">
                            <i class="bi bi-calendar2-check me-2"></i>
                        </x-sidebar-item>
                        @endcan

                        @can('authorizeAdminComponents', 117)
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#navMenuLevelSecond"
                                aria-expanded="true" aria-controls="navMenuLevelSecond">
                                <i class="bi bi-gear-wide-connected me-2"></i>
                                Maintenance
                            </a>
                            <div id="navMenuLevelSecond" class="collapse" data-bs-parent="#navMenuLevel" style="">
                                <ul class="nav flex-column">
                                    @can('authorizeAdminComponents', 114)
                                    <li class="nav-item">
                                        <a class="nav-link " href="{{ route('a.jiss-company-maint') }}">
                                            <i class="bi bi-house-fill me-2"></i> Company</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link " href="{{ route('a.jiss-emailmaint') }}">
                                            <i class="bi bi-envelope-plus-fill me-2"></i> Emails Maint.</a>
                                    </li>
                                    @endcan
                                    @can('authorizeAdminComponents', 115)
                                    <li class="nav-item">

                                        <a class="nav-link " href="{{ route('a.jiss-course-maint') }}">
                                            <i class="bi bi-receipt-cutoff me-2"></i>
                                            Courses</a>
                                    </li>
                                    @endcan
                                    @can('authorizeAdminComponents', 116)
                                    <x-sidebar-item label="Price Matrix" route="a.jiss-price-matrix">
                                        <i class="bi bi-currency-dollar me-2"></i>
                                    </x-sidebar-item>
                                    @endcan
                                </ul>
                            </div>
                        </li>
                        @endcan
                    </ul>
                </div>
            </li>

            @endcan

            @can('authorizeAdminComponents', 14)
            <li class="nav-item">
                <a class="nav-link   collapsed " href="{{ route('a.report-dashboard') }}">
                    <i class="bi bi-file-bar-graph me-2"></i>Report
                </a>
            </li>
            @endcan

            @can('authorizeAdminComponents', 129)
            <x-sidebar-dropdown label="Payroll Module" id="glps">
                @can('authorizeAdminComponents', 130)
                <x-sidebar-item label="Biometrics" route="glps.biometric-index">
                    <i class="bi bi-fingerprint me-2"></i>
                </x-sidebar-item>
                @endcan
                @can('authorizeAdminComponents', 131)
                <x-sidebar-item label="Payroll" route="glps.payroll">
                    <i class="bi bi-file-post me-2"></i>
                </x-sidebar-item>
                @endcan
                @can('authorizeAdminComponents', 132)
                <x-sidebar-item label="Instructor Rate" route="glps.instructor-payroll">
                    <i class="bi bi-file-post me-2"></i>
                </x-sidebar-item>
                <x-sidebar-item label="Instructor Payroll" route="glps.instructor-details-payroll">
                    <i class="bi bi-currency-dollar me-2"></i>
                </x-sidebar-item>
                @endcan
                @can('authorizeAdminComponents', 133)
                <x-sidebar-item label="Rate Maintenance" route="glps.instructor-rate">
                    <i class="bi bi-gear-wide-connected me-2"></i>
                </x-sidebar-item>
                <x-sidebar-item label="Description" route="glps.instructor-description">
                    <i class="bi bi-gear-wide-connected me-2"></i>
                </x-sidebar-item>
                @endcan
            </x-sidebar-dropdown>
            @endcan

            @can('authorizeAdminComponents', 15)
            <!-- Nav item -->
            <li class="nav-item">
                <a class="nav-link   collapsed " href="#" data-bs-toggle="collapse" data-bs-target="#navPDE"
                    aria-expanded="false" aria-controls="navPDE">
                    <i class="bi bi-file-earmark-medical me-2"></i>PDE
                </a>
                <div id="navPDE" class="collapse " data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        @can('authorizeAdminComponents', 16)
                        <x-sidebar-item label="Request PDE" route="a.requestpde">
                            <i class="bi bi-patch-question-fill me-2"></i>
                        </x-sidebar-item>
                        @endcan
                    </ul>
                </div>
                <div id="navPDE" class="collapse " data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        @can('authorizeAdminComponents', 17)
                        <x-sidebar-item label="PDE Status" route="a.pdestatus">
                            <i class="bi bi-app-indicator me-2"></i>
                        </x-sidebar-item>
                        @endcan
                    </ul>
                </div>
                <div id="navPDE" class="collapse " data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        @can('authorizeAdminComponents', 18)
                        <x-sidebar-item label="PDE Report" route="a.pdereport">
                            <i class="bi bi-card-heading me-2"></i>
                        </x-sidebar-item>
                        @endcan
                    </ul>
                </div>
                <div id="navPDE" class="collapse " data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        @can('authorizeAdminComponents', 75)
                        <x-sidebar-item label="PDE Maintenance" route="a.pdemaintenance">
                            <i class="bi bi-gear-fill me-2"></i>
                        </x-sidebar-item>
                        @endcan
                    </ul>
                </div>
            </li>
            @endcan

            <!-- Nav item -->
            @can('authorizeAdminComponents', 19)
            <li class="nav-item">
                <a class="nav-link   collapsed " href="#" data-bs-toggle="collapse" data-bs-target="#navMaintenance"
                    aria-expanded="false" aria-controls="navMaintenance">
                    <i class="nav-icon fe fe-settings me-2"></i>Maintenance
                </a>
                <div id="navMaintenance" class="collapse " data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <!-- <li class="nav-item">
                                                                        <a class="nav-link " href="{{ route('a.maintenance') }}">
                                                                            Maintenance
                                                                        </a>
                                                                    </li> -->
                        @can('authorizeAdminComponents', 20)
                        <li class="nav-item">
                            <a class="nav-link " href="{{ route('a.announcement') }}">
                                Announcement
                            </a>
                        </li>
                        @endcan

                        @can('authorizeAdminComponents', 21)
                        <li class="nav-item">
                            <a class="nav-link " href="{{ route('a.faq') }}">
                                FAQ
                            </a>
                        </li>
                        @endcan

                        @can('authorizeAdminComponents', 22)
                        <x-sidebar-item label="Handout" route="a.handout">
                            <i class="bi bi-journal-bookmark-fill me-2"></i>
                        </x-sidebar-item>
                        @endcan

                        @can('authorizeAdminComponents', 23)
                        <x-sidebar-item label="Rank" route="a.rank">
                            <i class="bi bi-bookmark-star-fill me-2"></i>
                        </x-sidebar-item>
                        @endcan

                        @can('authorizeAdminComponents', 24)
                        <x-sidebar-item label="Roles" route="a.roles">
                            <i class="bi bi-book-half me-2"></i>
                        </x-sidebar-item>
                        @endcan

                        @can('authorizeAdminComponents', 25)
                        <x-sidebar-item label="Room" route="a.room">
                            <i class="bi bi-border-style me-2"></i>
                        </x-sidebar-item>
                        @endcan

                        @can('authorizeAdminComponents', 26)
                        <x-sidebar-item label="Course Department" route="a.coursedepartment">
                            <i class="bi bi-box me-2"></i>
                        </x-sidebar-item>
                        @endcan

                        @can('authorizeAdminComponents', 27)
                        <li class="nav-item">
                            <a class="nav-link " href="#">
                                Home Page
                            </a>
                        </li>
                        @endcan

                        @can('authorizeAdminComponents', 28)
                        <x-sidebar-item label="Company" route="a.company">
                            <i class="bi bi-bank me-2"></i>
                        </x-sidebar-item>
                        @endcan

                        @can('authorizeAdminComponents', 29)
                        <x-sidebar-item label="Certificate" route="a.certmain">
                            <i class="bi bi-badge-cc-fillm me-2"></i>
                        </x-sidebar-item>
                        @endcan

                    </ul>

                </div>
            </li>
            @endcan

            <!-- Nav item -->
            @can('authorizeAdminComponents', 30)
            <li class="nav-item">
                <a class="nav-link   collapsed " href="#" data-bs-toggle="collapse" data-bs-target="#navCommunications"
                    aria-expanded="false" aria-controls="navCommunications">
                    <i class="bi bi-chat-left-dots me-2"></i>Communication
                </a>
                <div id="navCommunications" class="collapse " data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        @can('authorizeAdminComponents', 31)
                        <li class="nav-item">
                            <a class="nav-link " href="{{ route('a.textblast') }}">
                                Text Blast
                            </a>
                        </li>
                        @endcan

                        @can('authorizeAdminComponents', 32)
                        <li class="nav-item">
                            <a class="nav-link " href="#">
                                Send email
                            </a>
                        </li>
                        @endcan

                        @can('authorizeAdminComponents', 33)
                        <li class="nav-item">
                            <a class="nav-link " href="{{ route('a.inquiries') }}">
                                Inquiries
                                <livewire:admin.widget.a-count-inquiries-component />
                            </a>
                        </li>
                        @endcan

                    </ul>
                </div>
            </li>
            @endcan

            <!-- Nav item -->
            @can('authorizeAdminComponents', 34)
            <li class="nav-item">
                <a class="nav-link  collapsed " href="#" data-bs-toggle="collapse" data-bs-target="#navTrainees"
                    aria-expanded="false" aria-controls="navTrainees">
                    <i class="bi bi-people me-2"></i>Trainees
                </a>
                <div id="navTrainees" class="collapse " data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        @can('authorizeAdminComponents', 35)
                        <x-sidebar-item label="Manage Trainess" route="a.trainee">
                            <i class="bi bi-person-fill me-2"></i>
                        </x-sidebar-item>
                        @endcan

                        {{-- @can('', 3) --}}
                        {{-- <li class="nav-item">
                            <a class="nav-link " href="#">
                                Trainees Inquiries
                            </a>
                        </li> --}}
                        {{-- @endcan --}}

                        @can('authorizeAdminComponents', 107)

                        <x-sidebar-item label="Bus Monitoring" route="a.bus-monitoring">
                            <i class="bi bi-car-front me-2"></i>
                        </x-sidebar-item>
                        @endcan

                        @can('authorizeAdminComponents', 108)
                        <x-sidebar-item label="Meal Monitoring" route="a.meal-monitoring">
                            <i class="bi bi-egg-fried me-2"></i>
                        </x-sidebar-item>
                        @endcan

                    </ul>
                </div>
            </li>
            @endcan

            <!-- Nav item -->
            @can('authorizeAdminComponents', 37)
            <li class="nav-item">
                <a class="nav-link   collapsed " href="#" data-bs-toggle="collapse" data-bs-target="#navAdmin"
                    aria-expanded="false" aria-controls="navAdmin">
                    <i class="nav-icon fe fe-lock me-2"></i>Admin Accounts
                </a>
                <div id="navAdmin" class="collapse " data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        @can('authorizeAdminComponents', 38)
                        <x-sidebar-item label="Manage Admins" route="a.adminusers">
                            <i class="bi bi-person-fill me-2"></i>
                        </x-sidebar-item>
                        @endcan
                    </ul>
                </div>
            </li>
            @endcan

            <!-- Nav item -->
            @can('authorizeAdminComponents', 39)
            <li class="nav-item">
                <a class="nav-link   collapsed " href="#" data-bs-toggle="collapse"
                    data-bs-target="#navTrainingCalendar" aria-expanded="false" aria-controls="navTrainingCalendar">
                    <i class="bi bi-calendar-week me-2"></i> Training Calendar
                </a>
                <div id="navTrainingCalendar" class="collapse " data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        @can('authorizeAdminComponents', 40)
                        <x-sidebar-item label="Training Schedule" route="a.trainingcalendar">
                            <i class="bi bi-calendar-week-fill me-2"></i>
                        </x-sidebar-item>
                        @endcan

                        @can('authorizeAdminComponents', 41)
                        <x-sidebar-item label="Special Class" route="a.specialcalendar">
                            <i class="bi bi-calendar3-event-fill me-2"></i>
                        </x-sidebar-item>
                        @endcan

                    </ul>
                </div>
            </li>
            @endcan


            @can('authorizeAdminComponents', 47)
            <!-- Nav item -->
            <li class="nav-item">
                <a class="nav-link  collapsed " href="#" data-bs-toggle="collapse" data-bs-target="#navMenuLevel"
                    aria-expanded="false" aria-controls="navMenuLevel">
                    <i class="nav-icon fe fe-book-open me-2"></i> Manage Reservations
                </a>
                <div id="navMenuLevel" class="collapse " data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            @can('authorizeAdminComponents', 48)
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                                data-bs-target="#navMenuLevelSecond" aria-expanded="false"
                                aria-controls="navMenuLevelSecond">
                                <i class="bi bi-bookmark-check-fill me-2"></i>
                                Reservations
                            </a>
                            @endcan
                            <div id="navMenuLevelSecond" class="collapse" data-bs-parent="#navMenuLevel">
                                <ul class="nav flex-column">

                                    @can('authorizeAdminComponents', 48)
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('d.reserve') }}"><i
                                                class="bi bi-check-all me-2"></i>Assign Room</a>
                                    </li>
                                    @endcan

                                    @can('authorizeAdminComponents', 48)
                                    <li class="nav-item">
                                        <a class="nav-link " href="{{ route('d.checkin') }}"><i
                                                class="bi bi-card-checklist me-2"></i>Reserved</a>
                                    </li>
                                    @endcan

                                    @can('authorizeAdminComponents', 49)
                                    <li class="nav-item">
                                        <a class="nav-link " href="{{ route('d.checkout') }}"><i
                                                class="bi bi-bookmark-check-fill me-2"></i>Check In</a>
                                    </li>
                                    @endcan

                                    @can('authorizeAdminComponents', 50)
                                    <li class="nav-item">
                                        <a class="nav-link " href="{{ route('d.noshow') }}"><i
                                                class="bi bi-eye-slash-fill me-2"></i>No Show</a>
                                    </li>
                                    @endcan

                                    {{-- @can('authorizeAdminComponents', 48)
                                    <li class="nav-item">
                                        <a class="nav-link " href="{{ route('d.emcheckin') }}">Emergency Check In</a>
                        </li>
                        @endcan --}}
            </li>
        </ul>
    </div>
    </li>
    <li class="nav-item">
        @can('authorizeAdminComponents', 113)
        <a class="nav-link  collapsed  " href="#" data-bs-toggle="collapse" data-bs-target="#navMenuLevelThree"
            aria-expanded="false" aria-controls="navMenuLevelThree">
            <i class="bi bi-body-text me-2"></i>
            Reports
        </a>
        @endcan
        <div id="navMenuLevelThree" class="collapse" data-bs-parent="#navMenuLevel">
            <ul class="nav flex-column">

                @can('authorizeAdminComponents', 51)
                <x-sidebar-item label="Daily-Weekly" route="d.reportsdailyweekly">
                    <i class="bi bi-file-earmark-text-fill me-2"></i>
                </x-sidebar-item>
                @endcan

                @can('authorizeAdminComponents', 52)
                <x-sidebar-item label="Waiver" route="d.waiverammenitiesreport">
                    <i class="bi bi-file-earmark-text-fill me-2"></i>
                </x-sidebar-item>
                @endcan

                @can('authorizeAdminComponents', 53)
                <x-sidebar-item label="Checkout List" route="d.checkoutlist">
                    <i class="bi bi-arrow-right-square-fill me-2"></i>
                </x-sidebar-item>
                @endcan

                @can('authorizeAdminComponents', 87)
                <x-sidebar-item label="View Trainee" route="d.viewtrainee">
                    <i class="bi bi-eye-fill me-2"></i>
                </x-sidebar-item>
                @endcan

                @can('authorizeAdminComponents', 92)
                <x-sidebar-item label="Event Logs" route="d.dormitory-events">
                    <i class="bi bi-calendar-fill me-2"></i>
                </x-sidebar-item>
                @endcan
            </ul>
        </div>
    </li>
    <li class="nav-item">
        @can('authorizeAdminComponents', 54)
        <a class="nav-link  collapsed  " href="#" data-bs-toggle="collapse"
            data-bs-target="#navMenuLevelThreeMaint" aria-expanded="false" aria-controls="navMenuLevelThree"><i
                class="bi bi-gear-fill me-2"></i>
            Maintenance
        </a>
        @endcan
        <div id="navMenuLevelThreeMaint" class="collapse" data-bs-parent="#navMenuLevel">
            <ul class="nav flex-column">

                @can('authorizeAdminComponents', 54)
                <x-sidebar-item label="R-Price Maint." route="d.roompricemaintenance">
                    <i class="bi bi-gear-fill me-2"></i>
                </x-sidebar-item>

                <x-sidebar-item label="R-Cap. Maint." route="d.roomcapacity">
                    <i class="bi bi-gear-fill me-2"></i>
                </x-sidebar-item>
                @endcan

            </ul>
        </div>
    </li>
    {{-- @can('authorizeAdminComponents', 53)
            <li class="nav-item">
                <a class="nav-link " href="{{ route('d.checkoutlist') }}">
    Checked Out List
    </a>
    </li>
    @endcan

    @can('authorizeAdminComponents', 87)
    <li class="nav-item">
        <a class="nav-link " href="{{ route('d.viewtrainee') }}">
            View Trainee
        </a>
    </li>
    @endcan

    @can('authorizeAdminComponents', 54)
    <li class="nav-item">
        <a class="nav-link " href="{{ route('d.roompricemaintenance') }}">
            Room Price Maintenance
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link " href="{{ route('d.roomcapacity') }}">
            Room Capacity Maintenance
        </a>
    </li>
    @endcan --}}

    </ul>
    </div>
    </li>
    @endcan


    @can('authorizeAdminComponents', 43)
    <li class="nav-item">
        <a class="nav-link   collapsed " href="#" data-bs-toggle="collapse" data-bs-target="#navNotification"
            aria-expanded="false" aria-controls="navNotification">
            <i class="bi bi-bell me-2"></i>Notification History
        </a>
        <div id="navNotification" class="collapse " data-bs-parent="#sideNavbar">
            <ul class="nav flex-column">
                @can('authorizeAdminComponents', 42)
                <li class="nav-item">
                    <a class="nav-link " href="{{ route('a.notification-history') }}">
                        Event Logs
                    </a>
                </li>
                @endcan

            </ul>
        </div>
    </li>
    @endcan

    @can('authorizeAdminComponents', 110)
    <li class="nav-item">
        <a class="nav-link  collapsed " href="#" data-bs-toggle="collapse" data-bs-target="#CertificateApproval"
            aria-expanded="false" aria-controls="CertificateApproval">
            <i class="bi bi-file-bar-graph me-2"></i>Certificate Approval
        </a>
        <div id="CertificateApproval" class="collapse " data-bs-parent="#sideNavbar">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('a.nmc-cert-approval', ['course_type_id' => 3]) }}">
                        NMC/NMCR
                        <livewire:admin.widget.a-count-certificate-approval-component :course_type_id="3" />
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('a.nmc-cert-approval', ['course_type_id' => 2]) }}">
                        UPGRADING
                        <livewire:admin.widget.a-count-certificate-approval-component :course_type_id="2" />
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('a.nmc-cert-approval', ['course_type_id' => 7]) }}">
                        PJMCC
                        <livewire:admin.widget.a-count-certificate-approval-component :course_type_id="7" />
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('a.nmc-cert-approval', ['course_type_id' => 1]) }}">
                        MANDATORY
                        <livewire:admin.widget.a-count-certificate-approval-component :course_type_id="1" />
                    </a>
                </li>
            </ul>
        </div>
    </li>
    @endcan

    @can('authorizeAdminComponents', 124)
    <li class="nav-item">
        <a class="nav-link " href="{{ route('c.manageins') }}">
            <i class="nav-icon fe fe-user me-2"></i> Manage Instructor
        </a>
    </li>
    @endcan

    @can('authorizeAdminComponents', 143)
    <li class="nav-item">
        <a class="nav-link  collapsed " href="#" data-bs-toggle="collapse" data-bs-target="#attendance"
            aria-expanded="false" aria-controls="attendance">
            <i class="nav-icon fe fe-book-open me-2"></i> Attendance
        </a>
        <div id="attendance" class="collapse " data-bs-parent="#sideNavbar">
            <ul class="nav flex-column">

                @can('authorizeAdminComponents', 143)
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('a.failureapproval') }}">
                        <i class="bi bi-info-circle-fill me-2"></i> Failure Approval
                    </a>
                </li>
                @endcan

            </ul>
        </div>
    </li>
    @endcan

    <li class="nav-item">
        <div class="navbar-heading">Documentation</div>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{route('a.changelog') }}">
            <i class="nav-icon fe fe-git-pull-request me-2"></i> Changelog <span class="text-primary ms-1"
                id="">v1.1.2</span>
        </a>
    </li>
    <!-- Nav item -->
    </ul>
    @endif

    @if (Auth::user()->u_type === 3)
    <div class="navbar-brand bg-dark text-center">
        <h4 class="text-white mt-3">CLIENT PANEL</h4>
    </div>
    <!-- Navbar nav -->
    <ul class="navbar-nav flex-column small-text" id="sideNavbar">

        @if (Auth::user()->user_id != 422 && Auth::user()->user_id != 423)
        <!-- Nav item -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('c.dashboard') }}">
                <i class="nav-icon fe fe-home me-2"></i> Dashboard
            </a>
        </li>
        <!-- Nav item -->
        @can('authorizeAdminComponents', 134)
        <li class="nav-item">
            <a class="nav-link   collapsed " href="#" data-bs-toggle="collapse" data-bs-target="#navEnrollment"
                aria-expanded="false" aria-controls="navEnrollment">
                <i class="nav-icon fe fe-user me-2"></i> Enrollment
            </a>
            <div id="navEnrollment" class="collapse " data-bs-parent="#sideNavbar">
                <ul class="nav flex-column">
                    <li class="nav-item ">
                        <a class="nav-link " href="{{ route('c.confirm-enroll') }}">
                            Enrolled Trainees
                        </a>
                        <a class="nav-link " href="{{ route('c.view-trainees') }}">
                            Crew List
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        @endcan

        @can('authorizeAdminComponents', 155)
        <li class="nav-item">
            <a class="nav-link" href="{{ route('c.certificate-report') }}">
                <i class="nav-icon fe fe-file-text me-2"></i>Certificate <span class="badge bg-success ms-2">New feature</span>
            </a>
        </li>
        @endcan

        @can('authorizeAdminComponents', 135)
        <li class="nav-item">
            <a class="nav-link" href="{{ route('c.handout') }}">
                <i class="nav-icon fe fe-box me-2"></i> Handout Password
            </a>
        </li>
        @endcan
        @endif

        <!-- Nav item -->
        @can('authorizeAdminComponents', 136)
        <li class="nav-item">
            <a class="nav-link" href="{{ route('c.client-billing-monitoring') }}">
                <i class="bi bi-receipt me-2"></i> Billing
            </a>
        </li>
        @endcan

        @if (Auth::user()->user_id != 422 && Auth::user()->user_id != 423)
        @can('authorizeAdminComponents', 137)
        <li class="nav-item">
            <a class="nav-link   collapsed " href="#" data-bs-toggle="collapse" data-bs-target="#navPDE"
                aria-expanded="false" aria-controls="navPDE">
                <i class="bi bi-file-earmark-medical me-2"></i>PDE
            </a>
            <div id="navPDE" class="collapse " data-bs-parent="#sideNavbar">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('c.requestpde') }}">
                            Request PDE
                        </a>
                    </li>

                </ul>
            </div>
            <div id="navPDE" class="collapse " data-bs-parent="#sideNavbar">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('c.pdestatus') }}">
                            PDE Status
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        @endcan

        <!-- Nav item -->

        @can('authorizeAdminComponents', 138)
        <li class="nav-item">
            {{-- {{dd(Auth::user())}} --}}
            <a class="nav-link " href="{{ route('c.edit-company', Auth::user()->company_id) }}">
                <i class="nav-icon fe fe-info me-2"></i> Edit Company Profile
            </a>
        </li>
        @endcan
        @endif

    </ul>
    @endif


    @if (Auth::user()->u_type === 2)
    <div class="navbar-brand bg-dark text-center">
        <h4 class="text-white mt-3">INSTRUCTOR PANEL</h4>
    </div>
    <!-- Navbar nav -->
    <ul class="navbar-nav flex-column small-text" id="sideNavbar">
        <!-- Nav item -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('i.dashboard') }}">
                <i class="nav-icon fe fe-home me-2"></i> Dashboard
            </a>
        </li>
        {{-- <li class="nav-item">
            <a class="nav-link" href="{{route('i.edit-instructor', ['hashid' => Auth::user()->hash_id])}}">
        <i class="nav-icon fe fe-user me-2"></i> My Profile
        </a>
        </li> --}}
        <li class="nav-item">
            <a class="nav-link" href="{{ route('i.pde-dashboard') }}">
                <i class="nav-icon bi-file-earmark-zip-fill me-2"></i> Pde Dashboard
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link  collapsed " href="#" data-bs-toggle="collapse" data-bs-target="#navMenuLevel"
                aria-expanded="false" aria-controls="navMenuLevel">
                <i class="nav-icon fe fe-book-open me-2"></i> Attendance
            </a>
            <div id="navMenuLevel" class="collapse " data-bs-parent="#sideNavbar">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('i.attendance') }}">
                            <i class="bi bi-person-lines-fill me-2"></i> Attendance Logs
                        </a>
                    </li>

                    @can('authorizeAdminComponents', 139)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('i.otfiling') }}">
                            <i class="bi bi-card-heading me-2"></i> File Overtime
                        </a>
                    </li>
                    @endcan

                    @can('authorizeAdminComponents', 143)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('i.failureapproval') }}">
                            <i class="bi bi-info-circle-fill me-2"></i> Failure Approval
                        </a>
                    </li>
                    @endcan

                    @can('authorizeAdminComponents', 140)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('i.otapproval') }}">
                            <i class="bi  bi-patch-check-fill me-2"></i> Approval of Overtime
                        </a>
                    </li>
                    @endcan
                </ul>
            </div>
        </li>
    </ul>
    @endif

    @if (Auth::user()->u_type === 4)
    <div class="navbar-brand bg-dark text-center">
        <h4 class="text-white mt-3">TECHNICAL PANEL</h4>
    </div>
    <!-- Navbar nav -->
    <ul class="navbar-nav flex-column small-text" id="sideNavbar">
        <!-- Nav item -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('te.dashboard') }}">
                <i class="nav-icon fe fe-home me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('te.manage-trainees') }}">
                <i class="bi bi-people me-2"></i> Manage Trainees
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('te.report-batch') }}">
                <i class="bi bi-file-earmark-ruled-fill me-2"></i> Course Schedule
            </a>
        </li>
    </ul>
    @endif

    @if (Auth::user()->u_type === 5)
    <div class="navbar-brand bg-dark text-center">
        <h4 class="text-white mt-3">NON TECHNICAL PANEL</h4>
    </div>
    <!-- Navbar nav -->
    <ul class="navbar-nav flex-column small-text" id="sideNavbar">
        <!-- Nav item -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('te.dashboard') }}">
                <i class="nav-icon fe fe-home me-2"></i> Dashboard
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link   collapsed " href="#" data-bs-toggle="collapse" data-bs-target="#navPDE"
                aria-expanded="false" aria-controls="navPDE">
                <i class="bi bi-file-earmark-medical me-2"></i>PDE
            </a>
            <div id="navPDE" class="collapse " data-bs-parent="#sideNavbar">
                <ul class="nav flex-column">

                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('nte.requestpde') }}">
                            Request PDE
                        </a>
                    </li>

                </ul>
            </div>
            <div id="navPDE" class="collapse " data-bs-parent="#sideNavbar">
                <ul class="nav flex-column">

                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('nte.pdestatus') }}">
                            PDE Status
                        </a>
                    </li>

                </ul>
            </div>
        </li>
    </ul>
    @endif

    </div>
</nav>