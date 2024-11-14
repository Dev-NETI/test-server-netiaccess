@props(['cardTitle','cardDescription'])
<div class="container-fluid mt-5 mb-2">
    <div class="card ">

        <div class="card-header text-center">
            <h2 class="h1 fw-bold mt-3">{{$cardTitle}}
            </h2>
            <p class="mb-0 fs-4">{{$cardDescription}}</p>
        </div>

        <div class="card-body">
            {{$slot}}
        </div>

    </div>
</div>
