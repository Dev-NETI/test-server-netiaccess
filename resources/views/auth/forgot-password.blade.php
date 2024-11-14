<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('layouts.landing.head')
    @include('layouts.libraries.libraries')
    <style>
        body {
            background-image: url("{{asset('assets/images/oesximg/card.jpg')}}");
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
                                <div class="row"><a href="/" class="text-center"><img src="../assets/images/oesximg/NETI.png" class="pt-2 border-start" alt="logo" width="300px"></a></div>
                            </div>
                            <div class="mb-4 text-sm text-gray-600">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" class="form-control" name="email" :value="old('email')" placeholder="Email address here" required autofocus autocomplete="username">
            </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary "> {{ __('Reset Password') }}</button>
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
