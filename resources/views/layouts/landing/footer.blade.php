<footer class="pt-5 footer bg-white">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 col-12 text-center text-sm-start">
                <div class="mb-4 text-center">
                    {{-- <img src="{{asset('assets/images/brand/logo/oesx-neti.png')}}" alt="OESX Logo" height="80" class="logo-inverse"> --}}
                    <img src="{{ asset('assets/images/oesximg/logo/logo-min.svg') }}" class="img-responsive" width="auto"
                        height="50px">
                    <div class="mt-2 text-center">
                        <p style="text-align: justify;">NYK-FIL Maritime E-Training, Inc. (NETI) is the training arm of
                            NYK-Fil Ship Management, Inc. An ISO 9001:2015 certified company, it is engaged in maritime
                            training using state-of-the-art equipment for marine officers and ratings (non-officers) and
                            develops customized training courses designed to meet the specific requirements of
                            Principals.</p>
                        <div class="fs-4 mt-4">
                            <a href="https://www.facebook.com/NETICertified/"
                                class="mdi mdi-facebook fs-4 text-muted me-2"></a>
                            <!-- <a href="#" class="mdi mdi-twitter text-muted me-2"></a>
                            <a href="#" class="mdi mdi-instagram text-muted "></a> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="offset-lg-1 col-lg-2 col-md-3 col-6">
                <div class="mb-4">
                    <h3 class="fw-bold mb-3">Company</h3>
                    <ul class="list-unstyled nav nav-footer flex-column nav-x-0">
                        <li><a href="{{ route('dataprivacy') }}" class="nav-link">Data Privacy</a></li>
                        <li><a href="{{ route('courseslist', ['hashid' => '4e07408562bedb8b60ce05c1decfe3ad16b72230967de01f640b7e4729b49fce']) }}"
                                class="nav-link">Courses</a></li>
                        <li><a href="{{ route('contact') }}" class="nav-link">Contact us</a></li>
                        <li><a href="{{ route('faq') }}" class="nav-link">FAQ</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-6">
                <div class="mb-4">
                    <h3 class="fw-bold mb-3">Shortcut Links</h3>
                    <ul class="list-unstyled nav nav-footer flex-column nav-x-0">
                        <li><a href="{{ route('t.login') }}" class="nav-link">Go to Trainees' Portal</a></li>
                        <li><a href="{{ route('registration') }}" class="nav-link">Register an Account</a></li>
                        <li><a href="{{ route('request-access') }}" class="nav-link">Request Access Handout</a></li>
                        <li><a id="loginemp" href="{{ route('login') }}" class="nav-link">Go to Employees' Portal</a></li>
                    </ul>

                </div>
            </div>
            <div class="col-lg-3 col-md-12  ">
                <div class="mb-4">
                    <h3 class="fw-bold mb-3">Get in touch</h3>
                    <b><i>
                            <p>Knowledge Avenue, Carmeltown, Canlubang, Calamba City 4037, Laguna Philippines</p>
                        </i></b>
                    <p class="mb-1">Registrar: <b> registrar@neti.com.ph</b></p>
                    <p>Phone: <span class="text-dark fw-semibold">(049) 508-8600</span></p>
                </div>
            </div>
        </div>

        <x-software-version />

    </div>
</footer>
