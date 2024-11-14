<div class="form-check mt-3">
    <input class="form-check-input" type="checkbox" wire:model="select_email" value="{{ $email_id }}"
        id="flexCheckDefault{{ $email_id }}">
    <label class="form-check-label float-start" for="flexCheckDefault{{ $email_id }}">
        {{ $email }}
    </label>
    @if ($success_badge == 1)
        <span class="badge text-bg-{{$success_badge_class}}">{{$success_badge_msg}}</span>
    @endif
</div>
