@props(['submit'])

<form wire:submit.prevent="{{ $submit }}">
    @csrf
    <div class="row">
            {{ $form }}
    </div>

    @if (isset($actions))
        <div
            class="row">
            {{ $actions }}
        </div>
    @endif
</form>
