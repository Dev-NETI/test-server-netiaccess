<main>
    <section class="py-md-20 py-12 position-relative overflow-hidden" style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('{{ asset('assets/images/oesximg/landing-2.1-bg.jpg') }}'); background-size: cover; background-position: center;">
        <div class="gradient-background1" style="z-index: -1;"></div> <!-- Gradient background here -->
        {{-- <span wire:loading>
            <livewire:components.loading-screen-component />
        </span> --}}

       
      
        <div class="container">
            <!-- Hero Section -->
           
            <div class="row align-items-center ">
                <div class="col-xl-6 col-lg-6 col-md-12">
                    <div class="mb-4 mb-xl-0 text-center text-md-start">
                        <!-- Caption -->
                        <h1 class="display-2 fw-bold mb-3 ls-sm text-white ">Trainees' Portal </h1>
                        <p class="mb-4 lead text-white">
                            Welcome back! Log in to your account to access your training materials and resources.
                        </p>
                
                        <a href="{{ route('registration') }}" class="btn btn-info">Don't have an account yet? Register here.</a>
                    </div>
                </div>
                <div class="offset-xl-1 col-xl-5 col-lg-6 col-md-12">
                    <!-- Card -->
                    <div class="card smooth-shadow-md" style="z-index: 1;">
                        <!-- Card body -->
                        <div class="card-body p-6">
                            <div class="mb-4">
                                
                                <div class="row g-2">
                                <img src="{{ asset('assets/images/brand/logo/oesx-neti.png') }}"
                                class="" alt="logo">
                            </div>
                                
                                        {{-- <div class="mt-3 mb-5 row g-2">
                                            <!-- btn group -->
                                            <div class="btn-group mb-2 mb-md-0 col-lg-4" role="group" aria-label="socialButton">
                                                <button type="button" class="btn btn-light shadow-sm"><i
                                                        class="mdi mdi-google me-2 text-danger"></i>Google</button>
                                            </div>
                                            <!-- btn group -->
                                            <div class="btn-group mb-2 mb-md-0 col-lg-4" role="group" aria-label="socialButton">
                                                <button type="button" class="btn btn-light shadow-sm"><i
                                                        class="mdi mdi-twitter text-info me-2"></i>Twitter</button>
                                            </div>
                                            <!-- btn group -->
                                            <div class="btn-group col-lg-4" role="group" aria-label="socialButton">
                                                <button type="button" class="btn btn-light shadow-sm"><i
                                                        class="mdi mdi-facebook text-primary me-2"></i>Facebook</button>
                                            </div>
                                        </div> --}}
                            </div>
                            <div class="mb-4">
                                <div class="border-bottom"></div>
                                <div class="text-center mt-n2  lh-1">
                                    <span class="bg-white px-2 fs-6 rounded"></span>
                                </div>
                                <h1 class="mb-4 lh-1 fw-bold h2 mt-2">LOGIN YOUR ACCOUNT</h1>
                            </div>
                            <!-- Form -->

                            @if (session()->has('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                            @endif

                            @if (session()->has('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('error') }}
                            </div>
                            @endif

                            @if ($timer_message == 1)
                            <div class="alert alert-danger" role="alert" wire:poll.keep-alive >
                                Your account is locked out! You may login after {{$this->remaining_time}} seconds ! 
                            </div>
                            @endif

                            <form wire:submit.prevent="attemptLogin">
                                @csrf
                                <!-- Username -->
                                <div class="mb-3">
                                    <label for="email" class="form-label visually-hidden">Email</label>
                                    <input type="email" id="email" class="form-control" wire:model.defer="email"
                                    placeholder="Email"  autofocus autocomplete="username">
                                    @error('email') <span class="text-danger">{{$message}}</span> @enderror
                                </div>
                                <!-- Password -->
                                <div class="mb-3">
                                    <label for="password" class="form-label visually-hidden">Password</label>
                                    <input type="password" id="password" class="form-control"
                                        wire:model.defer="password" placeholder="Password" 
                                        autocomplete="current-password">
                                    @error('password') <span class="text-danger">{{$message}}</span> @enderror
                                </div>
                                 <!-- Checkbox -->
                                 <div class="d-lg-flex justify-content-between align-items-center mb-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="rememberme" wire:model="remember">
                                        <label class="form-check-label" for="rememberme">Remember me</label>
                                    </div>
                                    <div>
                                        <a href="{{route('t.forget-password')}}">Forgot Password?</a>
                                    </div>
                                </div>
                                
                                <div class="d-grid">
                                    <button class="btn btn-primary">{{ __('Log in') }}</button>
                                </div>
                                <!-- Button -->
                                {{-- <div class="d-grid">
                                    <button type="submit"   
                                    data-sitekey="{{env('CAPTCHA_SITE_KEY')}}"
                                    data-callback='handle'
                                    data-action='submit'
                                    class="g-recaptcha btn btn-primary"  >{{ __('Log in') }}</button>
                                </div> --}}
                              
                                
                            </form>
                        </div>
                        <!-- Card Footer -->
                        <div class="card-footer px-6 py-4">
                           
                        </div>
                    </div>
                    <!-- Pattern -->
                    <div class="position-relative">
                        <div
                            class="position-absolute bottom-0 end-0 me-md-n3 mb-md-n6 me-lg-n4 mb-lg-n4 me-xl-n6 mb-xl-n8 d-none d-md-block ">
                            <img src="../assets/images/pattern/dots-pattern.svg" alt="" class="opacity-25">
                        </div>
                    </div>
                </div>
            </div>
           
        </div>
    </section>


    {{-- <script src="https://www.google.com/recaptcha/api.js?render={{env('CAPTCHA_SITE_KEY')}}"></script>
    <script>
        function handle(e) {
            grecaptcha.ready(function () {
                grecaptcha.execute('{{env('CAPTCHA_SITE_KEY')}}', {action: 'submit'})
                    .then(function (token) {
                        @this.set('captcha', token);
                    });
            })
        }
    </script> --}}
</main>
