<li class="list-group-item">
    <a wire:click="attachSignature()"
        class="d-flex justify-content-between align-items-center text-inherit text-decoration-none">
        <div class="text-truncate">
            <span class="icon-shape bg-success text-white icon-sm rounded-circle me-2"><i
                    class="bi bi-pen-fill"></i></span>
            <span>
                @if ($is_SignatureAttached == 0)
                    Attach Signature
                @else
                    Remove Signature
                @endif
            </span>
        </div>
    </a>
</li>
