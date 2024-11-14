<div class="card border-gray">

    <div class="card-header text-center">
        <img src="{{$image_path}}" class="card-img-top img-fluid"
            alt="{{ $image_path }}" >
    </div>

    <div class="card-body">
        <h3 class="card-title fw-bold text-center {{$text_color}}">{{ $name }}</h3>
        <p class="card-text text-center">
            <b>{{ $course }}</b>
            <br>
            {{ $training_date }}
        </p>
        <span class="badge float-end {{$badge_color}}">{{$badge_msg}}</span>
    </div>

    <style>
    .img-fluid{
        width:120px;
        height: 120px;
    }
    </style>
</div>
