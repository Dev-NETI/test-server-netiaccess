<div>
    <li class="dropdown stopevent">
        <a class="btn btn-light btn-icon rounded-circle  text-muted"
            href="#" role="button" id="dropdownNotification" data-bs-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
            <i class="bi bi-chat"></i>
        </a>
        {{-- {{ $isAllLogsRead ? '' : 'indicator indicator-primary' }} --}}
        <div class="dropdown-menu dropdown-menu-end dropdown-menu-lg" aria-labelledby="dropdownNotification">
            <div>
                <div class="border-bottom px-3 pb-3 d-flex justify-content-between align-items-center">
                    <span class="h4 mb-0">Conversations</span>
                    <a href="# " class="text-muted">
                        <span class="align-middle">
                            <i class="fe fe-settings me-1"></i>
                        </span>
                    </a>
                </div>
                <!-- Notification group -->
                <ul class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;">

                    {{-- {{dd($logs)}} --}}
                    {{-- @if (count($logs))
                        @foreach ($logs as $log)
                            <livewire:notification.list-item :log="$log" wire:key="{{ $log->log_id }}" />
                        @endforeach
                    @else
                        <li class="list-group-item bg-light">
                            <div class="row">
                                <div class="col">

                                    <div class="d-flex">
                                        <span> No new notifications</span>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endif --}}
                </ul>


                <div class="border-top px-3 pt-3 pb-0">

                    <a href="{{ route('a.notification-history') }}" class="text-link fw-semibold">
                        See all conversations
                    </a>
                </div>
            </div>
        </div>
    </li>
</div>
