<li class="list-group-item">
    <a wire:loading.class="disabled" wire:click="updateStatus({{$updateStatus}})"
        class="d-flex disabled justify-content-between align-items-center text-inherit text-decoration-none">
        <div class="text-truncate">
            <span class="icon-shape bg-success text-white icon-sm rounded-circle me-2"><i
                    class="bi bi-send-check-fill"></i></span>
            <span>{{ $title }}
                <span wire:loading>
                    <livewire:components.loading-screen-component />
                </span>
            </span>
        </div>
    </a>
</li>