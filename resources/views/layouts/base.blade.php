<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('layouts.landing.head')
    @include('layouts.libraries.libraries')
</head>

<body>
    @include('layouts.landing.header')

    {{ $slot }}

    @include('layouts.landing.footer')
    @include('layouts.landing.js')
    @include('layouts.sweetalert')

    @stack('scripts')
</body>

</html>