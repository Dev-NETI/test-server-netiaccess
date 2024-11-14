<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('layouts.landing.head')
    @include('layouts.libraries.libraries')
</head>

<body>
    @include('layouts.landing.header')

    <div class="row">
        <div class="col-md-6 offset-md-3 mt-5 card">
            <h5 class="card-header text-danger">On going maintenance!</h5>
            <div class="card-body">
                <p class="card-text">We are currently upgrading the server for NETI-OEX. Services will be available later at 9 PM, offering improved speed and a better user experience. Thank you for your patience.</p>
            </div>
        </div>
    </div>

    @include('layouts.landing.js')
    @include('layouts.sweetalert')
</body>

</html>
