@props(['on'])


<div x-data="{ show: false }" x-init="@this.on('{{ $on }}', () => {
    show = true;
    setTimeout(() => { show = false }, 2000)
})" x-show.transition.out.opacity.duration.1500ms="show"
    x-transition:leave.opacity.duration.1500ms>
    <label class="text-success"> Value updated</label>

</div>
