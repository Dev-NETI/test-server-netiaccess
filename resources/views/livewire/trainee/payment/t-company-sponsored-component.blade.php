<div>
    <div class="mb-3">
        <span wire:loading>
            <livewire:components.loading-screen-component />
        </span>
        <div class="container">
            <div class="row">
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-danger mt-5" wire:click.prevent="create">
                        Submit For Review
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>