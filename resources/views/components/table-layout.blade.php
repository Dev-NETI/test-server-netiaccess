@props(['data','total'])
<section>
    <div class="col-md-4 offset-md-8">
        <input type="search" class="form-control float-end" placeholder="Search..." {{ $attributes }}>
    </div>

    <div class="col-md-12 table-responsive">
        <table class="table table-hover table-striped text-sm">
            <thead>
                {{ $thead }}
            </thead>
            <tbody>
                {{ $slot }}
            </tbody>
        </table>
    </div>

    <div class="col-md-10 offset-md-1">
        {{ $data->links('livewire.components.customized-pagination-link') }}
        Total: {{ $total }}
    </div>
</section>
