<html lang="en">

<head>
    @include('layouts.landing.head')
    @include('layouts.libraries.libraries')
</head>

<body>
    @include('layouts.trainee.theader')
    {{ $slot }}
    
    @include('layouts.landing.footer')
    @include('layouts.landing.js')
    @include('layouts.trainee.script')
    @include('layouts.sweetalert')

    @stack('scripts')
</body>

</html>
