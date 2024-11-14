@props(['title'])
<div class="col-md-12 card-body row ">

    <div class="col-md-3 border-top border-bottom border-left rounded text-center d-flex align-items-center"
        style="background-color: #2980b9; height: 100%;">
        <h2 class="text-white mx-auto">{{ $title }}</h2>
    </div>
    <div class="col-md-9 border-right border-top border-bottom rounded">
        {{ $slot }}
    </div>


</div>
