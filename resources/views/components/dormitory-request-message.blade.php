@if (session()->has('success'))
    <livewire:dormitory.components.success-card-component />
@elseif(session()->has('error'))
    @include('components.alert-danger')
@endif
