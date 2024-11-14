@props(['title', 'index'])
<div class="accordion accordion-flush container-fluid" id="accordionExample">
    <div class="py-3" id="heading{{ $index }}">
        <h3 class="mb-0 fw-bold">
            <a href="#" class="d-flex align-items-center text-inherit collapsed" data-bs-toggle="collapse"
                data-bs-target="#collapse{{ $index }}" aria-expanded="false"
                aria-controls="collapse{{ $index }}">
                <span class="me-auto">{{ $title }}</span>
                <span class="collapse-toggle ms-4">
                    <i class="fe fe-plus text-primary"></i>
                </span>
            </a>
        </h3>
    </div>
    <div id="collapse{{ $index }}" class="collapse" aria-labelledby="heading{{ $index }}"
        data-bs-parent="#accordionExample" style="">
        <div class="container-fluid">
            {{ $slot }}
        </div>
    </div>
</div>
