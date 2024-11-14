<div>
    <input type="text" wire:model="message" wire:keydown.enter="updateMessage">
    <div wire:mouseover="showTooltip" wire:mouseout="hideTooltip">
        {{ $message }}
    </div>
</div>

@push('scripts')
    {{-- tool tip --}}
    <!-- Development -->
    <script src="{{asset('assets/libs/@popperjs/core/dist/umd/popper.min.js')}}"></script>
    <script src="{{asset('assets/libs/tippy.js/dist/tippy-bundle.umd.js')}}"></script>

    <script>
        document.addEventListener('livewire:load', function () {
            Livewire.hook('message.sent', (message, component) => {
                tippy('[wire\:mouseover]', {
                    content: component.message,
                });
            });
        });
    </script>
@endpush