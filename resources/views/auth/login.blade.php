<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('layouts.landing.head')
    @include('layouts.libraries.libraries')
    <style>
        body {
            /* background-image: url("{{asset('assets/images/oesximg/card.jpg')}}"); */
            background-size: cover;
            background-repeat: no-repeat;
        }
    </style>
</head>

<body>
    <main>

        <section class="container d-flex flex-column">
            <div class="row align-items-center justify-content-center g-0 min-vh-100">
                <span wire:loading>
                    <livewire:components.loading-screen-component />
                </span>
                <div class="col-lg-5 col-md-8 py-8 py-xl-0">
                    <!-- Card -->
                    <div class="card shadow ">
                        <!-- Card body -->
                        <div class="card-body p-6">
                            <div class="mb-4">
                                <div class="row">
                                    <a href="/" class="text-center">
                                        {{-- <img src="../assets/images/oesximg/logo.png" style="margin-right: 10px;" class="" alt="logo" width="100px"> --}}
                                        <img src="../assets/images/oesximg/NETI.png" class="pt-2" alt="logo" width="300px">
                                    </a>
                                </div>
                                <hr>
                            </div>

                            <x-validation-errors class="mb-4" style="color: red;" />

                            @if (session('status'))
                            <div class="mb-4 font-medium text-sm text-green-600">
                                {{ session('status') }}
                            </div>
                            @endif
                            <!-- Form -->
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <!-- Username -->
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" id="email" class="form-control" name="email" :value="old('email')" placeholder="Email address here" required autofocus autocomplete="username">
                                </div>
                                <!-- Password -->
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" id="password" type="password" class="form-control" name="password" placeholder="**************" required autocomplete="current-password">
                                </div>
                                <!-- Checkbox -->
                                <div class="d-lg-flex justify-content-between align-items-center mb-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="rememberme">
                                        <label class="form-check-label " for="rememberme">Remember me</label>
                                    </div>
                                    <div>
                                        <a href="{{ route('password.request') }}">Forgot your password?</a>
                                    </div>
                                </div>
                                <div>
                                    <!-- Button -->
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary "> {{ __('Log in') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    @include('layouts.landing.js')
</body>

</html>
