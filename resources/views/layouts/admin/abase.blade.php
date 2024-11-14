<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.landing.head')
    @include('layouts.libraries.libraries')
</head>

<body>
    <div id="db-wrapper">

        @include('layouts.admin.aheader')
        <main id="page-content">
            @include('layouts.admin.anavbar')
            {{ $slot }}


            <x-lean::console-log />

            @include('layouts.partials._DataPrivacy')

        </main>
    </div>

    @include('layouts.admin.ajs')
    @include('layouts.sweetalert')

    @livewireScripts
    @stack('scripts')
</body>

</html>