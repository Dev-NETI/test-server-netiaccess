<main>
    <section class="py-md-20 py-12 position-relative overflow-hidden" style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('{{ asset('assets/images/oesximg/landing-2.1-bg.jpg') }}'); background-size: cover; background-position: center;">
        <div class="gradient-background1" style="z-index: -1;"></div> <!-- Gradient background here -->
        <span wire:loading>
            <livewire:components.loading-screen-component />
        </span>



        <div class="container">
            <!-- Hero Section -->

            <div class="row align-items-center ">
                <div class="col-xl-6 col-lg-6 col-md-12">
                    <div class="mb-4 mb-xl-0 text-center text-md-start">
                        <!-- Caption -->
                        <h1 class="display-2 fw-bold mb-3 ls-sm text-white ">Trainee's Portal </h1>
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
                                    <img src="{{ asset('assets/images/brand/logo/oesx-neti.png') }}" class="" alt="logo">
                                </div>

                            </div>
                            <div class="mb-4">
                                <div class="border-bottom"></div>
                                <div class="text-center mt-n2  lh-1">
                                    <span class="bg-white px-2 fs-6 rounded"></span>
                                </div>
                                <h1 class="mb-4 lh-1 fw-bold h2 mt-2">FORGET PASSWORD</h1>
                                <label>Enter the email address associated with your account. We'll send you a password reset link to regain access.</label>
                            </div>

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

                            <form wire:submit.prevent="sendResetLink">
                                @csrf
                                <!-- Username -->
                                <div class="mb-3">
                                    <label for="email" class="form-label visually-hidden">Email</label>
                                    <input type="email" id="email" class="form-control" wire:model.defer="email" placeholder="Email" required autofocus autocomplete="username">
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">SEND VERIFICATION EMAIL LINK</button>
                                </div>
                            </form>
                        </div>
                        <!-- Card Footer -->
                        <div class="card-footer px-6 py-4">
                        </div>
                    </div>
                    <!-- Pattern -->
                    <div class="position-relative">
                        <div class="position-absolute bottom-0 end-0 me-md-n3 mb-md-n6 me-lg-n4 mb-lg-n4 me-xl-n6 mb-xl-n8 d-none d-md-block ">
                            <img src="../assets/images/pattern/dots-pattern.svg" alt="" class="opacity-25">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>