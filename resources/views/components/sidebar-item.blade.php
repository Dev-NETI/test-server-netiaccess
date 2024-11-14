@props(['label','route' => 'a.dashboard'])
<li class="nav-item ">
    <a class="nav-link " href="{{ route($route) }}">
        {{ $slot }}
        {{ $label }}
    </a>
</li>