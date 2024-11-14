@props(['model', 'label'])
<section>
    <label>{{ $label }}</label>
    <input wire:model.lazy="{{$model}}" wire:loading.attr="disabled" {{ $attributes->merge(['class' =>
    'form-control']) }} />
    @error($model)
    <small class="text-danger">{{ $message }}</small>
    @enderror
</section>