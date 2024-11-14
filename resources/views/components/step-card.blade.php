@props(['icon', 'title', 'description', 'count'])
<div class="col-xl-3 col-lg-6 col-md-6 col-12 mt-3 " >
    
    <div class="card border-top border-muted border-4 card-hover-with-icon card-img-top">

        <div class="card-header text-center" style="background-color: #2980b9;" data-bs-toggle="tooltip"
            data-bs-placement="top" data-bs-title="Click to go to {{$title}}" {{ $attributes }}>
            <span class="rounded-top-md card-mg-top mt-5">
                <i class="{{ $icon }}" style="color: antiquewhite;font-size:30px;"></i>
            </span>
            <h5 class="mt-2 text-white">
                {{ $title }}
            </h5>
        </div>
        
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="badge bg-info-soft">
                    {{ $description }}
                </span>

            </div>
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="mb-0 text-muted">
                    <h2>{{ $count }}</h2>
                    </p>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-center">
                {{ $slot }}
            </div>

        </div>

    </div>

</div>
