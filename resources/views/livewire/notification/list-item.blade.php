<div>
    <li class="list-group-item {{ $log->is_read ? 'bg-muted' : 'bg-light' }}" @if ($log->is_read !== 1) wire:mouseleave="$emitTo('notification.notification-component', 'seen', {{ $log->log_id }})" @endif>
        <div class="row">
            <div class="col">
                <a class="text-body" href="#">
                    <div class="d-flex">
                        <div class="avatar avatar-md avatar-indicators avatar-online">
                            @if (Auth::user())
                            <img src="{{asset('assets/images/avatar/avatar.jpg')}}" class="rounded-circle" alt="avatar">
                            @elseif (Auth::guard('trainee')->user()->imagepath)
                            <img src="/storage/uploads/traineepic/{{Auth::guard('trainee')->user()->imagepath}}" class="rounded-circle" alt="avatar">
                            @else
                            <img src="{{asset('assets/images/avatar/avatar.jpg')}}" class="rounded-circle" alt="avatar">
                            @endif
                        </div>
                </a>
                <div class="ms-3">
                    <h5 class="fw-bold mb-1">{{ $log->f_name }} {{ $log->l_name }}</h5>
                    @if (Auth::user())
                    <p class="mb-3">
                        {{ $log->details }}
                    </p>
                    @elseif (Auth::guard('trainee')->user())
                    <a href="{{route('t.courses')}}">
                        <p class="mb-3">
                            {{ $log->details }}
                        </p>
                    </a>
                    @endif
                    <span class="fs-6 text-muted">
                        <span><span class="fe fe-thumbs-up text-success me-1"></span>{{ \Carbon\Carbon::parse($log->timestamp)->diffForHumans() }}
                        </span>
                        <span class="ms-1">{{ \Carbon\Carbon::parse($log->timestamp)->format('g:i A') }}</span>

                    </span>
                </div>
            </div>
            </a>
        </div>

</div>
</li>
</div>