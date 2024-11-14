@props(['label','id'])
<li class="nav-item">
    <a class="nav-link   collapsed " href="#" data-bs-toggle="collapse"
        data-bs-target="#{{ $id }}" aria-expanded="false" aria-controls="{{ $id }}">
        <i class="bi bi-receipt me-2"></i>{{ $label }}
    </a>
    <div id="{{ $id }}" class="collapse " data-bs-parent="#sideNavbar">
        <ul class="nav flex-column">
            {{ $slot }}
        </ul>
    </div>
</li>